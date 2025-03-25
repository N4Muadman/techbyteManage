<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class work_schedule extends Model
{
    use HasFactory;
    protected $table = 'work_schedule';

    protected $fillable = [
        'date',
        'employee_id',
        'work_shift_id',
        'branch_id',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function work_shift(){
        return $this->belongsTo(work_shift::class, 'work_shift_id');
    }
    public function branch(){
        return $this->belongsTo(branch::class, 'branch_id');
    }
    public function attendance(){
        return $this->hasMany(attendance::class, 'work_schodule_id');
    }
}
