<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employee';

    protected $fillable = [
        "full_name",
        "date_of_birth",
        "gender",
        "phone_number",
        "address",
        "email",
        "start_date",
        "end_date",
        "position",
        'base_salary',
        "branch_id",
        "profile"
    ];

    // Định nghĩa quan hệ với bảng `Attendance`
    public function attendance()
    {
        return $this->hasMany(attendance::class, 'employee_id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }
    // Định nghĩa quan hệ với bảng `Salary`
    public function salary()
    {
        return $this->hasMany(salary::class, 'employee_id');
    }
    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function latestSalary()
    {
        return $this->hasOne(salary::class, 'employee_id')->latestOfMany('salary_date');
    }
    public function leave_request(){
        return $this->hasMany(leave_request::class, 'employee_id');
    }

    public function work_schedule(){
        return $this->hasMany(work_schedule::class, 'employee_id');
    }
}
