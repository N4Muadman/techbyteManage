<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class checkin_token extends Model
{
    use HasFactory;
    protected $table = 'checkin_token';
    protected $fillable = [
        'employee_id',
        'token',
        'created_at',
        'expires_at',
        'status',
    ];
}
