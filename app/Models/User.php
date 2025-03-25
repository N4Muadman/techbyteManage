<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function role(){
        return $this->belongsTo(role::class, 'role_id');
    }

    public function hasPermissionOnPage($permissionId, $pageId)
    {
        if ($this->role_id == 1){
            return true;
        }

        return $this->role->pagePermissions()
                ->whereHas('page', function ($query) use ($pageId) {
                    $query->where('id', $pageId);
                })
                ->whereHas('permission', function ($query) use ($permissionId) {
                    $query->where('id', $permissionId);
                })
                ->where('status', 1)
                ->exists();
    }

    public function hasPermissionOnPath($permissionId, $path){
        return $this->role->pagePermissions()
                ->whereHas('page', function ($query) use ($path) {
                    $query->where('slug', $path);
                })
                ->whereHas('permission', function ($query) use ($permissionId) {
                    $query->where('id', $permissionId);
                })
                ->where('status', 1)
                ->exists();
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
