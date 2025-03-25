<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagePermission extends Model
{
    use HasFactory;

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_page_permissions');
    }
}
