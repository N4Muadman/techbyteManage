<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    use HasFactory;
    protected $table = 'role';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public $timestamps = false;

    public function user(){
        return $this->hasMany(User::class, 'role_id');
    }

    public function pagePermissions()
    {
        return $this->belongsToMany(PagePermission::class, 'role_page_permissions');
    }

    public function RolePagePermission(){
        return $this->hasMany(RolePagePermission::class);
    }

    public function permissions()
    {
        return $this->hasManyThrough(Permission::class, PagePermission::class);
    }
}
