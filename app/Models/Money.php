<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Money extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'amount', 'details', 'type'];
    
    public const TYPE_CASH_IN = 'cash_in';
    public const TYPE_CASH_OUT = 'cash_out';

    // Define a method to get the total amount for cash_in transactions
    public static function getTotalCashIn()
    {
        return static::where('type', self::TYPE_CASH_IN)->sum('amount');
    }

    // Define a method to get the total amount for cash_out transactions
    public static function getTotalCashOut()
    {
        return static::where('type', self::TYPE_CASH_OUT)->sum('amount');
    }
}
