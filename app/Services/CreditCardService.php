<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Installment;
use Carbon\Carbon;

class CreditCardService
{
    /**
     * Process credit card cut-off. Adds minuciota (installments) to balance if appropriate.
     */
    public function processCutOff(Account $account)
    {
        if ($account->type !== 'credit') {
            return;
        }

        // Logic to apply monthly installments
        $activeInstallments = $account->installments()
            ->whereColumn('paid_installments', '<', 'total_installments')
            ->get();

        foreach ($activeInstallments as $installment) {
            // Check if installment starts this month or earlier
            if (Carbon::parse($installment->start_date)->isPast() || Carbon::parse($installment->start_date)->isCurrentMonth()) {
                $installment->paid_installments += 1;
                $installment->save();

            // Note: Balance is usually already considering the total amount of the installment at purchase.
            // Or if we only charge the 'monthly_amount' to balance each month:
            // $account->balance += $installment->monthly_amount;
            // For this implementation, we assume the total debt is already in the balance. 
            // The installment just helps with tracking what's due this cycle.
            }
        }

        $account->save();
    }
}
