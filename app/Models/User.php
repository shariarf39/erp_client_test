<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
        'role_id',
        'is_active',
        'last_login',
        'two_fa_enabled',
        'two_fa_secret',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_fa_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'is_active' => 'boolean',
        'two_fa_enabled' => 'boolean',
    ];

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function permissions()
    {
        return $this->role->permissions ?? collect();
    }

    // Helper Methods
    public function hasPermission($module, $action = 'view')
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->permissions()
            ->where('module', $module)
            ->where('action', $action)
            ->exists();
    }

    public function isSuperAdmin()
    {
        return $this->role && $this->role->name === 'Super Admin';
    }
}
