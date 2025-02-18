<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchSaleOutstanding extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_sale_id',
        'branch_id',
        'date',
        'outstanding_balance',
        'extra_money',
    ];

    public function branchSale()
    {
        return $this->belongsTo(BranchSale::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
