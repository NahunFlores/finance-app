<?php

namespace App\Services;

use App\Models\Account;

class PredictionService
{
    /**
     * Predict future payments and balances based on current state and fixed monthly constraints.
     */
    public function generatePrediction(Account $account, int $months = 6)
    {
        $predictions = [];
        $currentBalance = $account->balance;

        $activeInstallments = $account->installments()
            ->whereColumn('paid_installments', '<', 'total_installments')
            ->get();

        for ($i = 1; $i <= $months; $i++) {
            $monthlyFixedCharge = 0;

            foreach ($activeInstallments as $installment) {
                if ($installment->paid_installments + $i <= $installment->total_installments) {
                    $monthlyFixedCharge += $installment->monthly_amount;
                }
            }

            $predictions[] = [
                'month' => now()->addMonths($i)->format('F Y'),
                'expected_minimum_payment' => $monthlyFixedCharge, // simplified
                'projected_debt' => $account->total_hnl_debt ?? $account->balance // Fallback to balance if total_hnl_debt isn't set
            ];
        }

        return $predictions;
    }
}
