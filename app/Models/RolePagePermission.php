<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RolePagePermission extends Pivot
{
    protected $table = 'role_page_permissions';

    protected $fillable = [
        'role_id',
        'page_permission_id',
        'status'
    ];

    public function pagePermission(){
        return $this->belongsTo(PagePermission::class);
    }
    public function role(){
        return $this->belongsTo(Role::class);
    }
}
