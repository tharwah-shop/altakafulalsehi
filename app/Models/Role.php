<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * العلاقات
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('assigned_at', 'assigned_by')->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * إضافة صلاحية للدور
     */
    public function givePermissionTo($permission)
    {
        return $this->permissions()->attach($permission);
    }

    /**
     * إزالة صلاحية من الدور
     */
    public function revokePermissionTo($permission)
    {
        return $this->permissions()->detach($permission);
    }

    /**
     * التحقق من وجود صلاحية
     */
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->contains('name', $permission);
        }
        return $this->permissions->contains($permission);
    }
}
