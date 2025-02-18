<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadOfficeDue extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'head_office_sale_id', 'note', 'due_amount'];

    public function headOfficeSale()
    {
        return $this->belongsTo(HeadOfficeSale::class);
    }
}
