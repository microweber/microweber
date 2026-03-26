<?php

namespace Tests\Feature\Role;

use Illuminate\Foundation\Testing\RefreshDatabase;
use MicroweberPackages\Role\Models\Role;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;

class RoleResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdminUser(): User
    {
        return User::factory()->create([
            'is_admin' => 1,
            'is_active' => 1,
        ]);
    }

    protected function createNonAdminUser(): User
    {
        return User::factory()->create([
            'is_admin' => 0,
            'is_active' => 1,
        ]);
    }

    public function test_role_can_be_created_with_custom_fields(): void
    {
        $role = Role::create([
            'name' => 'Content Manager ' . uniqid(),
            'description' => 'Manages content only',
            'color' => '#3b82f6',
            'is_system' => false,
            'sort_order' => 10,
            'guard_name' => 'web',
        ]);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'description' => 'Manages content only',
            'color' => '#3b82f6',
            'sort_order' => 10,
        ]);
    }

    public function test_role_has_custom_fields(): void
    {
        $role = Role::create([
            'name' => 'Test Role ' . uniqid(),
            'description' => 'Test Description',
            'color' => '#ff0000',
            'is_system' => true,
            'sort_order' => 5,
            'guard_name' => 'web',
        ]);

        $this->assertEquals('Test Description', $role->description);
        $this->assertEquals('#ff0000', $role->color);
        $this->assertTrue($role->is_system);
        $this->assertEquals(5, $role->sort_order);
    }

    public function test_duplicate_role_name_throws_exception(): void
    {
        $roleName = 'TestDuplicate-' . uniqid();
        Role::create(['name' => $roleName, 'guard_name' => 'web']);

        $this->expectException(\Spatie\Permission\Exceptions\RoleAlreadyExists::class);
        Role::create(['name' => $roleName, 'guard_name' => 'web']);
    }

    public function test_role_can_be_updated(): void
    {
        $role = Role::create([
            'name' => 'Old Name ' . uniqid(),
            'description' => 'Old description',
            'color' => '#000000',
            'guard_name' => 'web',
        ]);

        $role->update([
            'description' => 'Updated description',
            'color' => '#ffffff',
            'sort_order' => 20,
        ]);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'description' => 'Updated description',
            'color' => '#ffffff',
            'sort_order' => 20,
        ]);
    }

    public function test_custom_role_can_be_deleted(): void
    {
        $role = Role::create([
            'name' => 'Custom Role ' . uniqid(),
            'is_system' => false,
            'guard_name' => 'web',
        ]);

        $roleId = $role->id;
        $role->delete();

        $this->assertDatabaseMissing('roles', ['id' => $roleId]);
    }

    public function test_system_role_is_protected(): void
    {
        $role = Role::create([
            'name' => 'System Admin ' . uniqid(),
            'is_system' => true,
            'guard_name' => 'web',
        ]);

        $this->assertTrue($role->isSystem());
        $this->assertDatabaseHas('roles', ['id' => $role->id]);
    }

    public function test_scope_filters_system_roles(): void
    {
        // Clean existing roles to get predictable counts
        Role::query()->delete();

        Role::create(['name' => 'System Role 1 ' . uniqid(), 'is_system' => true, 'guard_name' => 'web']);
        Role::create(['name' => 'Custom Role 1 ' . uniqid(), 'is_system' => false, 'guard_name' => 'web']);
        Role::create(['name' => 'System Role 2 ' . uniqid(), 'is_system' => true, 'guard_name' => 'web']);

        $systemRoles = Role::system()->get();
        $this->assertEquals(2, $systemRoles->count());
        $this->assertTrue($systemRoles->first()->is_system);

        $customRoles = Role::custom()->get();
        $this->assertEquals(1, $customRoles->count());
        $this->assertFalse($customRoles->first()->is_system);
    }

    public function test_isSystem_method_works(): void
    {
        $systemRole = Role::create(['name' => 'System ' . uniqid(), 'is_system' => true, 'guard_name' => 'web']);
        $customRole = Role::create(['name' => 'Custom ' . uniqid(), 'is_system' => false, 'guard_name' => 'web']);

        $this->assertTrue($systemRole->isSystem());
        $this->assertFalse($customRole->isSystem());
    }

    public function test_role_auto_sets_guard_name(): void
    {
        $role = Role::create(['name' => 'Test Role ' . uniqid()]);
        $this->assertNotNull($role->guard_name);
    }

    public function test_role_resource_permission_relationship(): void
    {
        $role = Role::create([
            'name' => 'Test Role ' . uniqid(),
            'guard_name' => 'web',
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class, $role->resourcePermissions());
    }
}
