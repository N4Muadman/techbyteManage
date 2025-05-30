<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'role_id',
        'employee_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function role()
    {
        return $this->belongsTo(role::class, 'role_id');
    }

    public function hasPermissionOnPage($permissionId, $pageId)
    {
        if ($this->role_id == 1) {
            return true;
        }

        $cachedPermissions = Cache::rememberForever('user_permissions_' . $this->id, function () {
            return $this->role->pagePermissions()
                ->where('status', 1)
                ->get(['permission_id', 'page_id'])
                ->toArray();
        });


        return collect($cachedPermissions)->contains(function ($permission) use ($permissionId, $pageId) {
            return $permission['permission_id'] == $permissionId
                && $permission['page_id'] == $pageId;
        });
    }

    public function canAccessPage($permissionIds, $pageId)
    {
        if ($this->role_id == 1) {
            return true;
        }

        $cachedPermissions = Cache::rememberForever('user_permissions_' . $this->id, function () {
            return $this->role->pagePermissions()
                ->where('status', 1)
                ->get(['permission_id', 'page_id'])
                ->toArray();
        });

        return collect($cachedPermissions)->contains(function ($permission) use ($permissionIds, $pageId) {
            return in_array($permission['permission_id'], $permissionIds)
                && $permission['page_id'] == $pageId;
        });
    }

    public function is_project_leader($project_leader_id) {
        if ($this->role_id == 1){
            return true;
        }

        return $this->id == $project_leader_id;
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
