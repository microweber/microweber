<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Laravel\Passport\Passport;
use MicroweberPackages\User\Models\User;
use Modules\Content\Models\Content;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Verifies Passport scope enforcement on `/api/module/*` write routes.
 *
 * Every write route is wired through `scope:<module>:write`, so a
 * personal token that was narrowed to a different module must not be
 * able to write elsewhere. The wildcard `*` scope (which new tokens
 * get by default when the user leaves the scope picker empty)
 * continues to work everywhere.
 */
final class ModuleApiScopesTest extends TestCase
{
    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'email' => 'scopes-' . uniqid() . '@example.com',
            'is_admin' => 1,
            'is_active' => 1,
        ]);
    }

    #[Test]
    public function wildcard_scope_is_accepted_on_content_write(): void
    {
        Passport::actingAs($this->adminUser, ['*'], 'api');

        $response = $this->postJson('/api/module/content', [
            'title' => 'Scoped via wildcard',
            'content_type' => 'post',
        ]);

        $response->assertStatus(201);
    }

    #[Test]
    public function matching_write_scope_is_accepted(): void
    {
        Passport::actingAs($this->adminUser, ['content:write'], 'api');

        $response = $this->postJson('/api/module/content', [
            'title' => 'Scoped via content:write',
            'content_type' => 'post',
        ]);

        $response->assertStatus(201);
    }

    #[Test]
    public function token_missing_write_scope_is_rejected_with_403(): void
    {
        // `content:read` exists but is not the scope required by the
        // POST route, so the CheckToken middleware must refuse it.
        Passport::actingAs($this->adminUser, ['content:read'], 'api');

        $response = $this->postJson('/api/module/content', [
            'title' => 'Should be rejected',
            'content_type' => 'post',
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function cross_module_scope_does_not_grant_content_write(): void
    {
        // A token limited to `products:write` must not be able to POST
        // content — each module's write scope is isolated.
        Passport::actingAs($this->adminUser, ['products:write'], 'api');

        $response = $this->postJson('/api/module/content', [
            'title' => 'Wrong module scope',
            'content_type' => 'post',
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function destroy_requires_the_same_write_scope(): void
    {
        $content = Content::factory()->create();

        Passport::actingAs($this->adminUser, ['content:read'], 'api');

        $this->deleteJson("/api/module/content/{$content->id}")
            ->assertStatus(403);

        Passport::actingAs($this->adminUser, ['content:write'], 'api');

        $this->deleteJson("/api/module/content/{$content->id}")
            ->assertStatus(200);
    }

    #[Test]
    public function every_registered_module_declares_read_and_write_scopes(): void
    {
        $registered = array_keys(Passport::scopes()->pluck('id', 'id')->toArray());

        $expectedModules = [
            'content', 'pages', 'posts', 'tags', 'comments', 'menus',
            'media', 'forms', 'products', 'categories', 'orders',
            'coupons', 'shipping', 'tax', 'invoices', 'users',
            'customers', 'profile', 'newsletter', 'settings',
        ];

        foreach ($expectedModules as $module) {
            $this->assertContains("{$module}:read", $registered, "Missing scope {$module}:read");
            $this->assertContains("{$module}:write", $registered, "Missing scope {$module}:write");
        }
    }
}
