<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use MicroweberPackages\User\Models\User;
use Modules\Content\Models\Content;
use Modules\Page\Models\Page;
use Modules\Post\Models\Post;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Feature tests for the unified /api/module/{module} namespace.
 *
 * Reads must be public; writes must require a Passport-authenticated admin.
 */
final class ModuleApiTest extends TestCase
{
    private User $adminUser;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'email' => 'module-api-admin-' . uniqid() . '@example.com',
            'is_admin' => 1,
            'is_active' => 1,
        ]);

        $this->regularUser = User::factory()->create([
            'email' => 'module-api-user-' . uniqid() . '@example.com',
            'is_admin' => 0,
            'is_active' => 1,
        ]);
    }

    #[Test]
    public function content_index_is_public_under_module_namespace(): void
    {
        Content::factory()->count(3)->create();

        $response = $this->getJson('/api/module/content');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'title', 'url']]]);
    }

    #[Test]
    public function content_show_is_public_under_module_namespace(): void
    {
        $content = Content::factory()->create(['title' => 'Module API Content']);

        $response = $this->getJson("/api/module/content/{$content->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => ['id' => $content->id, 'title' => 'Module API Content'],
            ]);
    }

    #[Test]
    public function content_store_requires_passport_authentication(): void
    {
        $response = $this->postJson('/api/module/content', ['title' => 'Unauth']);

        $response->assertStatus(401);
    }

    #[Test]
    public function content_store_rejects_non_admin_passport_user(): void
    {
        $response = $this->actingAs($this->regularUser, 'api')
            ->postJson('/api/module/content', ['title' => 'Regular User Attempt']);

        $response->assertStatus(403);
    }

    #[Test]
    public function content_store_accepts_admin_passport_token(): void
    {
        $response = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/module/content', [
                'title' => 'Created via module API',
                'content_type' => 'page',
                'is_active' => true,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => ['title' => 'Created via module API'],
            ]);

        $this->assertDatabaseHas('content', ['title' => 'Created via module API']);
    }

    #[Test]
    public function content_update_requires_admin_passport_token(): void
    {
        $content = Content::factory()->create(['title' => 'Before']);

        $this->putJson("/api/module/content/{$content->id}", ['title' => 'After'])
            ->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->putJson("/api/module/content/{$content->id}", ['title' => 'After'])
            ->assertStatus(403);

        $this->actingAs($this->adminUser, 'api')
            ->putJson("/api/module/content/{$content->id}", ['title' => 'After'])
            ->assertStatus(200);

        $this->assertDatabaseHas('content', ['id' => $content->id, 'title' => 'After']);
    }

    #[Test]
    public function pages_index_is_public_under_module_namespace(): void
    {
        $response = $this->getJson('/api/module/pages');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    #[Test]
    public function pages_store_requires_passport_authentication(): void
    {
        $this->postJson('/api/module/pages', ['title' => 'Unauth'])
            ->assertStatus(401);
    }

    #[Test]
    public function posts_index_is_public_under_module_namespace(): void
    {
        $response = $this->getJson('/api/module/posts');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    #[Test]
    public function posts_store_requires_passport_authentication(): void
    {
        $this->postJson('/api/module/posts', ['title' => 'Unauth'])
            ->assertStatus(401);
    }

    #[Test]
    public function module_api_routes_are_registered(): void
    {
        $router = app('router');
        $names = collect($router->getRoutes())->map(fn ($r) => $r->getName())->filter();

        $expected = [
            'api.module.content.index',
            'api.module.content.store',
            'api.module.content.show',
            'api.module.content.update',
            'api.module.content.destroy',
            'api.module.pages.index',
            'api.module.pages.store',
            'api.module.posts.index',
            'api.module.posts.store',
        ];

        foreach ($expected as $name) {
            $this->assertContains($name, $names->values()->all(),
                "Expected route name {$name} to be registered");
        }
    }
}
