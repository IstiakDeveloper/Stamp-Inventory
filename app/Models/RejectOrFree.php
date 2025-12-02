<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectOrFree extends Model
{
    use HasFactory;
    
    protected $fillable = ['date', 'sets', 'purchase_price_per_set', 'purchase_price_total', 'note'];
    
}
