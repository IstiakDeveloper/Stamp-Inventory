<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutstandingBalanceHistory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'branch_id',
        'outstanding_balance',
        'date',
    ];
}
