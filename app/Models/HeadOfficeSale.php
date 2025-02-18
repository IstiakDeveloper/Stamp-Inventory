<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadOfficeSale extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'sets', 'per_set_price', 'total_price', 'cash'];

    public function headOfficeDue()
    {
        return $this->hasOne(HeadOfficeDue::class);
    }

}
