<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    public function pagePermissions()
    {
        return $this->hasMany(PagePermission::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'page_permissions');
    }
}
