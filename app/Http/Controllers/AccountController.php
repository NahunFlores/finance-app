<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\CurrencyService;

class AccountController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->middleware('auth');
        $this->currencyService = $currencyService;
    }

    public function index()
    {
        $accounts = Account::where('user_id', Auth::id())->get();
        $rate = $this->currencyService->getUsdToHnlRate();

        foreach ($accounts as $account) {
            $account->hnl_balance = $this->currencyService->convertToHnl($account->balance, $account->currency);
            if ($account->currency === 'USD' && $account->credit_limit) {
                $account->hnl_credit_limit = $this->currencyService->convertToHnl($account->credit_limit, $account->currency);
            }

            if ($account->type === 'credit') {
                // Total debt combining native HNL balance + USD balance converted to HNL
                $account->total_hnl_debt = $account->balance + ($account->usd_balance * $rate);

                // Available Credit Calculation
                if ($account->credit_limit) {
                    if ($account->currency === 'USD') {
                        $usedLimitUsd = $account->usd_balance + ($account->balance / $rate);
                        $account->available_credit = $account->credit_limit - $usedLimitUsd;
                    }
                    else {
                        // Limit is in HNL
                        $account->available_credit = $account->credit_limit - $account->total_hnl_debt;
                    }
                }
            }
        }

        return view('accounts.index', compact('accounts', 'rate'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:credit,debit,cash',
            'currency' => 'required|in:HNL,USD',
            'balance' => 'nullable|numeric',
            'balance_hnl' => 'nullable|numeric',
            'usd_balance' => 'nullable|numeric',
            'credit_limit' => 'nullable|numeric',
            'cut_off_day' => 'nullable|integer|min:1|max:31',
            'payment_due_day' => 'nullable|integer|min:1|max:31',
        ]);

        $validated['user_id'] = Auth::id();

        if ($validated['type'] === 'credit') {
            $validated['balance'] = $validated['balance_hnl'] ?? 0;
            $validated['usd_balance'] = $validated['usd_balance'] ?? 0;
        }
        else {
            $validated['balance'] = $validated['balance'] ?? 0;
            $validated['usd_balance'] = 0;
        }

        unset($validated['balance_hnl']);

        Account::create($validated);

        return redirect()->route('accounts.index')->with('success', 'Cuenta creada exitosamente.');
    }

    public function destroy(Account $account)
    {
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }

        $account->delete();

        return redirect()->route('accounts.index')->with('success', 'Cuenta eliminada exitosamente.');
    }

    public function edit(Account $account)
    {
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:credit,debit,cash',
            'currency' => 'required|in:HNL,USD',
            'balance' => 'nullable|numeric',
            'balance_hnl' => 'nullable|numeric',
            'usd_balance' => 'nullable|numeric',
            'credit_limit' => 'nullable|numeric',
            'cut_off_day' => 'nullable|integer|min:1|max:31',
            'payment_due_day' => 'nullable|integer|min:1|max:31',
        ]);

        if ($validated['type'] === 'credit') {
            $validated['balance'] = $validated['balance_hnl'] ?? 0;
            $validated['usd_balance'] = $validated['usd_balance'] ?? 0;
        }
        else {
            $validated['balance'] = $validated['balance'] ?? 0;
            $validated['usd_balance'] = 0;
        }

        unset($validated['balance_hnl']);

        $account->update($validated);

        return redirect()->route('accounts.show', $account)->with('success', 'Cuenta actualizada exitosamente.');
    }

    public function show(Account $account)
    {
        if ($account->user_id !== Auth::id())
            abort(403);

        $rate = $this->currencyService->getUsdToHnlRate();

        $account->hnl_balance = $this->currencyService->convertToHnl($account->balance, $account->currency);
        if ($account->currency === 'USD' && $account->credit_limit) {
            $account->hnl_credit_limit = $this->currencyService->convertToHnl($account->credit_limit, $account->currency);
        }

        if ($account->type === 'credit') {
            $account->total_hnl_debt = $account->balance + ($account->usd_balance * $rate);

            if ($account->credit_limit) {
                if ($account->currency === 'USD') {
                    $usedLimitUsd = $account->usd_balance + ($account->balance / $rate);
                    $account->available_credit = $account->credit_limit - $usedLimitUsd;
                }
                else {
                    $account->available_credit = $account->credit_limit - $account->total_hnl_debt;
                }
            }
        }

        $transactions = $account->transactions()->latest('transaction_date')->get();
        $installments = $account->installments;
        return view('accounts.show', compact('account', 'transactions', 'installments', 'rate'));
    }
}
