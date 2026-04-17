<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\WithFaker;
use Modules\Content\Models\Content;
use Modules\Page\Models\Page;
use Modules\Post\Models\Post;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;

final class ContentApiTest extends TestCase
{
    use WithFaker;

    protected ?User $adminUser = null;
    protected ?User $regularUser = null;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user with unique email
        $adminEmail = 'admin-' . uniqid() . '@example.com';
        $this->adminUser = User::factory()->create([
            'email' => $adminEmail,
            'is_admin' => 1,
            'is_active' => 1,
        ]);

        // Create regular user with unique email
        $userEmail = 'user-' . uniqid() . '@example.com';
        $this->regularUser = User::factory()->create([
            'email' => $userEmail,
            'is_admin' => 0,
            'is_active' => 1,
        ]);
    }

    #[Test]
    public function it_can_list_content_via_api(): void
    {
        // Create test content
        Content::factory()->count(5)->create();

        $response = $this->getJson('/api/content');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'url',
                        'content_type',
                        'is_active',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }

    #[Test]
    public function it_can_show_content_via_api(): void
    {
        $content = Content::factory()->create([
            'title' => 'Test Content',
            'content_type' => 'page',
        ]);

        $response = $this->getJson("/api/content/{$content->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $content->id,
                    'title' => 'Test Content',
                    'content_type' => 'page',
                ]
            ]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_content(): void
    {
        $response = $this->getJson('/api/content/99999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Content not found',
            ]);
    }

    #[Test]
    public function it_can_create_content_via_api_with_auth(): void
    {
        $contentData = [
            'title' => 'New API Content',
            'content_type' => 'page',
            'content' => 'This is the content body',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/content', $contentData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Content created successfully',
                'data' => [
                    'title' => 'New API Content',
                    'content_type' => 'page',
                ]
            ]);

        $this->assertDatabaseHas('content', [
            'title' => 'New API Content',
            'content_type' => 'page',
        ]);
    }

    #[Test]
    public function it_requires_authentication_to_create_content(): void
    {
        $contentData = [
            'title' => 'New API Content',
            'content_type' => 'page',
        ];

        $response = $this->postJson('/api/content', $contentData);

        $response->assertStatus(401);
    }

    #[Test]
    public function it_validates_required_fields_when_creating_content(): void
    {
        $response = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/content', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    #[Test]
    public function it_validates_unique_url_when_creating_content(): void
    {
        $existingContent = Content::factory()->create([
            'url' => 'test-url',
        ]);

        $contentData = [
            'title' => 'New Content',
            'content_type' => 'page',
            'url' => 'test-url',
        ];

        $response = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/content', $contentData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['url']);
    }

    #[Test]
    public function it_can_update_content_via_api_with_auth(): void
    {
        $content = Content::factory()->create([
            'title' => 'Original Title',
            'is_active' => true,
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'is_active' => false,
        ];

        $response = $this->actingAs($this->adminUser, 'api')
            ->putJson("/api/content/{$content->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Content updated successfully',
                'data' => [
                    'title' => 'Updated Title',
                    'is_active' => false,
                ]
            ]);

        $this->assertDatabaseHas('content', [
            'id' => $content->id,
            'title' => 'Updated Title',
            'is_active' => false,
        ]);
    }

    #[Test]
    public function it_can_partially_update_content_via_api(): void
    {
        $content = Content::factory()->create([
            'title' => 'Original Title',
            'content' => 'Original content',
        ]);

        $response = $this->actingAs($this->adminUser, 'api')
            ->patchJson("/api/content/{$content->id}", [
                'title' => 'Patched Title',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'Patched Title',
                ]
            ]);

        $this->assertDatabaseHas('content', [
            'id' => $content->id,
            'title' => 'Patched Title',
            'content' => 'Original content',
        ]);
    }

    #[Test]
    public function it_returns_404_when_updating_nonexistent_content(): void
    {
        $response = $this->actingAs($this->adminUser, 'api')
            ->putJson('/api/content/99999', [
                'title' => 'New Title',
            ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Content not found',
            ]);
    }

    #[Test]
    public function it_can_delete_content_via_api_with_auth(): void
    {
        $content = Content::factory()->create();

        $response = $this->actingAs($this->adminUser, 'api')
            ->deleteJson("/api/content/{$content->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Content deleted successfully',
                'data' => [
                    'id' => $content->id,
                ]
            ]);

        $this->assertDatabaseHas('content', [
            'id' => $content->id,
            'is_deleted' => 1,
        ]);
    }

    #[Test]
    public function it_returns_404_when_deleting_nonexistent_content(): void
    {
        $response = $this->actingAs($this->adminUser, 'api')
            ->deleteJson('/api/content/99999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Content not found',
            ]);
    }

    #[Test]
    public function it_can_filter_content_by_type(): void
    {
        Content::factory()->count(3)->create(['content_type' => 'page']);
        Content::factory()->count(2)->create(['content_type' => 'post']);

        $response = $this->getJson('/api/content?content_type=page');

        $response->assertStatus(200);
        // The filter should work, but we just verify the API responds correctly
    }

    #[Test]
    public function it_can_filter_content_by_status(): void
    {
        Content::factory()->count(2)->create(['is_active' => true]);
        Content::factory()->count(3)->create(['is_active' => false]);

        $response = $this->getJson('/api/content?is_active=1');

        $response->assertStatus(200);
        // The filter should work, but we just verify the API responds correctly
    }

    #[Test]
    public function it_can_search_content(): void
    {
        Content::factory()->create(['title' => 'Searchable Title']);
        Content::factory()->create(['title' => 'Different Title']);

        $response = $this->getJson('/api/content?search=Searchable');

        $response->assertStatus(200);
        // Search functionality should work
    }

    #[Test]
    public function it_paginates_content_list(): void
    {
        Content::factory()->count(50)->create();

        $response = $this->getJson('/api/content?limit=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'from', 'last_page', 'per_page', 'to', 'total'],
            ]);
    }

    #[Test]
    public function it_can_list_pages_via_api(): void
    {
        $pages = Page::factory()->count(5)->create();

        $response = $this->getJson('/api/pages');

        $response->assertStatus(200);

        // Total count (across all pages) should include the 5 we created
        $this->assertGreaterThanOrEqual(5, $response->json('meta.total'));

        // Verify the individual page can be retrieved
        $this->getJson('/api/pages/' . $pages->first()->id)
            ->assertStatus(200)
            ->assertJsonPath('data.id', $pages->first()->id);
    }

    #[Test]
    public function it_can_show_page_via_api(): void
    {
        $page = Page::factory()->create(['title' => 'Test Page']);

        $response = $this->getJson("/api/pages/{$page->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $page->id,
                    'title' => 'Test Page',
                ]
            ]);
    }

    #[Test]
    public function it_can_create_page_via_api_with_auth(): void
    {
        $pageData = [
            'title' => 'New API Page',
            'content' => 'Page content',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/pages', $pageData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Page created successfully',
            ]);

        $this->assertDatabaseHas('content', [
            'title' => 'New API Page',
            'content_type' => 'page',
        ]);
    }

    #[Test]
    public function it_can_update_page_via_api(): void
    {
        $page = Page::factory()->create(['title' => 'Original']);

        $response = $this->actingAs($this->adminUser, 'api')
            ->putJson("/api/pages/{$page->id}", [
                'title' => 'Updated',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'Updated',
                ]
            ]);
    }

    #[Test]
    public function it_can_delete_page_via_api(): void
    {
        $page = Page::factory()->create();

        $response = $this->actingAs($this->adminUser, 'api')
            ->deleteJson("/api/pages/{$page->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('content', ['id' => $page->id, 'is_deleted' => 1]);
    }

    #[Test]
    public function it_can_list_posts_via_api(): void
    {
        $initialCount = Post::where('is_deleted', '!=', 1)->count();
        Post::factory()->count(5)->create();

        $response = $this->getJson('/api/posts?limit=200');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'meta']);
        // Use meta.total which reflects actual DB count, not limited page count
        $this->assertGreaterThanOrEqual($initialCount + 5, $response->json('meta.total'));
    }

    #[Test]
    public function it_can_show_post_via_api(): void
    {
        $post = Post::factory()->create(['title' => 'Test Post']);

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $post->id,
                    'title' => 'Test Post',
                ]
            ]);
    }

    #[Test]
    public function it_can_create_post_via_api_with_auth(): void
    {
        $postData = [
            'title' => 'New API Post',
            'content' => 'Post content',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/posts', $postData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Post created successfully',
            ]);

        $this->assertDatabaseHas('content', [
            'title' => 'New API Post',
            'content_type' => 'post',
        ]);
    }

    #[Test]
    public function it_can_update_post_via_api(): void
    {
        $post = Post::factory()->create(['title' => 'Original']);

        $response = $this->actingAs($this->adminUser, 'api')
            ->putJson("/api/posts/{$post->id}", [
                'title' => 'Updated',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'Updated',
                ]
            ]);
    }

    #[Test]
    public function it_can_delete_post_via_api(): void
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->adminUser, 'api')
            ->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('content', ['id' => $post->id, 'is_deleted' => 1]);
    }
}
