<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->middleware('auth');
        $this->transactionService = $transactionService;
    }

    public function store(Request $request, Account $account)
    {
        if ($account->user_id !== Auth::id())
            abort(403);

        $validated = $request->validate([
            'type' => 'required|in:income,expense,withdrawal,payment',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|in:HNL,USD',
            'fee_amount' => 'nullable|numeric|min:0',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $this->transactionService->recordTransaction(
            $account,
            $validated['type'],
            $validated['amount'],
            $validated['fee_amount'] ?? 0,
            $validated['transaction_date'],
            $validated['description'],
            $validated['currency'] ?? null
        );

        return redirect()->route('accounts.show', $account)->with('success', 'Transaction recorded.');
    }

    public function edit(\App\Models\Transaction $transaction)
    {
        $account = $transaction->account;
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }

        return view('transactions.edit', compact('transaction', 'account'));
    }

    public function update(Request $request, \App\Models\Transaction $transaction)
    {
        $account = $transaction->account;
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => 'required|in:income,expense,withdrawal,payment',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|in:HNL,USD',
            'fee_amount' => 'nullable|numeric|min:0',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $this->transactionService->updateTransaction($transaction, $validated);

        return redirect()->route('accounts.show', $account)->with('success', 'Transacción actualizada exitosamente.');
    }

    public function destroy(\App\Models\Transaction $transaction)
    {
        $account = $transaction->account;
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }

        $this->transactionService->deleteTransaction($transaction);

        return redirect()->route('accounts.show', $account)->with('success', 'Transacción eliminada exitosamente.');
    }
}
