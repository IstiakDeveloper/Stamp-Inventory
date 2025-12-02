<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundManagement extends Model
{

    use HasFactory;

    protected $fillable = ['date', 'amount', 'type', 'note'];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!in_array($model->type, ['fund_in', 'fund_out'])) {
                throw new \InvalidArgumentException("Invalid type: must be either 'fund_in' or 'fund_out'");
            }
        });
    }
}
