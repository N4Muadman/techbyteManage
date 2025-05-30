<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notification extends Model
{
    use HasFactory;
    protected $table = 'notifycation';
    protected $fillable = [
        'name',
        'description',
        'role_id',
        'employee_id',
        'status',
        'url',
        'branch_id',
        'icon',
    ];
}
