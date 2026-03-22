<?php

namespace MicroweberPackages\Role\Services;

use MicroweberPackages\Role\Models\ResourcePermission;
use MicroweberPackages\Role\Models\Role;
use Illuminate\Support\Facades\Cache;

class ResourcePermissionService
{
    /**
     * Check if a role has permission to perform an action on a resource
     */
    public function can(Role $role, string $resourceName, string $action): bool
    {
        $cacheKey = "role_{$role->id}_resource_{$resourceName}_{$action}";
        
        return Cache::remember($cacheKey, 3600, function () use ($role, $resourceName, $action) {
            // First check if role has the specific permission
            $permission = ResourcePermission::where('resource_name', $resourceName)
                ->where('action', $action)
                ->first();

            if (!$permission) {
                return false;
            }

            // Check role's resource permission pivot
            $rolePermission = $role->resourcePermissions()
                ->where('resource_permission_id', $permission->id)
                ->first();

            if ($rolePermission) {
                return $rolePermission->pivot->is_allowed;
            }

            // If no explicit resource permission, check general permissions
            return $role->hasPermissionTo("{$action} {$resourceName}");
        });
    }

    /**
     * Grant permission to a role for a resource action
     */
    public function grantPermission(Role $role, string $resourceName, string $action, ?array $conditions = null): void
    {
        $permission = ResourcePermission::getOrCreate($resourceName, $action);

        $role->resourcePermissions()->syncWithoutDetaching([
            $permission->id => [
                'is_allowed' => true,
                'conditions' => $conditions ? json_encode($conditions) : null,
            ]
        ]);

        $this->clearCache($role, $resourceName, $action);
    }

    /**
     * Revoke permission from a role for a resource action
     */
    public function revokePermission(Role $role, string $resourceName, string $action): void
    {
        $permission = ResourcePermission::where('resource_name', $resourceName)
            ->where('action', $action)
            ->first();

        if ($permission) {
            $role->resourcePermissions()->detach($permission->id);
            $this->clearCache($role, $resourceName, $action);
        }
    }

    /**
     * Deny permission explicitly for a role
     */
    public function denyPermission(Role $role, string $resourceName, string $action): void
    {
        $permission = ResourcePermission::getOrCreate($resourceName, $action);

        $role->resourcePermissions()->syncWithoutDetaching([
            $permission->id => [
                'is_allowed' => false,
                'conditions' => null,
            ]
        ]);

        $this->clearCache($role, $resourceName, $action);
    }

    /**
     * Get all resource permissions for a role
     */
    public function getRoleResourcePermissions(Role $role): array
    {
        return $role->resourcePermissions()
            ->withPivot('is_allowed', 'conditions')
            ->get()
            ->groupBy('resource_name')
            ->map(function ($permissions) {
                return $permissions->mapWithKeys(function ($permission) {
                    return [
                        $permission->action => [
                            'allowed' => (bool) $permission->pivot->is_allowed,
                            'conditions' => $permission->pivot->conditions ? json_decode($permission->pivot->conditions, true) : null,
                        ]
                    ];
                });
            })
            ->toArray();
    }

    /**
     * Get resources grouped by category
     */
    public function getResourcesByCategory(): array
    {
        return ResourcePermission::orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category')
            ->toArray();
    }

    /**
     * Register default resource permissions
     */
    public function registerDefaultPermissions(): void
    {
        $defaults = [
            'users' => [
                'category' => 'User Management',
                'actions' => [
                    'view' => 'View user accounts',
                    'create' => 'Create new users',
                    'edit' => 'Edit existing users',
                    'delete' => 'Delete users',
                    'impersonate' => 'Impersonate users',
                ],
            ],
            'content' => [
                'category' => 'Content Management',
                'actions' => [
                    'view' => 'View pages and posts',
                    'create' => 'Create pages and posts',
                    'edit' => 'Edit pages and posts',
                    'delete' => 'Delete pages and posts',
                    'publish' => 'Publish content',
                    'unpublish' => 'Unpublish content',
                ],
            ],
            'products' => [
                'category' => 'E-commerce',
                'actions' => [
                    'view' => 'View products',
                    'create' => 'Create products',
                    'edit' => 'Edit products',
                    'delete' => 'Delete products',
                    'manage_inventory' => 'Manage inventory',
                    'manage_pricing' => 'Manage pricing',
                ],
            ],
            'orders' => [
                'category' => 'E-commerce',
                'actions' => [
                    'view' => 'View orders',
                    'edit' => 'Edit orders',
                    'process' => 'Process orders',
                    'refund' => 'Process refunds',
                    'export' => 'Export orders',
                ],
            ],
            'settings' => [
                'category' => 'System',
                'actions' => [
                    'view' => 'View settings',
                    'edit' => 'Edit settings',
                    'manage_modules' => 'Manage modules',
                    'manage_templates' => 'Manage templates',
                ],
            ],
            'media' => [
                'category' => 'Media',
                'actions' => [
                    'view' => 'View media library',
                    'upload' => 'Upload files',
                    'edit' => 'Edit media metadata',
                    'delete' => 'Delete files',
                ],
            ],
        ];

        foreach ($defaults as $resourceName => $config) {
            $sortOrder = 0;
            foreach ($config['actions'] as $action => $description) {
                ResourcePermission::getOrCreate(
                    $resourceName,
                    $action,
                    $description,
                    $config['category']
                );
                $sortOrder++;
            }
        }
    }

    /**
     * Clear permission cache for a role
     */
    protected function clearCache(Role $role, string $resourceName, string $action): void
    {
        Cache::forget("role_{$role->id}_resource_{$resourceName}_{$action}");
    }

    /**
     * Clear all resource permission caches
     */
    public function clearAllCache(): void
    {
        Cache::flush();
    }
}
