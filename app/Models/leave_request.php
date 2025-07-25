<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class leave_request extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'leave_type',
        'start_date',
        'end_date',
        'reason_description',
        'leave_status',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
