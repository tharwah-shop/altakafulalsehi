<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'status',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * العلاقات
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withPivot('assigned_at', 'assigned_by')->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function medicalCenters()
    {
        return $this->hasMany(MedicalCenter::class, 'created_by');
    }

    /**
     * التحقق من الصلاحيات
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        return $this->roles->contains($role);
    }

    public function hasPermission($permission)
    {
        return $this->roles->flatMap->permissions->contains('name', $permission);
    }

    public function hasAnyRole($roles)
    {
        return $this->roles->whereIn('name', $roles)->isNotEmpty();
    }
}
