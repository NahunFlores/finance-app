<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id', 'description', 'total_amount', 'monthly_amount', 'total_installments', 'paid_installments', 'start_date'
    ];

    protected $casts = [
        'start_date' => 'date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
