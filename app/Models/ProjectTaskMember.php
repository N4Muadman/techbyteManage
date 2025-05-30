<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTaskMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_task_id',
        'project_member_id',
        'role',
    ];
}
