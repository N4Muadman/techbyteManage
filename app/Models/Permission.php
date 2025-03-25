<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    public function pagePermissions()
    {
        return $this->hasMany(PagePermission::class);
    }

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'page_permissions');
    }
}

