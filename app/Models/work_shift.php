<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class work_shift extends Model
{
    use HasFactory;
    protected $table = 'work_shift';

    protected $fillable = ['start_time', 'end_time', 'name'];

    public function work_schedule(){
        return $this->hasMany(work_schedule::class, 'work_shift_id');
    }
}
