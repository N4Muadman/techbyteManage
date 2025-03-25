<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPerformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'total_work',
        'total_late_arrivals',
        'total_late_hours',
        'total_project',
        'total_revenue',
    ];

    public function performance_review(){
        return $this->hasOne(employee_performance_reviews::class);
    }

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
