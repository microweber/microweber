<?php

namespace Tests\Feature\Role;

use MicroweberPackages\Role\Models\ResourcePermission;
use MicroweberPackages\Role\Models\Role;
use MicroweberPackages\Role\Services\ResourcePermissionService;
use Tests\TestCase;

class ResourcePermissionTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate', [
            '--path' => base_path('src/MicroweberPackages/Role/database/migrations'),
            '--realpath' => true,
            '--force' => true,
        ]);
        // Clean up data from previous test runs to avoid unique constraint violations
        \Illuminate\Support\Facades\DB::table('role_resource_permissions')->delete();
        \Illuminate\Support\Facades\DB::table('resource_permissions')->delete();
    }

    /** @test */
    public function it_can_create_resource_permission()
    {
        $permission = ResourcePermission::create([
            'resource_name' => 'products',
            'action' => 'view',
            'permission_name' => 'view products',
            'description' => 'View product catalog',
            'category' => 'E-commerce',
            'sort_order' => 10,
        ]);

        $this->assertDatabaseHas('resource_permissions', [
            'resource_name' => 'products',
            'action' => 'view',
            'permission_name' => 'view products',
        ]);

        $this->assertEquals('E-commerce', $permission->category);
        $this->assertEquals(10, $permission->sort_order);
    }

    /** @test */
    public function it_resource_permission_auto_generates_permission_name()
    {
        $permission = ResourcePermission::create([
            'resource_name' => 'users',
            'action' => 'edit',
        ]);

        $this->assertEquals('edit users', $permission->permission_name);
    }

    /** @test */
    public function it_resource_permission_prevents_duplicate_resource_action_combination()
    {
        ResourcePermission::create([
            'resource_name' => 'content',
            'action' => 'publish',
        ]);

        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);

        ResourcePermission::create([
            'resource_name' => 'content',
            'action' => 'publish',
        ]);
    }

    /** @test */
    public function it_can_scope_by_resource_name()
    {
        ResourcePermission::create(['resource_name' => 'products', 'action' => 'view']);
        ResourcePermission::create(['resource_name' => 'products', 'action' => 'edit']);
        ResourcePermission::create(['resource_name' => 'orders', 'action' => 'view']);

        $productPermissions = ResourcePermission::forResource('products')->get();

        $this->assertEquals(2, $productPermissions->count());
        $this->assertTrue($productPermissions->every(fn ($p) => $p->resource_name === 'products'));
    }

    /** @test */
    public function it_can_scope_by_category()
    {
        ResourcePermission::create([
            'resource_name' => 'users',
            'action' => 'view',
            'category' => 'User Management',
        ]);
        ResourcePermission::create([
            'resource_name' => 'roles',
            'action' => 'edit',
            'category' => 'User Management',
        ]);
        ResourcePermission::create([
            'resource_name' => 'products',
            'action' => 'view',
            'category' => 'E-commerce',
        ]);

        $userMgmtPermissions = ResourcePermission::inCategory('User Management')->get();

        $this->assertEquals(2, $userMgmtPermissions->count());
    }

    /** @test */
    public function it_getOrCreate_creates_new_permission_if_not_exists()
    {
        $permission = ResourcePermission::getOrCreate('media', 'upload', 'Upload media files', 'Media');

        $this->assertDatabaseHas('resource_permissions', [
            'resource_name' => 'media',
            'action' => 'upload',
        ]);

        $this->assertEquals('upload media', $permission->permission_name);
    }

    /** @test */
    public function it_getOrCreate_returns_existing_permission_if_exists()
    {
        $original = ResourcePermission::create([
            'resource_name' => 'orders',
            'action' => 'process',
            'permission_name' => 'process orders',
            'category' => 'Sales',
        ]);

        $retrieved = ResourcePermission::getOrCreate('orders', 'process');

        $this->assertEquals($original->id, $retrieved->id);
    }

    /** @test */
    public function it_can_get_actions_for_resource()
    {
        ResourcePermission::create(['resource_name' => 'settings', 'action' => 'view', 'sort_order' => 2]);
        ResourcePermission::create(['resource_name' => 'settings', 'action' => 'edit', 'sort_order' => 1]);
        ResourcePermission::create(['resource_name' => 'settings', 'action' => 'delete', 'sort_order' => 3]);

        $actions = ResourcePermission::getActionsForResource('settings');

        $this->assertEquals(['edit', 'view', 'delete'], $actions);
    }

    /** @test */
    public function it_service_can_grant_resource_permission_to_role()
    {
        $service = app(ResourcePermissionService::class);
        $role = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web'], ['guard_name' => 'web']);

        $service->grantPermission($role, 'products', 'edit');

        $this->assertDatabaseHas('role_resource_permissions', [
            'role_id' => $role->id,
        ]);

        $role->refresh();
        $resourcePermission = $role->resourcePermissions()->first();
        $this->assertEquals('products', $resourcePermission->resource_name);
        $this->assertEquals('edit', $resourcePermission->action);
        $this->assertEquals(1, (int) $resourcePermission->pivot->is_allowed);
    }

    /** @test */
    public function it_service_can_revoke_resource_permission_from_role()
    {
        $service = app(ResourcePermissionService::class);
        $role = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web'], ['guard_name' => 'web']);

        $service->grantPermission($role, 'users', 'delete');
        $service->revokePermission($role, 'users', 'delete');

        $role->refresh();
        $this->assertEquals(0, $role->resourcePermissions()->count());
    }

    /** @test */
    public function it_service_can_deny_resource_permission_to_role()
    {
        $service = app(ResourcePermissionService::class);
        $role = Role::firstOrCreate(['name' => 'Limited User', 'guard_name' => 'web'], ['guard_name' => 'web']);

        $service->denyPermission($role, 'settings', 'edit');

        $role->refresh();
        $resourcePermission = $role->resourcePermissions()->first();
        $this->assertEquals(0, (int) $resourcePermission->pivot->is_allowed);
    }

    /** @test */
    public function it_service_checks_if_role_can_perform_action_on_resource()
    {
        $service = app(ResourcePermissionService::class);
        $role = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'web'], ['guard_name' => 'web']);

        $service->grantPermission($role, 'content', 'edit');

        $this->assertTrue($service->can($role, 'content', 'edit'));
        $this->assertFalse($service->can($role, 'content', 'delete'));
    }

    /** @test */
    public function it_service_returns_false_for_nonexistent_permission()
    {
        $service = app(ResourcePermissionService::class);
        $role = Role::firstOrCreate(['name' => 'Test Role', 'guard_name' => 'web'], ['guard_name' => 'web']);

        $this->assertFalse($service->can($role, 'nonexistent', 'action'));
    }

    /** @test */
    public function it_service_can_get_all_resource_permissions_for_role()
    {
        $service = app(ResourcePermissionService::class);
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web'], ['guard_name' => 'web']);

        $service->grantPermission($role, 'users', 'view');
        $service->grantPermission($role, 'products', 'edit');
        $service->denyPermission($role, 'settings', 'delete');

        $permissions = $service->getRoleResourcePermissions($role);

        $this->assertArrayHasKey('users', $permissions);
        $this->assertArrayHasKey('products', $permissions);
        $this->assertArrayHasKey('settings', $permissions);

        $this->assertTrue($permissions['users']['view']['allowed']);
        $this->assertTrue($permissions['products']['edit']['allowed']);
        $this->assertFalse($permissions['settings']['delete']['allowed']);
    }

    /** @test */
    public function it_service_can_get_resources_grouped_by_category()
    {
        ResourcePermission::create(['resource_name' => 'users', 'action' => 'view', 'category' => 'User Management']);
        ResourcePermission::create(['resource_name' => 'roles', 'action' => 'view', 'category' => 'User Management']);
        ResourcePermission::create(['resource_name' => 'products', 'action' => 'view', 'category' => 'E-commerce']);

        $service = app(ResourcePermissionService::class);
        $grouped = $service->getResourcesByCategory();

        $this->assertArrayHasKey('User Management', $grouped);
        $this->assertArrayHasKey('E-commerce', $grouped);
        $this->assertCount(2, $grouped['User Management']);
        $this->assertCount(1, $grouped['E-commerce']);
    }

    /** @test */
    public function it_service_can_register_default_permissions()
    {
        $service = app(ResourcePermissionService::class);
        $service->registerDefaultPermissions();

        $this->assertDatabaseHas('resource_permissions', [
            'resource_name' => 'users',
            'action' => 'view',
        ]);

        $this->assertDatabaseHas('resource_permissions', [
            'resource_name' => 'content',
            'action' => 'create',
        ]);

        $this->assertDatabaseHas('resource_permissions', [
            'resource_name' => 'products',
            'action' => 'manage_inventory',
        ]);

        // Check that products resource has all expected actions
        $productActions = ResourcePermission::forResource('products')->pluck('action')->toArray();
        $this->assertContains('view', $productActions);
        $this->assertContains('create', $productActions);
        $this->assertContains('edit', $productActions);
        $this->assertContains('delete', $productActions);
        $this->assertContains('manage_inventory', $productActions);
        $this->assertContains('manage_pricing', $productActions);
    }

    /** @test */
    public function it_resource_permission_belongs_to_many_roles()
    {
        $permission = ResourcePermission::create([
            'resource_name' => 'reports',
            'action' => 'view',
        ]);

        $role1 = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web'], ['guard_name' => 'web']);
        $role2 = Role::firstOrCreate(['name' => 'Analyst', 'guard_name' => 'web'], ['guard_name' => 'web']);

        $role1->resourcePermissions()->attach($permission->id, ['is_allowed' => true]);
        $role2->resourcePermissions()->attach($permission->id, ['is_allowed' => true]);

        $this->assertEquals(2, $permission->roles()->count());
    }

    /** @test */
    public function it_can_store_conditions_in_pivot()
    {
        $service = app(ResourcePermissionService::class);
        $role = Role::firstOrCreate(['name' => 'Regional Manager', 'guard_name' => 'web'], ['guard_name' => 'web']);

        $conditions = ['region' => 'north_america', 'department' => 'sales'];
        $service->grantPermission($role, 'orders', 'view', $conditions);

        $role->refresh();
        $pivot = $role->resourcePermissions()->first()->pivot;
        $storedConditions = json_decode($pivot->conditions, true);

        $this->assertEquals($conditions, $storedConditions);
    }
}
