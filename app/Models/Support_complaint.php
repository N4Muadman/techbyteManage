<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support_complaint extends Model
{
    use HasFactory;
    protected $table = 'support_complaint';
    protected $fillable = [
        'employee_id',
        'complaint_type',
        'description',
        'file',
        'status',
    ];
    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
