<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hrtransfer extends Model
{
    use HasFactory;

    protected $table = 'hrtransfer';
    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'transfer_date',
        'old_branch',
        'new_branch',
        'reason',
        'status',
    ];
    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function oldBranch()
    {
        return $this->belongsTo(Branch::class, 'old_branch');
    }

    // Mối quan hệ với new_branch
    public function newBranch()
    {
        return $this->belongsTo(Branch::class, 'new_branch');
    }
}
