<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    
    protected $fillable = ['date', 'address', 'sets', 'pieces', 'price_per_set', 'price_per_piece', 'total_price', 'note'];

    
}
