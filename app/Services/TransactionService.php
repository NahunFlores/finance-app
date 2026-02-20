<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;

class TransactionService
{
    /**
     * Record a new transaction and update the account balance.
     */
    public function recordTransaction(Account $account, string $type, float $amount, float $feeAmount = 0, string $date, ?string $description = null, ?string $currency = null)
    {
        // For credit cards, 'expense' increases the balance (debt), 'payment' decreases it.
        // For debit/cash, 'income' increases balance, 'expense' decreases it.

        $originalCurrency = $currency ?? $account->currency;
        $originalAmount = $amount;
        $exchangeRate = null;

        /** @var \App\Services\CurrencyService $currencyService */
        $currencyService = app(\App\Services\CurrencyService::class);

        // Get exchange rate for tracking purposes
        if ($originalCurrency !== $account->currency) {
            if ($originalCurrency === 'HNL') {
                $exchangeRate = 1 / $currencyService->getUsdToHnlRate();
            }
            else {
                $exchangeRate = $currencyService->getUsdToHnlRate();
            }
        }

        if ($account->type === 'credit') {
            // For credit cards, keep USD and HNL debts separate
            $balanceField = ($originalCurrency === 'USD') ? 'usd_balance' : 'balance';
            $balanceChange = $originalAmount + $feeAmount; // Fees are charged in the original currency of the transaction

            if ($type === 'expense' || $type === 'withdrawal') {
                $account->$balanceField += $balanceChange;
            }
            elseif ($type === 'payment') {
                $account->$balanceField -= $balanceChange;
            }

            // To maintain compatibility in the transactions table, 'amount' will record the original amount
            $amount = $originalAmount;

        }
        else {
            // Debit or Cash: Everything must be stored and calculated in the account's base currency (HNL)
            if ($originalCurrency !== $account->currency) {
                $amount = $currencyService->convert($originalAmount, $originalCurrency, $account->currency);
            }
            $balanceChange = $amount + $feeAmount;

            if ($type === 'income') {
                $account->balance += $balanceChange;
            }
            elseif ($type === 'expense' || $type === 'withdrawal') {
                $account->balance -= $balanceChange;
            }
        }

        $account->save();

        return Transaction::create([
            'account_id' => $account->id,
            'type' => $type,
            'amount' => $amount,
            'fee_amount' => $feeAmount,
            'transaction_date' => $date,
            'description' => $description,
            'original_currency' => $originalCurrency,
            'original_amount' => $originalAmount,
            'exchange_rate' => $exchangeRate,
        ]);
    }

    /**
     * Reverse the effect of a transaction on its account balance.
     */
    public function reverseTransactionEffect(Transaction $transaction)
    {
        $account = $transaction->account;

        if ($account->type === 'credit') {
            $balanceField = ($transaction->original_currency === 'USD') ? 'usd_balance' : 'balance';
            $balanceChange = $transaction->original_amount + $transaction->fee_amount;

            if ($transaction->type === 'expense' || $transaction->type === 'withdrawal') {
                $account->$balanceField -= $balanceChange; // Reverse increase
            }
            elseif ($transaction->type === 'payment') {
                $account->$balanceField += $balanceChange; // Reverse decrease
            }
        }
        else {
            $balanceChange = $transaction->amount + $transaction->fee_amount;

            if ($transaction->type === 'income') {
                $account->balance -= $balanceChange; // Reverse increase
            }
            elseif ($transaction->type === 'expense' || $transaction->type === 'withdrawal') {
                $account->balance += $balanceChange; // Reverse decrease
            }
        }

        $account->save();
    }

    /**
     * Delete a transaction completely and revert its effects.
     */
    public function deleteTransaction(Transaction $transaction)
    {
        $this->reverseTransactionEffect($transaction);
        return $transaction->delete();
    }

    /**
     * Update an existing transaction and recalculate the account balance.
     */
    public function updateTransaction(Transaction $transaction, array $data)
    {
        // Revert old effect
        $this->reverseTransactionEffect($transaction);
        $account = $transaction->account;

        $originalCurrency = $data['currency'] ?? $account->currency;
        $originalAmount = $data['amount'];
        $feeAmount = $data['fee_amount'] ?? 0;
        $exchangeRate = null;

        /** @var \App\Services\CurrencyService $currencyService */
        $currencyService = app(\App\Services\CurrencyService::class);

        if ($originalCurrency !== $account->currency) {
            if ($originalCurrency === 'HNL') {
                $exchangeRate = 1 / $currencyService->getUsdToHnlRate();
            }
            else {
                $exchangeRate = $currencyService->getUsdToHnlRate();
            }
        }

        $amount = $originalAmount;

        // Apply new effect
        if ($account->type === 'credit') {
            $balanceField = ($originalCurrency === 'USD') ? 'usd_balance' : 'balance';
            $balanceChange = $originalAmount + $feeAmount;

            if ($data['type'] === 'expense' || $data['type'] === 'withdrawal') {
                $account->$balanceField += $balanceChange;
            }
            elseif ($data['type'] === 'payment') {
                $account->$balanceField -= $balanceChange;
            }
        }
        else {
            if ($originalCurrency !== $account->currency) {
                $amount = $currencyService->convert($originalAmount, $originalCurrency, $account->currency);
            }
            $balanceChange = $amount + $feeAmount;

            if ($data['type'] === 'income') {
                $account->balance += $balanceChange;
            }
            elseif ($data['type'] === 'expense' || $data['type'] === 'withdrawal') {
                $account->balance -= $balanceChange;
            }
        }

        $account->save();

        // Update model
        $transaction->update([
            'type' => $data['type'],
            'amount' => $amount,
            'fee_amount' => $feeAmount,
            'transaction_date' => $data['transaction_date'],
            'description' => $data['description'],
            'original_currency' => $originalCurrency,
            'original_amount' => $originalAmount,
            'exchange_rate' => $exchangeRate,
        ]);

        return $transaction;
    }
}
