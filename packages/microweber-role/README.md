# Microweber Role

Roles & permissions for Microweber, built on top of **spatie/laravel-permission**. Ships extended `Role` / `Permission` models, a fine-grained *resource permission* layer (per resource + action, with allow/deny and cached checks), database migrations, and Filament admin resources for managing roles and permissions.

## Features

- **Spatie-based roles & permissions** — the Spatie permission tables and `HasRoles` API, with Microweber-specific columns added to `roles` (`description`, `color`, `is_system`, `sort_order`).
- **System vs custom roles** — `is_system` flag with `system()` / `custom()` query scopes so built-in roles cannot be deleted.
- **Resource permissions** — a `ResourcePermission` model + `ResourcePermissionService` for allow/deny decisions keyed by `resource_name` + `action` (e.g. `products` / `manage_inventory`), with optional JSON conditions and tagged-cache memoisation.
- **Filament admin** — `RoleResource` and `PermissionResource` (nav group *System Settings*), auto-registered via the CMS `FilamentRegistry`.
- **Migrations** for the Spatie tables plus `add_custom_fields_to_roles` and `create_resource_permissions_table`.

## Installation

```bash
composer require microweber-packages/microweber-role
```

Inside Microweber the `RoleServiceProvider` is registered automatically by the framework's package discovery; it merges the `permission` config, loads the migrations, registers the Filament resources and the `role` view namespace. In a standalone Laravel + Filament app register it manually and migrate:

```php
// bootstrap/providers.php
MicroweberPackages\Role\RoleServiceProvider::class,
```

```bash
php artisan migrate
```

## Usage

### Roles & permissions (Spatie API)

```php
use MicroweberPackages\Role\Models\Role;
use MicroweberPackages\Role\Models\Permission;

$editor = Role::create([
    'name'        => 'editor',
    'description' => 'Can manage content',
    'color'       => '#0d6efd',
    'is_system'   => false,
]);

Permission::firstOrCreate(['name' => 'edit content']);
$editor->givePermissionTo('edit content');

$user->assignRole('editor');
$user->can('edit content'); // true

// Query scopes
Role::system()->get(); // built-in roles (is_system = true)
Role::custom()->get(); // user-defined roles
$editor->isSystem();   // false
$editor->users_count;  // accessor: number of users with this role
```

### Resource permissions

The `ResourcePermissionService` (bound as a singleton) grants/denies actions on named resources and caches the result:

```php
use MicroweberPackages\Role\Services\ResourcePermissionService;
use MicroweberPackages\Role\Models\Role;

$service = app(ResourcePermissionService::class);
$role = Role::findByName('editor');

// Grant / deny / revoke an action on a resource (optional JSON conditions)
$service->grantPermission($role, 'products', 'manage_inventory', ['store_id' => 5]);
$service->denyPermission($role, 'orders', 'refund');
$service->revokePermission($role, 'products', 'manage_inventory');

// Cached allow/deny check (1h TTL, tagged cache)
if ($service->can($role, 'products', 'edit')) {
    // ...
}

// Introspection
$service->getRoleResourcePermissions($role); // grouped by resource_name
$service->getResourcesByCategory();          // grouped by category
$service->registerDefaultPermissions();      // seed the built-in catalog
$service->clearAllCache();                   // sweep only this service's tagged entries
```

Resource permission rows are created on demand:

```php
use MicroweberPackages\Role\Models\ResourcePermission;

$perm = ResourcePermission::getOrCreate('media', 'upload', 'Upload files', 'Media');
ResourcePermission::getActionsForResource('media'); // ['view', 'upload', ...]
```

### Filament admin

When the CMS `FilamentRegistry` is available, `RoleResource` and `PermissionResource` are registered automatically. In a standalone panel:

```php
use MicroweberPackages\Role\Filament\Resources\RoleResource;
use MicroweberPackages\Role\Filament\Resources\PermissionResource;

$panel->resources([RoleResource::class, PermissionResource::class]);
// or:
$panel->resources(MicroweberPackages\Role\RoleServiceProvider::getFilamentResources());
```

## Configuration

Config is merged under the `permission` key (standard spatie/laravel-permission config from `src/config/permission.php`). The model bindings point at the Microweber subclasses:

```php
'models' => [
    'permission' => MicroweberPackages\Role\Models\Permission::class,
    'role'       => MicroweberPackages\Role\Models\Role::class,
],
```

Override table names, guards, team support, and cache settings there as with any spatie/laravel-permission install.

## Key classes

- `MicroweberPackages\Role\RoleServiceProvider`
- `MicroweberPackages\Role\Models\Role` — extends `Spatie\Permission\Models\Role`
- `MicroweberPackages\Role\Models\Permission` — extends `Spatie\Permission\Models\Permission`
- `MicroweberPackages\Role\Models\ResourcePermission`
- `MicroweberPackages\Role\Services\ResourcePermissionService`
- `MicroweberPackages\Role\Filament\Resources\RoleResource` / `PermissionResource`

## Authors

Part of [Microweber](https://microweber.com), built by:

- Bozhidar Slaveykov
- Peter Ivanov
- Petko Yovchevski
- Nikolay Rachkov

## License

MIT
