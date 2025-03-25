<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'base_salary',
        'allowance',
        'bonus',
        'deduction',
        'total_work',
        'total_late_arrivals',
        'total_late_hours',
        'description',
        'salary_date'
    ];


    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
