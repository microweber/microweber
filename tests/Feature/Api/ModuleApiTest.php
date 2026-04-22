<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\User\Models\User;
use Modules\Category\Models\Category;
use Modules\Comments\Models\Comment;
use Modules\ContactForm\Models\Form;
use Modules\Content\Models\Content;
use Modules\Coupons\Models\Coupon;
use Modules\Customer\Models\Customer;
use Modules\Invoice\Models\Invoice;
use Modules\Media\Models\Media;
use Modules\Menu\Models\Menu;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Order\Models\Order;
use Modules\Page\Models\Page;
use Modules\Post\Models\Post;
use Modules\Product\Models\Product;
use Modules\Shipping\Models\ShippingProvider;
use Modules\Tag\Models\Tag;
use Modules\Tax\Models\TaxRate;
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

    /**
     * Insert or update an `options` row via the query builder. The Option
     * Eloquent model's $fillable silently drops option_key, so we bypass
     * it for test fixtures.
     */
    private function seedOption(string $key, string $value, ?string $group = null): void
    {
        $existing = DB::table('options')
            ->where('option_key', $key)
            ->when($group, fn ($q, $g) => $q->where('option_group', $g))
            ->first();

        if ($existing) {
            DB::table('options')->where('id', $existing->id)->update([
                'option_value' => $value,
                'updated_at' => now(),
            ]);

            return;
        }

        DB::table('options')->insert([
            'option_key' => $key,
            'option_value' => $value,
            'option_group' => $group,
            'created_at' => now(),
            'updated_at' => now(),
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
    public function media_index_is_public_under_module_namespace(): void
    {
        Media::factory()->count(2)->create();

        $response = $this->getJson('/api/module/media');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'title', 'filename', 'media_type', 'is_synced_to_cdn']]]);
    }

    #[Test]
    public function media_index_filters_by_rel_type_and_rel_id(): void
    {
        Media::factory()->create(['rel_type' => 'content', 'rel_id' => '42', 'title' => 'Media For 42']);
        Media::factory()->create(['rel_type' => 'content', 'rel_id' => '99', 'title' => 'Media For 99']);

        $data = $this->getJson('/api/module/media?rel_type=content&rel_id=42')
            ->assertStatus(200)
            ->json('data');

        $titles = array_column($data, 'title');
        $this->assertContains('Media For 42', $titles);
        $this->assertNotContains('Media For 99', $titles);
    }

    #[Test]
    public function media_resource_hides_upload_trail_from_public_callers(): void
    {
        $media = Media::factory()->create([
            'file_hash' => 'sha256-fingerprint',
            'session_id' => 'session-abc-123',
        ]);

        $publicJson = $this->getJson("/api/module/media/{$media->id}")->json('data');
        $this->assertArrayNotHasKey('file_hash', $publicJson);
        $this->assertArrayNotHasKey('session_id', $publicJson);

        $adminJson = $this->actingAs($this->adminUser, 'api')
            ->getJson("/api/module/media/{$media->id}")
            ->json('data');
        $this->assertSame('sha256-fingerprint', $adminJson['file_hash']);
        $this->assertSame('session-abc-123', $adminJson['session_id']);
    }

    #[Test]
    public function media_store_requires_admin_passport_token(): void
    {
        $payload = [
            'filename' => '{SITE_URL}userfiles/media/default/uploaded-' . uniqid() . '.jpg',
            'title' => 'Uploaded via module API',
            'media_type' => 'picture',
            'rel_type' => 'content',
            'rel_id' => '123',
        ];

        $this->postJson('/api/module/media', $payload)->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->postJson('/api/module/media', $payload)
            ->assertStatus(403);

        $response = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/module/media', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'Uploaded via module API',
                    'media_type' => 'picture',
                    'rel_type' => 'content',
                    'rel_id' => '123',
                ],
            ]);

        $this->assertDatabaseHas('media', ['title' => 'Uploaded via module API']);
    }

    #[Test]
    public function media_update_requires_admin_passport_token(): void
    {
        $media = Media::factory()->create(['title' => 'Before']);

        $this->putJson("/api/module/media/{$media->id}", ['title' => 'After'])
            ->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->putJson("/api/module/media/{$media->id}", ['title' => 'After'])
            ->assertStatus(403);

        $this->actingAs($this->adminUser, 'api')
            ->putJson("/api/module/media/{$media->id}", ['title' => 'After'])
            ->assertStatus(200);

        $this->assertDatabaseHas('media', ['id' => $media->id, 'title' => 'After']);
    }

    #[Test]
    public function media_destroy_requires_admin_passport_token(): void
    {
        $media = Media::factory()->create();

        $this->deleteJson("/api/module/media/{$media->id}")->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->deleteJson("/api/module/media/{$media->id}")
            ->assertStatus(403);

        $this->actingAs($this->adminUser, 'api')
            ->deleteJson("/api/module/media/{$media->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('media', ['id' => $media->id]);
    }

    #[Test]
    public function forms_index_is_public_under_module_namespace(): void
    {
        Form::factory()->count(2)->create(['is_active' => 1]);

        $response = $this->getJson('/api/module/forms');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'name', 'slug', 'module_id', 'is_active']]]);
    }

    #[Test]
    public function forms_index_hides_inactive_from_public(): void
    {
        Form::factory()->create(['name' => 'Active Form', 'is_active' => 1]);
        Form::factory()->create(['name' => 'Disabled Form', 'is_active' => 0]);

        $data = $this->getJson('/api/module/forms?limit=200')
            ->assertStatus(200)
            ->json('data');

        $names = array_column($data, 'name');
        $this->assertContains('Active Form', $names);
        $this->assertNotContains('Disabled Form', $names);
    }

    #[Test]
    public function forms_resource_hides_recipient_emails_from_public_callers(): void
    {
        $form = Form::factory()->create([
            'emails_notifications' => 'staff@example.com',
            'emails_notifications_subject' => 'New contact submission',
        ]);

        $publicJson = $this->getJson("/api/module/forms/{$form->id}")->json('data');
        $this->assertArrayNotHasKey('emails_notifications', $publicJson);
        $this->assertArrayNotHasKey('emails_notifications_subject', $publicJson);

        $adminJson = $this->actingAs($this->adminUser, 'api')
            ->getJson("/api/module/forms/{$form->id}")
            ->json('data');
        $this->assertSame('staff@example.com', $adminJson['emails_notifications']);
        $this->assertSame('New contact submission', $adminJson['emails_notifications_subject']);
    }

    #[Test]
    public function forms_store_requires_admin_passport_token(): void
    {
        $payload = [
            'name' => 'Created via module API form ' . uniqid(),
            'confirmation_message' => 'Thanks!',
        ];

        $this->postJson('/api/module/forms', $payload)->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->postJson('/api/module/forms', $payload)
            ->assertStatus(403);

        $response = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/module/forms', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => $payload['name'],
                    'confirmation_message' => 'Thanks!',
                ],
            ]);

        $this->assertDatabaseHas('forms', ['name' => $payload['name']]);
    }

    #[Test]
    public function forms_update_requires_admin_passport_token(): void
    {
        $form = Form::factory()->create(['name' => 'Before']);

        $this->putJson("/api/module/forms/{$form->id}", ['name' => 'After'])
            ->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->putJson("/api/module/forms/{$form->id}", ['name' => 'After'])
            ->assertStatus(403);

        $this->actingAs($this->adminUser, 'api')
            ->putJson("/api/module/forms/{$form->id}", ['name' => 'After'])
            ->assertStatus(200);

        $this->assertDatabaseHas('forms', ['id' => $form->id, 'name' => 'After']);
    }

    #[Test]
    public function forms_destroy_requires_admin_passport_token(): void
    {
        $form = Form::factory()->create();

        $this->deleteJson("/api/module/forms/{$form->id}")->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->deleteJson("/api/module/forms/{$form->id}")
            ->assertStatus(403);

        $this->actingAs($this->adminUser, 'api')
            ->deleteJson("/api/module/forms/{$form->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('forms', ['id' => $form->id]);
    }

    #[Test]
    public function contact_form_alias_resolves_to_same_controller(): void
    {
        $form = Form::factory()->create(['name' => 'Alias Test Form']);

        // Both slugs share the same controller — hitting either endpoint
        // should return the same resource.
        $plural = $this->getJson("/api/module/forms/{$form->id}")->json('data');
        $alias = $this->getJson("/api/module/contact-form/{$form->id}")->json('data');

        $this->assertSame($plural, $alias);
        $this->assertSame('Alias Test Form', $alias['name']);
    }

    #[Test]
    public function products_index_is_public(): void
    {
        Product::factory()->count(2)->create();

        $this->getJson('/api/module/products')
            ->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    #[Test]
    public function products_store_requires_admin(): void
    {
        $payload = ['title' => 'API Product ' . uniqid()];

        $this->postJson('/api/module/products', $payload)->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->postJson('/api/module/products', $payload)
            ->assertStatus(403);

        $response = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/module/products', $payload);

        $response->assertStatus(201)
            ->assertJson(['success' => true, 'data' => ['title' => $payload['title']]]);
    }

    #[Test]
    public function products_destroy_requires_admin(): void
    {
        $product = Product::factory()->create();

        $this->deleteJson("/api/module/products/{$product->id}")->assertStatus(401);
        $this->actingAs($this->regularUser, 'api')
            ->deleteJson("/api/module/products/{$product->id}")->assertStatus(403);
        $this->actingAs($this->adminUser, 'api')
            ->deleteJson("/api/module/products/{$product->id}")->assertStatus(200);
    }

    #[Test]
    public function categories_crud_roundtrip(): void
    {
        $category = Category::factory()->create(['title' => 'Public Cat']);

        $this->getJson("/api/module/categories/{$category->id}")
            ->assertStatus(200)
            ->assertJson(['data' => ['id' => $category->id, 'title' => 'Public Cat']]);

        $created = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/module/categories', [
                'title' => 'API Category ' . uniqid(),
                'data_type' => 'category',
                'rel_type' => 'Modules\\Content\\Models\\Content',
            ])
            ->assertStatus(201)
            ->json('data');

        $this->actingAs($this->adminUser, 'api')
            ->putJson("/api/module/categories/{$created['id']}", ['title' => 'Renamed Category'])
            ->assertStatus(200);

        $this->assertDatabaseHas('categories', ['id' => $created['id'], 'title' => 'Renamed Category']);
    }

    #[Test]
    public function orders_index_is_admin_only(): void
    {
        Order::factory()->count(2)->create();

        // Orders hold PII — even index is gated to admin, unlike content-type
        // modules where public reads are fine.
        $this->getJson('/api/module/orders')->assertStatus(401);
        $this->actingAs($this->regularUser, 'api')
            ->getJson('/api/module/orders')->assertStatus(403);
        $this->actingAs($this->adminUser, 'api')
            ->getJson('/api/module/orders')->assertStatus(200);
    }

    #[Test]
    public function orders_store_and_update_require_admin(): void
    {
        $payload = [
            'email' => 'buyer-' . uniqid() . '@example.com',
            'first_name' => 'Buyer',
            'last_name' => 'Test',
            'amount' => 99.99,
            'currency' => 'USD',
        ];

        $created = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/module/orders', $payload)
            ->assertStatus(201)
            ->json('data');

        $this->actingAs($this->adminUser, 'api')
            ->putJson("/api/module/orders/{$created['id']}", ['order_status' => 'shipped'])
            ->assertStatus(200);

        $this->assertDatabaseHas('cart_orders', ['id' => $created['id'], 'order_status' => 'shipped']);
    }

    #[Test]
    public function coupons_public_index_hides_expired(): void
    {
        Coupon::factory()->create(['is_active' => true, 'coupon_code' => 'ACTIVE-' . uniqid()]);
        Coupon::factory()->create(['is_active' => false, 'coupon_code' => 'INACTIVE-' . uniqid()]);

        $response = $this->getJson('/api/module/coupons')->assertStatus(200);
        $codes = collect($response->json('data'))->pluck('coupon_code')->all();

        foreach ($codes as $code) {
            $this->assertStringStartsWith('ACTIVE-', $code);
        }
    }

    #[Test]
    public function coupons_writes_require_admin(): void
    {
        $payload = [
            'coupon_code' => 'APITEST-' . uniqid(),
            'discount_type' => 'percentage',
            'discount_value' => 10,
        ];

        $this->postJson('/api/module/coupons', $payload)->assertStatus(401);
        $this->actingAs($this->regularUser, 'api')
            ->postJson('/api/module/coupons', $payload)->assertStatus(403);
        $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/module/coupons', $payload)->assertStatus(201);
    }

    #[Test]
    public function shipping_providers_public_reads_admin_writes(): void
    {
        $provider = ShippingProvider::factory()->create(['is_active' => true]);

        $this->getJson('/api/module/shipping')->assertStatus(200);
        $this->getJson("/api/module/shipping/{$provider->id}")
            ->assertStatus(200)
            ->assertJson(['data' => ['id' => $provider->id]]);

        $this->actingAs($this->regularUser, 'api')
            ->postJson('/api/module/shipping', ['name' => 'X', 'provider' => 'flat_rate'])
            ->assertStatus(403);

        $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/module/shipping', ['name' => 'Admin Provider', 'provider' => 'flat_rate'])
            ->assertStatus(201);
    }

    #[Test]
    public function shipping_provider_settings_are_admin_only(): void
    {
        $provider = ShippingProvider::factory()->create([
            'is_active' => true,
            'settings' => json_encode(['api_key' => 'secret-key-123']),
        ]);

        // Public reader must not see provider settings (which can hold secrets).
        $publicData = $this->getJson("/api/module/shipping/{$provider->id}")->json('data');
        $this->assertArrayNotHasKey('settings', $publicData);

        $adminData = $this->actingAs($this->adminUser, 'api')
            ->getJson("/api/module/shipping/{$provider->id}")->json('data');
        $this->assertArrayHasKey('settings', $adminData);
    }

    #[Test]
    public function tax_rates_crud(): void
    {
        TaxRate::factory()->count(2)->create(['is_active' => true]);
        $this->getJson('/api/module/tax')->assertStatus(200);

        $created = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/module/tax', [
                'name' => 'VAT ' . uniqid(),
                'country_code' => 'BG',
                'type' => 'percentage',
                'rate' => 20,
            ])
            ->assertStatus(201)
            ->json('data');

        $this->actingAs($this->adminUser, 'api')
            ->deleteJson("/api/module/tax/{$created['id']}")->assertStatus(200);
    }

    #[Test]
    public function invoices_are_admin_only_on_every_verb(): void
    {
        $invoice = Invoice::factory()->create();

        // actingAs() persists across subsequent requests on the same test
        // instance, so all anonymous assertions must run before any login.
        $this->getJson('/api/module/invoices')->assertStatus(401);
        $this->getJson("/api/module/invoices/{$invoice->id}")->assertStatus(401);

        $this->actingAs($this->regularUser, 'api');
        $this->getJson('/api/module/invoices')->assertStatus(403);
        $this->getJson("/api/module/invoices/{$invoice->id}")->assertStatus(403);

        $this->actingAs($this->adminUser, 'api');
        $this->getJson('/api/module/invoices')->assertStatus(200);
        $this->getJson("/api/module/invoices/{$invoice->id}")->assertStatus(200);
    }

    #[Test]
    public function users_are_admin_only_on_every_verb(): void
    {
        $target = User::factory()->create();

        $this->getJson('/api/module/users')->assertStatus(401);
        $this->getJson("/api/module/users/{$target->id}")->assertStatus(401);

        $this->actingAs($this->regularUser, 'api');
        $this->getJson('/api/module/users')->assertStatus(403);
        $this->getJson("/api/module/users/{$target->id}")->assertStatus(403);

        $this->actingAs($this->adminUser, 'api');
        $this->getJson('/api/module/users')->assertStatus(200);
        $this->getJson("/api/module/users/{$target->id}")->assertStatus(200)
            ->assertJsonPath('data.id', $target->id);
    }

    #[Test]
    public function users_create_update_and_delete_require_admin(): void
    {
        $payload = [
            'email' => 'users-api-' . uniqid() . '@example.com',
            'password' => 'secret-password-123',
            'first_name' => 'Test',
            'last_name' => 'User',
        ];

        // Unauthenticated write → 401
        $this->postJson('/api/module/users', $payload)->assertStatus(401);

        // Regular user → 403
        $this->actingAs($this->regularUser, 'api')
            ->postJson('/api/module/users', $payload)->assertStatus(403);

        // Admin → 201
        $created = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/module/users', $payload)
            ->assertStatus(201)
            ->json('data');

        $this->actingAs($this->adminUser, 'api')
            ->putJson("/api/module/users/{$created['id']}", ['first_name' => 'Renamed'])
            ->assertStatus(200)
            ->assertJsonPath('data.first_name', 'Renamed');

        $this->actingAs($this->adminUser, 'api')
            ->deleteJson("/api/module/users/{$created['id']}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('users', ['id' => $created['id']]);
    }

    #[Test]
    public function users_cannot_delete_themselves_via_api(): void
    {
        $this->actingAs($this->adminUser, 'api')
            ->deleteJson("/api/module/users/{$this->adminUser->id}")
            ->assertStatus(409);
    }

    #[Test]
    public function customers_are_admin_only_on_every_verb(): void
    {
        $customer = Customer::factory()->create();

        $this->getJson('/api/module/customers')->assertStatus(401);
        $this->getJson("/api/module/customers/{$customer->id}")->assertStatus(401);

        $this->actingAs($this->regularUser, 'api');
        $this->getJson('/api/module/customers')->assertStatus(403);
        $this->getJson("/api/module/customers/{$customer->id}")->assertStatus(403);

        $this->actingAs($this->adminUser, 'api');
        $this->getJson('/api/module/customers')->assertStatus(200);
        $this->getJson("/api/module/customers/{$customer->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $customer->id);
    }

    #[Test]
    public function customers_crud_roundtrip_for_admin(): void
    {
        $created = $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/module/customers', [
                'email' => 'cust-' . uniqid() . '@example.com',
                'first_name' => 'Test',
                'last_name' => 'Customer',
                'phone' => '555-1234',
            ])
            ->assertStatus(201)
            ->json('data');

        $this->actingAs($this->adminUser, 'api')
            ->putJson("/api/module/customers/{$created['id']}", ['status' => 'inactive'])
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'inactive');

        $this->actingAs($this->adminUser, 'api')
            ->deleteJson("/api/module/customers/{$created['id']}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('customers', ['id' => $created['id']]);
    }

    #[Test]
    public function profile_requires_authentication(): void
    {
        $this->getJson('/api/module/profile')->assertStatus(401);
        $this->putJson('/api/module/profile', ['first_name' => 'X'])->assertStatus(401);
        $this->postJson('/api/module/profile/change-password', [
            'current_password' => 'x',
            'new_password' => 'x',
        ])->assertStatus(401);
    }

    #[Test]
    public function profile_returns_authenticated_user(): void
    {
        $this->actingAs($this->regularUser, 'api')
            ->getJson('/api/module/profile')
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => ['id' => $this->regularUser->id, 'email' => $this->regularUser->email],
            ]);
    }

    #[Test]
    public function profile_update_edits_own_record_not_admin_flags(): void
    {
        $this->actingAs($this->regularUser, 'api')
            ->putJson('/api/module/profile', [
                'first_name' => 'Renamed',
                // Smuggle is_admin — it must be ignored because the controller
                // validator doesn't accept it.
                'is_admin' => 1,
            ])
            ->assertStatus(200)
            ->assertJsonPath('data.first_name', 'Renamed')
            ->assertJsonPath('data.is_admin', 0);

        $this->assertDatabaseHas('users', [
            'id' => $this->regularUser->id,
            'first_name' => 'Renamed',
            'is_admin' => 0,
        ]);
    }

    #[Test]
    public function profile_change_password_requires_current_password(): void
    {
        $user = User::factory()->create([
            'email' => 'pw-' . uniqid() . '@example.com',
            'password' => bcrypt('current-password'),
        ]);

        $this->actingAs($user, 'api')
            ->postJson('/api/module/profile/change-password', [
                'current_password' => 'wrong-password',
                'new_password' => 'new-password-123',
            ])
            ->assertStatus(422);

        $this->actingAs($user, 'api')
            ->postJson('/api/module/profile/change-password', [
                'current_password' => 'current-password',
                'new_password' => 'new-password-123',
            ])
            ->assertStatus(200);
    }

    #[Test]
    public function newsletter_subscribe_is_public(): void
    {
        $email = 'subscriber-' . uniqid() . '@example.com';

        $this->postJson('/api/module/newsletter', ['email' => $email, 'name' => 'Anon'])
            ->assertStatus(201)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => $email,
            'status' => 'active',
        ]);
    }

    #[Test]
    public function newsletter_resubscribe_returns_200_not_duplicate(): void
    {
        $email = 'resub-' . uniqid() . '@example.com';

        $this->postJson('/api/module/newsletter', ['email' => $email])->assertStatus(201);
        $this->postJson('/api/module/newsletter', ['email' => $email])->assertStatus(200);

        $this->assertSame(
            1,
            NewsletterSubscriber::where('email', $email)->count(),
            'Re-subscribing should not create a duplicate record'
        );
    }

    #[Test]
    public function newsletter_index_is_admin_only(): void
    {
        $this->getJson('/api/module/newsletter')->assertStatus(401);
        $this->actingAs($this->regularUser, 'api')
            ->getJson('/api/module/newsletter')->assertStatus(403);
        $this->actingAs($this->adminUser, 'api')
            ->getJson('/api/module/newsletter')->assertStatus(200);
    }

    #[Test]
    public function newsletter_unsubscribe_is_public_and_flips_status(): void
    {
        $subscriber = NewsletterSubscriber::factory()->create([
            'email' => 'unsub-' . uniqid() . '@example.com',
            'is_subscribed' => true,
            'status' => 'active',
        ]);

        $this->postJson('/api/module/newsletter/unsubscribe', ['email' => $subscriber->email])
            ->assertStatus(200);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'id' => $subscriber->id,
            'status' => 'unsubscribed',
            'is_subscribed' => 0,
        ]);
    }

    #[Test]
    public function settings_public_index_only_exposes_whitelisted_keys(): void
    {
        $this->seedOption('website_title', 'Headless API Site', 'website');
        $this->seedOption('stripe_secret_key', 'sk_test_shouldnotleak', 'payments');

        $response = $this->getJson('/api/module/settings')->assertStatus(200);
        $keys = collect($response->json('data'))->pluck('option_key')->all();

        $this->assertContains('website_title', $keys);
        $this->assertNotContains('stripe_secret_key', $keys,
            'Public index must never expose non-whitelisted option keys');
    }

    #[Test]
    public function settings_show_blocks_non_whitelisted_keys_for_anonymous(): void
    {
        $this->seedOption('stripe_secret_key', 'sk_test_shouldnotleak', 'payments');

        $this->getJson('/api/module/settings/stripe_secret_key')->assertStatus(403);

        $this->actingAs($this->adminUser, 'api')
            ->getJson('/api/module/settings/stripe_secret_key')
            ->assertStatus(200)
            ->assertJsonPath('data.option_value', 'sk_test_shouldnotleak');
    }

    #[Test]
    public function settings_write_requires_admin(): void
    {
        $key = 'test_setting_' . uniqid();
        $payload = ['option_key' => $key, 'option_value' => 'Initial'];

        $this->postJson('/api/module/settings', $payload)->assertStatus(401);

        $this->actingAs($this->regularUser, 'api')
            ->postJson('/api/module/settings', $payload)->assertStatus(403);

        $this->actingAs($this->adminUser, 'api')
            ->postJson('/api/module/settings', $payload)
            ->assertStatus(201)
            ->assertJsonPath('data.option_value', 'Initial');

        $this->actingAs($this->adminUser, 'api')
            ->putJson("/api/module/settings/{$key}", ['option_value' => 'Updated'])
            ->assertStatus(200)
            ->assertJsonPath('data.option_value', 'Updated');

        $this->actingAs($this->adminUser, 'api')
            ->deleteJson("/api/module/settings/{$key}")
            ->assertStatus(200);

        $this->assertSame(0, DB::table('options')->where('option_key', $key)->count());
    }

    #[Test]
    public function cart_and_checkout_endpoints_are_public(): void
    {
        // Both endpoints are session-backed — anonymous callers should reach
        // the controller and get a 200 (or a 4xx from the service layer, but
        // never an auth failure).
        $this->getJson('/api/module/cart')->assertStatus(200);

        // Checkout returns 400 "Cart is empty" for fresh sessions — that's
        // still the controller talking, not auth middleware.
        $this->getJson('/api/module/checkout')->assertStatus(400)
            ->assertJson(['success' => false]);
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
            'api.module.media.index',
            'api.module.media.store',
            'api.module.media.show',
            'api.module.media.update',
            'api.module.media.destroy',
            'api.module.forms.index',
            'api.module.forms.store',
            'api.module.forms.show',
            'api.module.forms.update',
            'api.module.forms.destroy',
            'api.module.contact-form.index',
            'api.module.contact-form.store',
            'api.module.contact-form.show',
            'api.module.contact-form.update',
            'api.module.contact-form.destroy',
            'api.module.products.index',
            'api.module.products.store',
            'api.module.products.show',
            'api.module.products.update',
            'api.module.products.destroy',
            'api.module.categories.index',
            'api.module.categories.store',
            'api.module.categories.show',
            'api.module.categories.update',
            'api.module.categories.destroy',
            'api.module.orders.index',
            'api.module.orders.store',
            'api.module.orders.show',
            'api.module.orders.update',
            'api.module.orders.destroy',
            'api.module.coupons.index',
            'api.module.coupons.store',
            'api.module.coupons.show',
            'api.module.coupons.update',
            'api.module.coupons.destroy',
            'api.module.shipping.index',
            'api.module.shipping.store',
            'api.module.shipping.show',
            'api.module.shipping.update',
            'api.module.shipping.destroy',
            'api.module.tax.index',
            'api.module.tax.store',
            'api.module.tax.show',
            'api.module.tax.update',
            'api.module.tax.destroy',
            'api.module.invoices.index',
            'api.module.invoices.store',
            'api.module.invoices.show',
            'api.module.invoices.update',
            'api.module.invoices.destroy',
            'api.module.cart.index',
            'api.module.cart.store',
            'api.module.cart.totals',
            'api.module.cart.empty',
            'api.module.cart.coupon.apply',
            'api.module.cart.coupon.remove',
            'api.module.cart.update',
            'api.module.cart.destroy',
            'api.module.checkout.index',
            'api.module.checkout.store',
            'api.module.checkout.update',
            'api.module.checkout.validate',
            'api.module.checkout.shipping.methods',
            'api.module.checkout.payment.methods',
            'api.module.checkout.shipping.calculate',
            'api.module.checkout.order.status',
            'api.module.users.index',
            'api.module.users.store',
            'api.module.users.show',
            'api.module.users.update',
            'api.module.users.destroy',
            'api.module.customers.index',
            'api.module.customers.store',
            'api.module.customers.show',
            'api.module.customers.update',
            'api.module.customers.destroy',
            'api.module.profile.show',
            'api.module.profile.update',
            'api.module.profile.change-password',
            'api.module.newsletter.index',
            'api.module.newsletter.store',
            'api.module.newsletter.show',
            'api.module.newsletter.update',
            'api.module.newsletter.destroy',
            'api.module.newsletter.unsubscribe',
            'api.module.settings.index',
            'api.module.settings.show',
            'api.module.settings.store',
            'api.module.settings.update',
            'api.module.settings.destroy',
        ];

        foreach ($expected as $name) {
            $this->assertContains($name, $names->values()->all(),
                "Expected route name {$name} to be registered");
        }
    }
}
