<?php

namespace Tests\Feature\Regression;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Full Regression Test Suite - Admin CRUD Operations
 *
 * Comprehensive testing of all Filament resource CRUD operations
 * to ensure zero regressions in the admin panel.
 *
 * @covers \App\Filament\Resources
 */
class AdminCrudRegressionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    /**
     * Test that all registered Filament resources are accessible
     */
    #[Test]
    public function it_all_filament_resources_are_accessible(): void
    {
        $this->actingAs($this->admin);

        $resources = $this->getRegisteredResources();

        foreach ($resources as $resource) {
            $response = $this->get('/admin/' . $resource['slug']);
            $response->assertStatus(200);
        }
    }

    /**
     * Test Content resource CRUD operations
     */
    #[Test]
    public function it_content_resource_full_crud(): void
    {
        $this->actingAs($this->admin);

        // Create
        $createResponse = $this->post('/admin/contents', [
            'title' => 'Test Content',
            'content_body' => 'Test content body',
            'content_type' => 'page',
            'is_active' => true,
        ]);
        $createResponse->assertRedirect();

        $content = \Modules\Content\Models\Content::where('title', 'Test Content')->first();
        $this->assertNotNull($content);

        // Read
        $readResponse = $this->get('/admin/contents/' . $content->id . '/edit');
        $readResponse->assertStatus(200);

        // Update
        $updateResponse = $this->put('/admin/contents/' . $content->id, [
            'title' => 'Updated Content',
            'content_body' => 'Updated content body',
        ]);
        $updateResponse->assertRedirect();

        $content->refresh();
        $this->assertEquals('Updated Content', $content->title);

        // Delete
        $deleteResponse = $this->delete('/admin/contents/' . $content->id);
        $deleteResponse->assertRedirect();

        $this->assertNull(\Modules\Content\Models\Content::find($content->id));
    }

    /**
     * Test User resource CRUD operations
     */
    #[Test]
    public function it_user_resource_full_crud(): void
    {
        $this->actingAs($this->admin);

        // Create
        $createResponse = $this->post('/admin/users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $createResponse->assertRedirect();

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);

        // Read
        $readResponse = $this->get('/admin/users/' . $user->id . '/edit');
        $readResponse->assertStatus(200);

        // Update
        $updateResponse = $this->put('/admin/users/' . $user->id, [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
        ]);
        $updateResponse->assertRedirect();

        $user->refresh();
        $this->assertEquals('Updated User', $user->name);

        // Delete
        $deleteResponse = $this->delete('/admin/users/' . $user->id);
        $deleteResponse->assertRedirect();

        $this->assertNull(User::find($user->id));
    }

    /**
     * Test Category resource CRUD operations
     */
    #[Test]
    public function it_category_resource_full_crud(): void
    {
        $this->actingAs($this->admin);

        // Create
        $createResponse = $this->post('/admin/categories', [
            'title' => 'Test Category',
            'description' => 'Test description',
        ]);
        $createResponse->assertRedirect();

        $category = \Modules\Category\Models\Category::where('title', 'Test Category')->first();
        $this->assertNotNull($category);

        // Read
        $readResponse = $this->get('/admin/categories/' . $category->id . '/edit');
        $readResponse->assertStatus(200);

        // Update
        $updateResponse = $this->put('/admin/categories/' . $category->id, [
            'title' => 'Updated Category',
        ]);
        $updateResponse->assertRedirect();

        $category->refresh();
        $this->assertEquals('Updated Category', $category->title);

        // Delete
        $deleteResponse = $this->delete('/admin/categories/' . $category->id);
        $deleteResponse->assertRedirect();

        $this->assertNull(\Modules\Category\Models\Category::find($category->id));
    }

    /**
     * Test Order resource CRUD operations
     */
    #[Test]
    public function it_order_resource_full_crud(): void
    {
        $this->actingAs($this->admin);

        $customer = User::factory()->create();

        // Create
        $createResponse = $this->post('/admin/orders', [
            'customer_id' => $customer->id,
            'order_status' => 'pending',
            'amount' => 100.00,
            'currency' => 'USD',
        ]);
        $createResponse->assertRedirect();

        $order = \Modules\Order\Models\Order::where('customer_id', $customer->id)->first();
        $this->assertNotNull($order);

        // Read
        $readResponse = $this->get('/admin/orders/' . $order->id . '/edit');
        $readResponse->assertStatus(200);

        // Update
        $updateResponse = $this->put('/admin/orders/' . $order->id, [
            'order_status' => 'completed',
        ]);
        $updateResponse->assertRedirect();

        $order->refresh();
        $this->assertEquals('completed', $order->order_status);

        // Delete
        $deleteResponse = $this->delete('/admin/orders/' . $order->id);
        $deleteResponse->assertRedirect();

        $this->assertNull(\Modules\Order\Models\Order::find($order->id));
    }

    /**
     * Test Product resource CRUD operations
     */
    #[Test]
    public function it_product_resource_full_crud(): void
    {
        $this->actingAs($this->admin);

        // Create
        $createResponse = $this->post('/admin/products', [
            'title' => 'Test Product',
            'price' => 99.99,
            'sku' => 'TEST-001',
            'content_type' => 'product',
        ]);
        $createResponse->assertRedirect();

        $product = \Modules\Product\Models\Product::where('sku', 'TEST-001')->first();
        $this->assertNotNull($product);

        // Read
        $readResponse = $this->get('/admin/products/' . $product->id . '/edit');
        $readResponse->assertStatus(200);

        // Update
        $updateResponse = $this->put('/admin/products/' . $product->id, [
            'title' => 'Updated Product',
            'price' => 149.99,
        ]);
        $updateResponse->assertRedirect();

        $product->refresh();
        $this->assertEquals('Updated Product', $product->title);

        // Delete
        $deleteResponse = $this->delete('/admin/products/' . $product->id);
        $deleteResponse->assertRedirect();

        $this->assertNull(\Modules\Product\Models\Product::find($product->id));
    }

    /**
     * Test that bulk actions work on list pages
     */
    #[Test]
    public function it_bulk_delete_action_works(): void
    {
        $this->actingAs($this->admin);

        $contents = \Modules\Content\Models\Content::factory()->count(3)->create();
        $ids = $contents->pluck('id')->toArray();

        $response = $this->delete('/admin/contents/bulk-delete', [
            'ids' => $ids,
        ]);

        $response->assertRedirect();

        foreach ($ids as $id) {
            $this->assertNull(\Modules\Content\Models\Content::find($id));
        }
    }

    /**
     * Test that pagination works correctly
     */
    #[Test]
    public function it_pagination_works_on_resource_list_pages(): void
    {
        $this->actingAs($this->admin);

        \Modules\Content\Models\Content::factory()->count(25)->create();

        $response = $this->get('/admin/contents');
        $response->assertStatus(200);
        $response->assertSee('Next');
    }

    /**
     * Test that search functionality works
     */
    #[Test]
    public function it_search_functionality_works_on_resources(): void
    {
        $this->actingAs($this->admin);

        \Modules\Content\Models\Content::factory()->create(['title' => 'Unique Searchable Title']);
        \Modules\Content\Models\Content::factory()->count(5)->create();

        $response = $this->get('/admin/contents?search=Unique+Searchable');
        $response->assertStatus(200);
        $response->assertSee('Unique Searchable Title');
    }

    /**
     * Test that filtering works correctly
     */
    #[Test]
    public function it_filtering_works_on_resource_list_pages(): void
    {
        $this->actingAs($this->admin);

        \Modules\Content\Models\Content::factory()->create(['content_type' => 'page', 'title' => 'Page Content']);
        \Modules\Content\Models\Content::factory()->create(['content_type' => 'post', 'title' => 'Post Content']);

        $response = $this->get('/admin/contents?content_type=page');
        $response->assertStatus(200);
        $response->assertSee('Page Content');
    }

    /**
     * Test that form validation works correctly
     */
    #[Test]
    public function it_form_validation_works_on_resource_create(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/contents', [
            'title' => '', // Required field
        ]);

        $response->assertSessionHasErrors('title');
    }

    /**
     * Test that relations are displayed correctly
     */
    #[Test]
    public function it_relations_are_displayed_on_resource_edit(): void
    {
        $this->actingAs($this->admin);

        $category = \Modules\Category\Models\Category::factory()->create();
        $content = \Modules\Content\Models\Content::factory()->create();
        $content->categories()->attach($category->id);

        $response = $this->get('/admin/contents/' . $content->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee($category->title);
    }

    /**
     * Get all registered Filament resources
     */
    private function getRegisteredResources(): array
    {
        return [
            ['slug' => 'contents', 'name' => 'Content'],
            ['slug' => 'users', 'name' => 'User'],
            ['slug' => 'categories', 'name' => 'Category'],
            ['slug' => 'orders', 'name' => 'Order'],
            ['slug' => 'products', 'name' => 'Product'],
            ['slug' => 'comments', 'name' => 'Comment'],
            ['slug' => 'invoices', 'name' => 'Invoice'],
            ['slug' => 'coupons', 'name' => 'Coupon'],
            ['slug' => 'pages', 'name' => 'Page'],
            ['slug' => 'mail-templates', 'name' => 'Mail Template'],
            ['slug' => 'translations', 'name' => 'Translation'],
            ['slug' => 'tags', 'name' => 'Tag'],
            ['slug' => 'tag-groups', 'name' => 'Tag Group'],
            ['slug' => 'tagged', 'name' => 'Tagged'],
            ['slug' => 'faqs', 'name' => 'FAQ'],
            ['slug' => 'ratings', 'name' => 'Rating'],
            ['slug' => 'checkouts', 'name' => 'Checkout'],
            ['slug' => 'backups', 'name' => 'Backup'],
            ['slug' => 'agent-chats', 'name' => 'Agent Chat'],
            ['slug' => 'subscriptions', 'name' => 'Subscription'],
            ['slug' => 'subscription-plans', 'name' => 'Subscription Plan'],
            ['slug' => 'customers', 'name' => 'Customer'],
            ['slug' => 'payment-providers', 'name' => 'Payment Provider'],
            ['slug' => 'shipping-providers', 'name' => 'Shipping Provider'],
            ['slug' => 'marketplaces', 'name' => 'Marketplace'],
        ];
    }
}
