<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'contract_code',
        'contract_value',
        'advance_money',
        'date',
        'note'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

}
