<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\PredictionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PredictionController extends Controller
{
    protected $predictionService;
    protected $currencyService;

    public function __construct(PredictionService $predictionService, \App\Services\CurrencyService $currencyService)
    {
        $this->middleware('auth');
        $this->predictionService = $predictionService;
        $this->currencyService = $currencyService;
    }

    public function index()
    {
        $accounts = Account::where('user_id', Auth::id())->where('type', 'credit')->get();
        $predictions = [];
        $rate = $this->currencyService->getUsdToHnlRate();

        foreach ($accounts as $account) {
            $account->total_hnl_debt = $account->balance + ($account->usd_balance * $rate);
            $account->hnl_balance = $account->total_hnl_debt; // To maintain compatibility with views

            $accountPredictions = $this->predictionService->generatePrediction($account, 6);

            // Add HNL conversion to each prediction step IF needed (but we already converted above so it's all HNL now)
            foreach ($accountPredictions as &$pred) {
                // By definition, installments and debt are now being projected in HNL equivalent purely for simplicity.
                $pred['hnl_expected_minimum_payment'] = $pred['expected_minimum_payment'];
                $pred['hnl_projected_debt'] = $pred['projected_debt'];
            }
            $predictions[$account->id] = $accountPredictions;
        }

        return view('predictions.index', compact('accounts', 'predictions', 'rate'));
    }
}
