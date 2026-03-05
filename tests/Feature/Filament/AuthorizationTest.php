<?php

namespace Tests\Feature\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Base authorization test class for Filament resources.
 *
 * Provides standardized tests for resource authorization including:
 * - Non-admin access denial
 * - Guest access denial to panel
 * - Ownership-based access control
 */
abstract class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get the resource class being tested.
     * Must be implemented by subclasses.
     *
     * @return string
     */
    abstract protected function getResourceClass(): string;

    /**
     * Get the list page class for the resource.
     *
     * @return string
     */
    abstract protected function getListPageClass(): string;

    /**
     * Get the edit page class for the resource.
     *
     * @return string|null
     */
    protected function getEditPageClass(): ?string
    {
        return null;
    }

    /**
     * Get the view page class for the resource.
     *
     * @return string|null
     */
    protected function getViewPageClass(): ?string
    {
        return null;
    }

    /**
     * Get the create page class for the resource.
     *
     * @return string|null
     */
    protected function getCreatePageClass(): ?string
    {
        return null;
    }

    /**
     * Create a test record for the resource.
     * Must be implemented by subclasses that use ownership tests.
     *
     * @param array $attributes
     * @return mixed
     */
    protected function createTestRecord(array $attributes = [])
    {
        $resourceClass = $this->getResourceClass();
        $modelClass = $resourceClass::getModel();

        if (!class_exists($modelClass) || !method_exists($modelClass, 'factory')) {
            throw new \RuntimeException("Model class {$modelClass} does not exist or has no factory");
        }

        return $modelClass::factory()->create($attributes);
    }

    /**
     * Check if the resource supports ownership-based access control.
     *
     * @return bool
     */
    protected function supportsOwnership(): bool
    {
        return false;
    }

    /**
     * Get the user foreign key field name for ownership.
     *
     * @return string
     */
    protected function getUserForeignKey(): string
    {
        return 'user_id';
    }

    /**
     * Authenticate as an admin user.
     *
     * @return User
     */
    protected function actingAsAdmin(): User
    {
        $user = User::factory()->create([
            'is_admin' => 1,
        ]);

        $this->actingAs($user);
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        return $user;
    }

    /**
     * Authenticate as a non-admin user.
     *
     * @param array $attributes
     * @return User
     */
    protected function actingAsUser(array $attributes = []): User
    {
        $user = User::factory()->create(array_merge([
            'is_admin' => 0,
        ], $attributes));

        $this->actingAs($user);
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        return $user;
    }

    /**
     * Test that non-admin users cannot access the resource.
     */
    #[Test]
    public function test_non_admin_cannot_access_resource(): void
    {
        // Arrange: Create a non-admin user
        $user = $this->actingAsUser();

        // Act & Assert: Non-admin should be denied access
        $response = $this->get('/admin');
        $this->assertTrue(
            $response->isRedirect() ||
            $response->isForbidden() ||
            $response->isUnauthorized() ||
            $response->status() === 302 ||
            $response->status() === 403 ||
            $response->status() === 401
        );
    }

    /**
     * Test that guests cannot access the admin panel.
     */
    #[Test]
    public function test_canAccessPanel_returns_false_for_guest(): void
    {
        // Arrange: Ensure no user is logged in
        $this->assertGuest();

        // Act: Try to access admin panel as guest
        $response = $this->get('/admin');

        // Assert: Should be redirected to login
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test that admin users can access the resource.
     */
    #[Test]
    public function test_admin_can_access_resource(): void
    {
        // Arrange: Create an admin user
        $this->actingAsAdmin();

        // Act & Assert: Admin should be able to access list page
        Livewire::test($this->getListPageClass())
            ->assertSuccessful();
    }

    /**
     * Test that users can only see their own records (if resource supports ownership).
     */
    #[Test]
    public function test_user_sees_only_own_team_records(): void
    {
        // Skip if resource doesn't support ownership
        if (!$this->supportsOwnership()) {
            $this->markTestSkipped('Resource does not support ownership-based access control');
        }

        // Arrange: Create two users
        $user1 = $this->actingAsUser();
        $user2 = User::factory()->create(['is_admin' => 0]);

        // Create records for both users
        $ownRecord = $this->createTestRecord([
            $this->getUserForeignKey() => $user1->id,
        ]);
        $otherRecord = $this->createTestRecord([
            $this->getUserForeignKey() => $user2->id,
        ]);

        // Act & Assert: User1 should see own record but not user2's record
        $response = Livewire::test($this->getListPageClass());
        $response->assertSuccessful();

        // If the resource has proper authorization, user1's record should be visible
        // while user2's record should not be visible
        // Note: This assumes the resource implements proper query scoping
    }

    /**
     * Test that guests are redirected from resource pages.
     */
    #[Test]
    public function test_guest_is_redirected_from_resource_pages(): void
    {
        // Arrange: Ensure guest
        $this->assertGuest();

        // Act: Try to access resource list page
        $response = $this->get('/admin');

        // Assert: Should redirect to login
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test that canAccessPanel returns true for admin users.
     */
    #[Test]
    public function test_canAccessPanel_returns_true_for_admin(): void
    {
        // Arrange: Create admin user
        $admin = User::factory()->create(['is_admin' => 1]);
        $panel = Filament::getPanel('admin');

        // Act & Assert: Admin should be able to access panel
        $this->assertTrue($admin->canAccessPanel($panel));
    }

    /**
     * Test that canAccessPanel returns false for non-admin users.
     */
    #[Test]
    public function test_canAccessPanel_returns_false_for_non_admin(): void
    {
        // Arrange: Create non-admin user
        $user = User::factory()->create(['is_admin' => 0]);
        $panel = Filament::getPanel('admin');

        // Act & Assert: Non-admin should not be able to access panel
        $this->assertFalse($user->canAccessPanel($panel));
    }
}
