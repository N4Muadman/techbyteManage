<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branch';
    public $timestamps = false;
    protected $fillable = [
        'branch_name',
        'address',
        'phone_number',
        'address_ip',
        'status',
    ];
    public function employee(){
        return $this->hasMany(Employee::class, 'branch_id');
    }
    public function recruitment(){
        return $this->hasMany(recruitment::class, 'branch_id');
    }

    public function work_schedule(){
        return $this->hasMany(work_schedule::class, 'branch_id');
    }
}
