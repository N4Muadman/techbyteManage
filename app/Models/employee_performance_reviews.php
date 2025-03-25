<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employee_performance_reviews extends Model
{
    use HasFactory;
    protected $fillable = [
        'work_performance_id',
        'date',
        'attendance_score',
        'quality_score',
        'productivity_score',
        'development_score',
        'problem_solving_score',
        'overall_score',
        'evaluation_result',
        'reward'
    ];
    public function work_performance(){
        return $this->belongsTo(WorkPerformance::class, 'work_performance_id');
    }
}
