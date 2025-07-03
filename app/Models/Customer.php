<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'phone_number',
        'email',
        'gender',
        'social_network',
        'date_find_to_me',
        'object',
        'employee_id',
        'type',
        'business_customer_since'
    ];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
    public function feedBackCustomer(){
        return $this->hasMany(FeedBackCustomer::class);
    }
    public function contract(){
        return $this->hasMany(Contract::class);
    }
}
