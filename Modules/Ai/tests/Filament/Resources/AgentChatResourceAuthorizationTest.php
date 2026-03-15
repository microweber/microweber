<?php

namespace Modules\Ai\Tests\Filament\Resources;

use Filament\Facades\Filament;
use Livewire\Livewire;
use MicroweberPackages\User\Models\User;
use Modules\Ai\Filament\Resources\AgentChatResource;
use Modules\Ai\Filament\Resources\AgentChatResource\Pages\ListAgentChats;
use Modules\Ai\Filament\Resources\AgentChatResource\Pages\CreateAgentChat;
use Modules\Ai\Filament\Resources\AgentChatResource\Pages\EditAgentChat;
use Modules\Ai\Filament\Resources\AgentChatResource\Pages\ViewAgentChat;
use Modules\Ai\Models\AgentChat;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Authorization tests for AgentChatResource.
 */
#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class AgentChatResourceAuthorizationTest extends TestCase
{
    use InteractsWithFilamentPanel;

    /**
     * Test that non-admin users cannot access AgentChat resource.
     */
    #[Test]
    public function it_non_admin_cannot_access_resource(): void
    {
        // Arrange: Create a non-admin user
        $this->actingAsUser();

        // Act & Assert: Non-admin should be denied access to list page
        $response = $this->get('/admin/agent-chats');
        $this->assertTrue(
            $response->isRedirect() ||
            $response->isForbidden() ||
            $response->isUnauthorized() ||
            in_array($response->status(), [302, 403, 401])
        );
    }

    /**
     * Test that non-admin users cannot create chats.
     */
    #[Test]
    public function it_non_admin_cannot_create_chat(): void
    {
        // Arrange: Create a non-admin user
        $this->actingAsUser();

        // Act & Assert: Non-admin should be denied access to create page
        $response = $this->get('/admin/agent-chats/create');
        $this->assertTrue(
            $response->isRedirect() ||
            $response->isForbidden() ||
            $response->isUnauthorized() ||
            in_array($response->status(), [302, 403, 401])
        );
    }

    /**
     * Test that non-admin users cannot edit chats.
     */
    #[Test]
    public function it_non_admin_cannot_edit_chat(): void
    {
        // Arrange: Create a non-admin user and a chat
        $user = $this->actingAsUser();
        $chat = AgentChat::factory()->create([
            'user_id' => $user->id,
        ]);

        // Act & Assert: Non-admin should be denied access to edit page
        $response = $this->get("/admin/agent-chats/{$chat->id}/edit");
        $this->assertTrue(
            $response->isRedirect() ||
            $response->isForbidden() ||
            $response->isUnauthorized() ||
            in_array($response->status(), [302, 403, 401])
        );
    }

    /**
     * Test that guests cannot access the admin panel.
     */
    #[Test]
    public function it_canaccesspanel_returns_false_for_guest(): void
    {
        // Arrange: Ensure no user is logged in
        $this->assertGuest();

        // Act: Try to access agent chats as guest
        $response = $this->get('/admin/agent-chats');

        // Assert: Should be redirected to login
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test that admin users can access the resource list.
     */
    #[Test]
    public function it_admin_can_access_resource_list(): void
    {
        // Arrange: Create an admin user
        $this->actingAsAdmin();

        // Act & Assert: Admin should be able to access list page
        Livewire::test(ListAgentChats::class)
            ->assertSuccessful();
    }

    /**
     * Test that admin users can create chats.
     */
    #[Test]
    public function it_admin_can_create_chat(): void
    {
        // Arrange: Create an admin user
        $this->actingAsAdmin();

        // Act & Assert: Admin should be able to access create page
        Livewire::test(CreateAgentChat::class)
            ->assertSuccessful();
    }

    /**
     * Test that admin users can edit any chat.
     */
    #[Test]
    public function it_admin_can_edit_any_chat(): void
    {
        // Arrange: Create an admin user and a chat owned by another user
        $this->actingAsAdmin();
        $otherUser = User::factory()->create();
        $chat = AgentChat::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        // Act & Assert: Admin should be able to access edit page
        Livewire::test(EditAgentChat::class, ['record' => $chat->id])
            ->assertSuccessful();
    }

    /**
     * Test that admin users can view any chat.
     */
    #[Test]
    public function it_admin_can_view_any_chat(): void
    {
        // Arrange: Create an admin user and a chat owned by another user
        $this->actingAsAdmin();
        $otherUser = User::factory()->create();
        $chat = AgentChat::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        // Act & Assert: Admin should be able to access view page
        Livewire::test(ViewAgentChat::class, ['record' => $chat->id])
            ->assertSuccessful();
    }

    /**
     * Test that guests are redirected from all resource pages.
     */
    #[Test]
    public function it_guest_is_redirected_from_resource_pages(): void
    {
        // Arrange: Ensure guest
        $this->assertGuest();

        // Test list page
        $response = $this->get('/admin/agent-chats');
        $response->assertRedirect('/admin/login');

        // Test create page
        $response = $this->get('/admin/agent-chats/create');
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test that canAccessPanel returns correct values for different user types.
     */
    #[Test]
    public function it_canaccesspanel_behavior(): void
    {
        // Arrange
        $panel = Filament::getPanel('admin');

        // Test admin
        $admin = User::factory()->create(['is_admin' => 1]);
        $this->assertTrue($admin->canAccessPanel($panel));

        // Test non-admin
        $user = User::factory()->create(['is_admin' => 0]);
        $this->assertFalse($user->canAccessPanel($panel));
    }
}
