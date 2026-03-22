<?php

namespace MicroweberPackages\Role\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name',
        'description',
        'color',
        'is_system',
        'sort_order',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get resource-specific permissions for this role
     */
    public function resourcePermissions(): BelongsToMany
    {
        return $this->belongsToMany(ResourcePermission::class, 'role_resource_permissions')
            ->withPivot('is_allowed', 'conditions')
            ->withTimestamps();
    }

    /**
     * Scope to get system roles that cannot be deleted
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Scope to get custom user-defined roles
     */
    public function scopeCustom($query)
    {
        return $query->where('is_system', false);
    }

    /**
     * Check if this role is a system role
     */
    public function isSystem(): bool
    {
        return $this->is_system;
    }

    /**
     * Get users count with this role
     */
    public function getUsersCountAttribute(): int
    {
        return $this->users()->count();
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($role) {
            if (empty($role->guard_name)) {
                $role->guard_name = config('auth.defaults.guard', 'web');
            }
        });
    }
}
