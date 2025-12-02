<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Borrower extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'note'
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function payments()
    {
        return $this->hasMany(LoanPayment::class);
    }

    // Total loan amount given to this borrower
    public function getTotalLoanAmountAttribute()
    {
        return $this->loans->sum('amount');
    }

    // Total payment received from this borrower
    public function getTotalPaidAmountAttribute()
    {
        return $this->payments->sum('amount');
    }

    // Remaining balance for this borrower
    public function getRemainingBalanceAttribute()
    {
        return $this->total_loan_amount - $this->total_paid_amount;
    }
}
