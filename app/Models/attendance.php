<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';
    public $timestamps = false;
    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'work_schedule_id',
        'working_hours',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function work_schedule()
    {
        return $this->belongsTo(work_shift::class, 'work_schedule_id');
    }
}
