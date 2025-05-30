<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'start_date',
        'end_date',
        'total_cost',
        'leader_id',
        'description',
        'archive_link',
        'document_link',
        'img',
    ];

    public function members()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class);
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function getTypeLabelAttribute()
    {
        return match ($this->type) {
            'internal' => '🏢 Nội bộ',
            'customer' => '👥 Khách hàng',
            default => ''
        };
    }

    public function getStatusLabelAttribute()
    {
        return match (true) {
            $this->progress == 100 => 'Đã hoàn thành',
            $this->progress < 100 && $this->end_date < Carbon::today()->format('Y-m-d') => 'Đã quá hạn',
            default => 'Đang triển khai'
        };
    }

    public function getStatusClassLabelAttribute()
    {
        return match (true) {
            $this->progress == 100 => 'completed-task',
            $this->progress < 100 && $this->end_date < Carbon::today()->format('Y-m-d') => 'overdue-task',
            default => 'progress-task'
        };
    }

    public function getProgressAttribute()
    {
        if ($this->tasks->count() === 0) {
            return 0;
        }

        $completedCount = $this->tasks->where('status', 'completed')->count();

        return round(($completedCount / $this->tasks->count()) * 100);
    }
}
