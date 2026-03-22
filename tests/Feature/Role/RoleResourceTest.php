<?php

namespace Tests\Feature\Role;

use Illuminate\Foundation\Testing\RefreshDatabase;
use MicroweberPackages\Role\Models\Role;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;

class RoleResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Run only specific migrations for role testing
        \Illuminate\Support\Facades\Schema::create('roles', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name')->default('web');
            $table->text('description')->nullable();
            $table->string('color', 50)->nullable();
            $table->boolean('is_system')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        \Illuminate\Support\Facades\Schema::create('permissions', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        \Illuminate\Support\Facades\Schema::create('role_has_permissions', function ($table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->primary(['permission_id', 'role_id']);
        });

        \Illuminate\Support\Facades\Schema::create('model_has_roles', function ($table) {
            $table->unsignedBigInteger('role_id');
            $table->morphs('model');
        });

        \Illuminate\Support\Facades\Schema::create('model_has_permissions', function ($table) {
            $table->unsignedBigInteger('permission_id');
            $table->morphs('model');
        });
    }

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

    /** @test */
    public function it_admin_can_view_roles_list()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        Role::create(['name' => 'Editor', 'guard_name' => 'web']);
        Role::create(['name' => 'Manager', 'guard_name' => 'web']);

        $response = $this->get('/admin/roles');
        $response->assertStatus(200);
    }

    /** @test */
    public function it_non_admin_cannot_view_roles_list()
    {
        $user = $this->createNonAdminUser();
        $this->actingAs($user);

        $response = $this->get('/admin/roles');
        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_create_custom_role()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $response = $this->postJson('/admin/roles', [
            'name' => 'Content Manager',
            'description' => 'Manages content only',
            'color' => '#3b82f6',
            'is_system' => false,
            'sort_order' => 10,
            'permission' => [],
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('roles', [
            'name' => 'Content Manager',
            'description' => 'Manages content only',
            'color' => '#3b82f6',
            'is_system' => false,
            'sort_order' => 10,
        ]);
    }

    /** @test */
    public function it_cannot_create_role_with_duplicate_name()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        Role::create(['name' => 'Editor', 'guard_name' => 'web']);

        $response = $this->postJson('/admin/roles', [
            'name' => 'Editor',
            'description' => 'Duplicate role',
            'permission' => [],
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_can_edit_custom_role()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $role = Role::create([
            'name' => 'Old Name',
            'description' => 'Old description',
            'color' => '#000000',
            'guard_name' => 'web',
        ]);

        $response = $this->putJson("/admin/roles/{$role->id}", [
            'name' => 'Updated Name',
            'description' => 'Updated description',
            'color' => '#ffffff',
            'is_system' => false,
            'sort_order' => 20,
            'permission' => [],
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'Updated Name',
            'description' => 'Updated description',
            'color' => '#ffffff',
            'sort_order' => 20,
        ]);
    }

    /** @test */
    public function it_cannot_delete_system_role()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $role = Role::create([
            'name' => 'System Admin',
            'is_system' => true,
            'guard_name' => 'web',
        ]);

        $response = $this->deleteJson("/admin/roles/{$role->id}");

        // Should fail or redirect back
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'System Admin',
        ]);
    }

    /** @test */
    public function it_can_delete_custom_role()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $role = Role::create([
            'name' => 'Custom Role',
            'is_system' => false,
            'guard_name' => 'web',
        ]);

        $response = $this->deleteJson("/admin/roles/{$role->id}");

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
        ]);
    }

    /** @test */
    public function it_role_has_custom_fields()
    {
        $role = Role::create([
            'name' => 'Test Role',
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

    /** @test */
    public function it_role_scope_filters_system_roles()
    {
        Role::create(['name' => 'System Role 1', 'is_system' => true, 'guard_name' => 'web']);
        Role::create(['name' => 'Custom Role 1', 'is_system' => false, 'guard_name' => 'web']);
        Role::create(['name' => 'System Role 2', 'is_system' => true, 'guard_name' => 'web']);

        $systemRoles = Role::system()->get();
        $this->assertEquals(2, $systemRoles->count());
        $this->assertTrue($systemRoles->first()->is_system);

        $customRoles = Role::custom()->get();
        $this->assertEquals(1, $customRoles->count());
        $this->assertFalse($customRoles->first()->is_system);
    }

    /** @test */
    public function it_role_isSystem_method_works()
    {
        $systemRole = Role::create(['name' => 'System', 'is_system' => true, 'guard_name' => 'web']);
        $customRole = Role::create(['name' => 'Custom', 'is_system' => false, 'guard_name' => 'web']);

        $this->assertTrue($systemRole->isSystem());
        $this->assertFalse($customRole->isSystem());
    }

    /** @test */
    public function it_role_auto_sets_guard_name()
    {
        $role = Role::create(['name' => 'Test Role']);
        $this->assertNotNull($role->guard_name);
    }

    /** @test */
    public function it_can_clone_role()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $role = Role::create(['name' => 'Original Role', 'guard_name' => 'web']);

        $response = $this->postJson('/admin/roles/clone', [
            'id' => $role->id,
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'Original Role (duplicate)',
        ]);
    }
}
