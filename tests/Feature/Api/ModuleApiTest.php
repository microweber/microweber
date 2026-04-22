<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use MicroweberPackages\User\Models\User;
use Modules\Comments\Models\Comment;
use Modules\Content\Models\Content;
use Modules\Menu\Models\Menu;
use Modules\Page\Models\Page;
use Modules\Post\Models\Post;
use Modules\Tag\Models\Tag;
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
    public function tags_index_is_public_under_module_namespace(): void
    {
        Tag::factory()->count(3)->create();

        $response = $this->getJson('/api/module/tags');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'name', 'slug']]]);
    }

    #[Test]
    public function tags_show_is_public_under_module_namespace(): void
    {
        $tag = Tag::factory()->create(['name' => 'Module API Tag']);

        $response = $this->getJson("/api/module/tags/{$tag->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => ['id' => $tag->id, 'name' => 'Module API Tag'],
            ]);
    }

    #[Test]
    public function tags_store_requires_passport_authentication(): void
    {
        $this->postJson('/api/module/tags', ['name' => 'Unauth Tag'])
            ->assertStatus(401);
    }

    #[Test]
    public function tags_store_rejects_non_admin_passport_user(): void
    {
        $response = $this->actingAs($this->regularUser, 'api')
            ->postJson('/api/module/tags', ['name' => 'Regular User Tag']);

        $response->assertStatus(403);
    }

    #[Test]
    public function tags_store_accepts_admin_passport_token(): void
    {
        $uniqueName = 'Created via module API ' . uniqid();

        $response = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/module/tags', [
                'name' => $uniqueName,
                'description' => 'Test tag description',
                'suggest' => true,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => $uniqueName,
                    'suggest' => true,
                ],
            ]);

        $this->assertDatabaseHas('tagging_tags', ['name' => $uniqueName]);
    }

    #[Test]
    public function tags_update_requires_admin_passport_token(): void
    {
        $tag = Tag::factory()->create(['name' => 'Before Update']);

        $this->putJson("/api/module/tags/{$tag->id}", ['description' => 'After'])
            ->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->putJson("/api/module/tags/{$tag->id}", ['description' => 'After'])
            ->assertStatus(403);

        $this->actingAs($this->adminUser, 'api')
            ->putJson("/api/module/tags/{$tag->id}", ['description' => 'After'])
            ->assertStatus(200);

        $this->assertDatabaseHas('tagging_tags', [
            'id' => $tag->id,
            'description' => 'After',
        ]);
    }

    #[Test]
    public function tags_destroy_requires_admin_passport_token(): void
    {
        $tag = Tag::factory()->create();

        $this->deleteJson("/api/module/tags/{$tag->id}")
            ->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->deleteJson("/api/module/tags/{$tag->id}")
            ->assertStatus(403);

        $this->actingAs($this->adminUser, 'api')
            ->deleteJson("/api/module/tags/{$tag->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('tagging_tags', ['id' => $tag->id]);
    }

    #[Test]
    public function comments_index_is_public_under_module_namespace(): void
    {
        Comment::factory()->count(2)->create([
            'is_moderated' => true,
            'is_spam' => false,
        ]);

        $response = $this->getJson('/api/module/comments');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'rel_type', 'rel_id', 'comment_body']]]);
    }

    #[Test]
    public function comments_index_hides_unmoderated_from_public(): void
    {
        Comment::factory()->create([
            'comment_subject' => 'Moderated OK',
            'is_moderated' => true,
            'is_spam' => false,
        ]);
        Comment::factory()->create([
            'comment_subject' => 'Pending Moderation',
            'is_moderated' => false,
            'is_spam' => false,
        ]);

        $response = $this->getJson('/api/module/comments');

        $response->assertStatus(200);
        $data = $response->json('data');
        $subjects = array_column($data, 'comment_subject');
        $this->assertContains('Moderated OK', $subjects);
        $this->assertNotContains('Pending Moderation', $subjects);
    }

    #[Test]
    public function comments_show_404s_unmoderated_for_public(): void
    {
        $pending = Comment::factory()->create(['is_moderated' => false, 'is_spam' => false]);

        $this->getJson("/api/module/comments/{$pending->id}")->assertStatus(404);

        $this->actingAs($this->adminUser, 'api')
            ->getJson("/api/module/comments/{$pending->id}")
            ->assertStatus(200);
    }

    #[Test]
    public function comments_resource_hides_pii_from_public_callers(): void
    {
        $comment = Comment::factory()->create([
            'comment_email' => 'secret@example.com',
            'user_ip' => '203.0.113.42',
            'is_moderated' => true,
            'is_spam' => false,
        ]);

        $publicJson = $this->getJson("/api/module/comments/{$comment->id}")->json('data');
        $this->assertArrayNotHasKey('comment_email', $publicJson);
        $this->assertArrayNotHasKey('user_ip', $publicJson);

        $adminJson = $this->actingAs($this->adminUser, 'api')
            ->getJson("/api/module/comments/{$comment->id}")
            ->json('data');
        $this->assertSame('secret@example.com', $adminJson['comment_email']);
        $this->assertSame('203.0.113.42', $adminJson['user_ip']);
    }

    #[Test]
    public function comments_store_requires_admin_passport_token(): void
    {
        $payload = [
            'rel_type' => 'content',
            'rel_id' => '1',
            'comment_body' => 'Hello world',
        ];

        $this->postJson('/api/module/comments', $payload)->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->postJson('/api/module/comments', $payload)
            ->assertStatus(403);

        $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/module/comments', $payload)
            ->assertStatus(201)
            ->assertJson(['success' => true, 'data' => ['comment_body' => 'Hello world']]);

        $this->assertDatabaseHas('comments', ['comment_body' => 'Hello world']);
    }

    #[Test]
    public function comments_update_requires_admin_passport_token(): void
    {
        $comment = Comment::factory()->create([
            'comment_subject' => 'Before',
            'is_moderated' => true,
            'is_spam' => false,
        ]);

        $this->putJson("/api/module/comments/{$comment->id}", ['comment_subject' => 'After'])
            ->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->putJson("/api/module/comments/{$comment->id}", ['comment_subject' => 'After'])
            ->assertStatus(403);

        $this->actingAs($this->adminUser, 'api')
            ->putJson("/api/module/comments/{$comment->id}", ['comment_subject' => 'After'])
            ->assertStatus(200);

        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'comment_subject' => 'After']);
    }

    #[Test]
    public function comments_destroy_requires_admin_passport_token(): void
    {
        $comment = Comment::factory()->create(['is_moderated' => true, 'is_spam' => false]);

        $this->deleteJson("/api/module/comments/{$comment->id}")->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->deleteJson("/api/module/comments/{$comment->id}")
            ->assertStatus(403);

        $this->actingAs($this->adminUser, 'api')
            ->deleteJson("/api/module/comments/{$comment->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    #[Test]
    public function menus_index_is_public_under_module_namespace(): void
    {
        Menu::factory()->count(2)->create(['is_active' => 1]);

        $response = $this->getJson('/api/module/menus');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'title', 'item_type', 'is_active']]]);
    }

    #[Test]
    public function menus_index_hides_inactive_from_public(): void
    {
        Menu::factory()->create(['title' => 'Active Menu', 'is_active' => 1]);
        Menu::factory()->create(['title' => 'Disabled Menu', 'is_active' => 0]);

        $data = $this->getJson('/api/module/menus?limit=200')
            ->assertStatus(200)
            ->json('data');

        $titles = array_column($data, 'title');
        $this->assertContains('Active Menu', $titles);
        $this->assertNotContains('Disabled Menu', $titles);
    }

    #[Test]
    public function menus_show_404s_inactive_for_public(): void
    {
        $disabled = Menu::factory()->create(['is_active' => 0]);

        $this->getJson("/api/module/menus/{$disabled->id}")->assertStatus(404);

        $this->actingAs($this->adminUser, 'api')
            ->getJson("/api/module/menus/{$disabled->id}")
            ->assertStatus(200);
    }

    #[Test]
    public function menus_store_requires_admin_passport_token(): void
    {
        $payload = [
            'title' => 'Created via module API menu',
            'item_type' => 'menu',
        ];

        $this->postJson('/api/module/menus', $payload)->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->postJson('/api/module/menus', $payload)
            ->assertStatus(403);

        $response = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/module/menus', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'Created via module API menu',
                    'item_type' => 'menu',
                ],
            ]);

        $this->assertDatabaseHas('menus', ['title' => 'Created via module API menu']);
    }

    #[Test]
    public function menus_update_requires_admin_passport_token(): void
    {
        $menu = Menu::factory()->create(['title' => 'Before']);

        $this->putJson("/api/module/menus/{$menu->id}", ['title' => 'After'])
            ->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->putJson("/api/module/menus/{$menu->id}", ['title' => 'After'])
            ->assertStatus(403);

        $this->actingAs($this->adminUser, 'api')
            ->putJson("/api/module/menus/{$menu->id}", ['title' => 'After'])
            ->assertStatus(200);

        $this->assertDatabaseHas('menus', ['id' => $menu->id, 'title' => 'After']);
    }

    #[Test]
    public function menus_destroy_cascades_to_items_for_admin(): void
    {
        $menu = Menu::factory()->create(['item_type' => 'menu']);
        $item = Menu::factory()->item()->create([
            'parent_id' => $menu->id,
        ]);

        $this->deleteJson("/api/module/menus/{$menu->id}")->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->deleteJson("/api/module/menus/{$menu->id}")
            ->assertStatus(403);

        $this->actingAs($this->adminUser, 'api')
            ->deleteJson("/api/module/menus/{$menu->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
        $this->assertDatabaseMissing('menus', ['id' => $item->id]);
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
            'api.module.tags.index',
            'api.module.tags.store',
            'api.module.tags.show',
            'api.module.tags.update',
            'api.module.tags.destroy',
            'api.module.comments.index',
            'api.module.comments.store',
            'api.module.comments.show',
            'api.module.comments.update',
            'api.module.comments.destroy',
            'api.module.menus.index',
            'api.module.menus.store',
            'api.module.menus.show',
            'api.module.menus.update',
            'api.module.menus.destroy',
        ];

        foreach ($expected as $name) {
            $this->assertContains($name, $names->values()->all(),
                "Expected route name {$name} to be registered");
        }
    }
}
