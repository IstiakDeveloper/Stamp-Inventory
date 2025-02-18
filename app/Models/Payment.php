<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'amount',
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d', // Ensure datetime casting
    ];
    
}
