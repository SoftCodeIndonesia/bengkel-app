<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use HasRoles {
        hasPermissionTo as protected hasPermissionToByRole;
    }

    /**
     * When configured, a user's selected menu permissions take precedence over
     * permissions inherited from their roles. Admin accounts retain role access.
     */
    public function hasPermissionTo($permission, ?string $guardName = null): bool
    {
        if (!$this->menu_access_configured || $this->hasRole('admin')) {
            return $this->hasPermissionToByRole($permission, $guardName);
        }

        return $this->hasDirectPermission($permission);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'menu_access_configured' => 'boolean',
    ];
}
