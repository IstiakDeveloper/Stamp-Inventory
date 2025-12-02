<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id', 'date', 'sets', 'per_set_price', 'total_price', 'cash', 'receiver_name',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
