<?php

namespace MicroweberPackages\Role\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ResourcePermission extends Model
{
    use HasFactory;

    protected $table = 'resource_permissions';

    protected $fillable = [
        'resource_name',
        'action',
        'permission_name',
        'description',
        'category',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Get the roles that have this resource permission
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_resource_permissions')
            ->withPivot('is_allowed', 'conditions')
            ->withTimestamps();
    }

    /**
     * Scope to filter by resource name
     */
    public function scopeForResource($query, string $resourceName)
    {
        return $query->where('resource_name', $resourceName);
    }

    /**
     * Scope to filter by category
     */
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get all available actions for a resource
     */
    public static function getActionsForResource(string $resourceName): array
    {
        return static::where('resource_name', $resourceName)
            ->orderBy('sort_order')
            ->pluck('action')
            ->toArray();
    }

    /**
     * Get or create a resource permission
     */
    public static function getOrCreate(string $resourceName, string $action, ?string $description = null, string $category = 'general'): self
    {
        $permission = static::where('resource_name', $resourceName)
            ->where('action', $action)
            ->first();

        if (!$permission) {
            $permission = static::create([
                'resource_name' => $resourceName,
                'action' => $action,
                'permission_name' => "{$action} {$resourceName}",
                'description' => $description ?? "Permission to {$action} {$resourceName}",
                'category' => $category,
            ]);
        }

        return $permission;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($permission) {
            if (empty($permission->permission_name)) {
                $permission->permission_name = "{$permission->action} {$permission->resource_name}";
            }
        });
    }
}
