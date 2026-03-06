<?php

namespace Tests\Feature\Filament;

use Filament\Facades\Filament;
use MicroweberPackages\User\Filament\Resources\UsersResource;
use MicroweberPackages\User\Filament\Resources\UsersResource\Pages\ListUsers;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;

/**
 * Authorization tests for UsersResource.
 */
class UsersResourceAuthorizationTest extends AuthorizationTest
{
    protected function getResourceClass(): string
    {
        return UsersResource::class;
    }

    protected function getListPageClass(): string
    {
        return ListUsers::class;
    }

    protected function createTestRecord(array $attributes = [])
    {
        return User::factory()->create($attributes);
    }

    /**
     * Test that regular users cannot list other users.
     */
    #[Test]
    public function it_non_admin_cannot_list_users(): void
    {
        // Arrange: Create non-admin user
        $this->actingAsUser();

        // Act: Try to access users list
        $response = $this->get('/admin/users');

        // Assert: Should be denied
        $this->assertTrue(
            $response->isRedirect() ||
            $response->isForbidden() ||
            $response->isUnauthorized() ||
            in_array($response->status(), [302, 403, 401])
        );
    }

    /**
     * Test that regular users cannot edit other users.
     */
    #[Test]
    public function it_non_admin_cannot_edit_other_users(): void
    {
        // Arrange: Create non-admin user and another user
        $user = $this->actingAsUser();
        $otherUser = User::factory()->create();

        // Act: Try to edit other user
        $response = $this->get("/admin/users/{$otherUser->id}/edit");

        // Assert: Should be denied
        $this->assertTrue(
            $response->isRedirect() ||
            $response->isForbidden() ||
            $response->isUnauthorized() ||
            in_array($response->status(), [302, 403, 401])
        );
    }

    /**
     * Test that admin users can list all users.
     */
    #[Test]
    public function it_admin_can_list_all_users(): void
    {
        // Arrange: Create admin and some users
        $this->actingAsAdmin();
        User::factory()->count(3)->create();

        // Act & Assert: Admin should see users list
        $response = $this->get('/admin/users');
        $this->assertTrue(
            $response->isSuccessful() ||
            $response->status() === 200
        );
    }

    /**
     * Test that admin users can edit any user.
     */
    #[Test]
    public function it_admin_can_edit_any_user(): void
    {
        // Arrange: Create admin and another user
        $this->actingAsAdmin();
        $otherUser = User::factory()->create();

        // Act & Assert: Admin should be able to access edit page
        $response = $this->get("/admin/users/{$otherUser->id}/edit");
        $this->assertTrue(
            $response->isSuccessful() ||
            $response->isRedirect() ||
            in_array($response->status(), [200, 302])
        );
    }

    /**
     * Test that guests cannot access user management.
     */
    #[Test]
    public function it_guest_cannot_access_user_management(): void
    {
        // Arrange: Ensure guest
        $this->assertGuest();

        // Act: Try to access users
        $response = $this->get('/admin/users');

        // Assert: Should redirect to login
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test that canAccessPanel returns correct values.
     */
    #[Test]
    public function it_user_canaccesspanel_behavior(): void
    {
        // Arrange
        $panel = Filament::getPanel('admin');

        // Test admin
        $admin = User::factory()->create(['is_admin' => 1]);
        $this->assertTrue($admin->canAccessPanel($panel));

        // Test non-admin
        $user = User::factory()->create(['is_admin' => 0]);
        $this->assertFalse($user->canAccessPanel($panel));

        // Test guest (null user)
        $guest = new User();
        $guest->is_admin = 0;
        $this->assertFalse($guest->canAccessPanel($panel));
    }
}
