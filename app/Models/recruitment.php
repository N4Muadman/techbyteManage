<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recruitment extends Model
{
    use HasFactory;

    protected $table = 'recruitment';
    public $timestamps = false;
    protected $fillable = [
        'position',
        'job_description',
        'posting_date',
        'expiration_date',
        'branch_id',
        'status'
    ];

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
