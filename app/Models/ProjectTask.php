<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ProjectTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'progress',
        'due_date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function members()
    {
        return $this->belongsToMany(ProjectMember::class, 'project_task_members');
    }

    public function getStatusClassAttribute()
    {
        if ($this->isOverdue()) {
            return 'overdue-task';
        }

        return match ($this->status) {
            'completed' => 'completed-task',
            'in_progress' => 'progress-task',
            'pending' => '',
            default => '',
        };
    }

    public function getStatusLabelAttribute() 
    {
        if ($this->isOverdue()) {
            return 'Quá hạn';
        }

        return match ($this->status) {
            'completed' => 'Hoàn thành',
            'in_progress' => 'Đang làm',
            'pending' => 'Chưa làm',
            default => '',
        };
    }

    public function isOverdue()
    {
        return $this->due_date < Carbon::today()->format('Y-m-d') && $this->status !== 'completed';
    }
}
