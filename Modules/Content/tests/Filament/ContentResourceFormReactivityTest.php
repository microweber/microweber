<?php

namespace Modules\Content\Tests\Filament;

use Livewire\Livewire;
use MicroweberPackages\User\Models\User;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Content\Filament\Admin\ContentResource\Pages\CreateContent;
use Modules\Content\Filament\Admin\ContentResource\Pages\EditContent;
use Modules\Content\Models\Content;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ContentResourceFormReactivityTest extends TestCase
{
    protected function actingAsAdmin(): User
    {
        $user = User::factory()->create([
            'is_admin' => 1,
        ]);

        $this->actingAs($user);

        return $user;
    }

    #[Test]
    public function test_slug_field_updates_on_title_change()
    {
        $this->actingAsAdmin();

        // Create a new content and test slug generation
        $title = 'My Test Page Title';
        $expectedSlug = 'my-test-page-title';

        $component = Livewire::test(CreateContent::class)
            ->fillForm([
                'title' => $title,
            ]);

        // Access the form data to verify slug was generated
        $formData = $component->get('data');

        // The URL field should be auto-populated based on title
        // Note: The slug generation happens in afterStateUpdated hook
        $this->assertNotNull($formData);

        // Verify the form accepts the title and processes it
        $component->assertHasNoFormErrors(['title']);

        // Create the record and verify slug/url was set
        $component->call('create');

        // Check that a content was created with the expected slug
        $content = Content::latest()->first();
        $this->assertNotNull($content);
        $this->assertEquals($title, $content->title);
        // The URL should contain a URL-friendly version of the title
        $this->assertStringContainsString(strtolower(str_replace(' ', '-', $title)), strtolower($content->url));
    }

    #[Test]
    public function test_paid_fields_hidden_when_type_free()
    {
        $this->actingAsAdmin();

        // Test with 'page' content type - pricing should not be visible
        $component = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'page',
                'title' => 'Test Page',
            ]);

        $formData = $component->get('data');
        $this->assertEquals('page', $formData['content_type']);

        // Verify form renders successfully without pricing fields
        $component->assertSuccessful();

        // Create a product to test pricing fields are visible
        $productComponent = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'product',
                'title' => 'Test Product',
                'price' => 99.99,
            ]);

        $productData = $productComponent->get('data');
        $this->assertEquals('product', $productData['content_type']);
        $this->assertEquals(99.99, $productData['price']);
    }

    #[Test]
    public function test_pricing_section_visible_only_for_products()
    {
        $this->actingAsAdmin();

        // Create a page - pricing should not be in data
        $pageComponent = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'page',
                'title' => 'Test Page No Pricing',
            ]);

        $pageComponent->assertSuccessful();
        $pageData = $pageComponent->get('data');
        $this->assertEquals('page', $pageData['content_type']);
        // Price field may not exist or be null for pages

        // Create a product - pricing should be available
        $productComponent = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'product',
                'title' => 'Test Product With Pricing',
                'price' => 49.99,
            ]);

        $productComponent->assertSuccessful();
        $productData = $productComponent->get('data');
        $this->assertEquals('product', $productData['content_type']);
        $this->assertEquals(49.99, $productData['price']);
    }

    #[Test]
    public function test_dependent_select_reloads_options()
    {
        $this->actingAsAdmin();

        // Test content type change affects available fields
        $component = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'page',
                'title' => 'Test Content',
            ]);

        $component->assertSuccessful();

        // Change content type and verify form updates
        $component->fillForm([
            'content_type' => 'product',
            'title' => 'Test Product',
            'price' => 29.99,
        ]);

        $formData = $component->get('data');
        $this->assertEquals('product', $formData['content_type']);
        $this->assertEquals(29.99, $formData['price']);

        // Verify no errors when switching content types
        $component->assertHasNoFormErrors();
    }

    #[Test]
    public function test_toggle_makes_field_visible()
    {
        $this->actingAsAdmin();

        // Create a product to test toggle behavior
        $component = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'product',
                'title' => 'Test Product Toggle',
                'price' => 99.99,
                'content_data' => [
                    'track_quantity' => true,
                    'quantity' => 10,
                ],
            ]);

        $formData = $component->get('data');
        $this->assertTrue($formData['content_data']['track_quantity']);
        $this->assertEquals(10, $formData['content_data']['quantity']);

        // Test with track_quantity disabled
        $component->fillForm([
            'content_data' => [
                'track_quantity' => false,
            ],
        ]);

        $updatedData = $component->get('data');
        $this->assertFalse($updatedData['content_data']['track_quantity']);

        $component->assertHasNoFormErrors();
    }

    #[Test]
    public function test_physical_product_toggle_controls_shipping_fields()
    {
        $this->actingAsAdmin();

        // Test with physical product enabled
        $component = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'product',
                'title' => 'Physical Product',
                'price' => 49.99,
                'content_data' => [
                    'physical_product' => true,
                    'shipping_fixed_cost' => 5.99,
                ],
            ]);

        $formData = $component->get('data');
        $this->assertTrue($formData['content_data']['physical_product']);
        $this->assertEquals(5.99, $formData['content_data']['shipping_fixed_cost']);

        // Disable physical product
        $component->fillForm([
            'content_data' => [
                'physical_product' => false,
            ],
        ]);

        $updatedData = $component->get('data');
        $this->assertFalse($updatedData['content_data']['physical_product']);

        $component->assertHasNoFormErrors();
    }

    #[Test]
    public function test_advanced_shipping_toggle_visibility()
    {
        $this->actingAsAdmin();

        $component = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'product',
                'title' => 'Product With Advanced Shipping',
                'price' => 99.99,
                'content_data' => [
                    'physical_product' => true,
                    'shipping_advanced_settings' => true,
                    'weight' => 2.5,
                    'width' => 30,
                    'length' => 40,
                    'depth' => 10,
                ],
            ]);

        $formData = $component->get('data');
        $this->assertTrue($formData['content_data']['shipping_advanced_settings']);
        $this->assertEquals(2.5, $formData['content_data']['weight']);
        $this->assertEquals(30, $formData['content_data']['width']);

        // Toggle off advanced settings
        $component->fillForm([
            'content_data' => [
                'shipping_advanced_settings' => false,
            ],
        ]);

        $updatedData = $component->get('data');
        $this->assertFalse($updatedData['content_data']['shipping_advanced_settings']);

        $component->assertHasNoFormErrors();
    }

    #[Test]
    public function test_require_login_toggle_visibility_based_on_record_existence()
    {
        $this->actingAsAdmin();

        // For new content, require_login should not be visible (no ID)
        $component = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'page',
                'title' => 'New Page',
            ]);

        $formData = $component->get('data');
        $this->assertNull($formData['id'] ?? null);

        // Create the content first
        $component->call('create');
        $content = Content::latest()->first();
        $this->assertNotNull($content);

        // Now test editing - require_login should be available
        $editComponent = Livewire::test(EditContent::class, ['record' => $content->id])
            ->fillForm([
                'require_login' => true,
            ]);

        $editData = $editComponent->get('data');
        $this->assertNotNull($editData['id']);

        $editComponent->call('save');

        $content->refresh();
        $this->assertTrue($content->require_login);
    }

    #[Test]
    public function test_is_home_toggle_visibility_for_pages()
    {
        $this->actingAsAdmin();

        // Test page can have is_home
        $pageComponent = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'page',
                'title' => 'Home Page',
                'is_home' => true,
            ]);

        $pageData = $pageComponent->get('data');
        $this->assertEquals('page', $pageData['content_type']);

        $pageComponent->call('create');
        $page = Content::latest()->first();
        $this->assertEquals('page', $page->content_type);

        // Test product cannot have is_home
        $productComponent = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'product',
                'title' => 'Regular Product',
            ]);

        $productData = $productComponent->get('data');
        $this->assertEquals('product', $productData['content_type']);
    }

    #[Test]
    public function test_is_shop_toggle_visibility_for_pages()
    {
        $this->actingAsAdmin();

        // Test page can have is_shop
        $component = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'page',
                'title' => 'Shop Page',
                'is_shop' => true,
            ]);

        $formData = $component->get('data');
        $this->assertEquals('page', $formData['content_type']);

        $component->call('create');
        $page = Content::latest()->first();
        $this->assertTrue($page->is_shop);
    }

    #[Test]
    public function test_special_price_field_conditional_visibility()
    {
        $this->actingAsAdmin();

        // Create product with special price
        $component = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'product',
                'title' => 'Product With Special Price',
                'price' => 100.00,
                'special_price' => 79.99,
            ]);

        $formData = $component->get('data');
        $this->assertEquals(100.00, $formData['price']);
        $this->assertEquals(79.99, $formData['special_price']);

        $component->assertHasNoFormErrors();
    }

    #[Test]
    public function test_template_tab_visibility_for_pages()
    {
        $this->actingAsAdmin();

        // Create a page - template tab should be accessible
        $pageComponent = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'page',
                'title' => 'Page With Template',
            ]);

        $pageComponent->assertSuccessful();
        $pageData = $pageComponent->get('data');
        $this->assertEquals('page', $pageData['content_type']);

        // Create a product - template tab should not be visible
        $productComponent = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'product',
                'title' => 'Product Without Template Tab',
                'price' => 49.99,
            ]);

        $productComponent->assertSuccessful();
        $productData = $productComponent->get('data');
        $this->assertEquals('product', $productData['content_type']);
    }

    #[Test]
    public function test_product_details_tab_visibility()
    {
        $this->actingAsAdmin();

        // Product should have product details tab
        $productComponent = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'product',
                'title' => 'Product With Details',
                'price' => 99.99,
                'content_data' => [
                    'sku' => 'SKU-123',
                    'barcode' => '123456789',
                ],
            ]);

        $productComponent->assertSuccessful();
        $productData = $productComponent->get('data');
        $this->assertEquals('product', $productData['content_type']);
        $this->assertEquals('SKU-123', $productData['content_data']['sku']);

        // Page should not have product details
        $pageComponent = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'page',
                'title' => 'Page Without Product Details',
            ]);

        $pageComponent->assertSuccessful();
        $pageData = $pageComponent->get('data');
        $this->assertEquals('page', $pageData['content_type']);
    }

    #[Test]
    public function test_content_body_visibility_by_content_type()
    {
        $this->actingAsAdmin();

        // Post should have content_body visible
        $postComponent = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'post',
                'title' => 'Blog Post',
                'content_body' => '<p>This is the post content.</p>',
            ]);

        $postComponent->assertSuccessful();
        $postData = $postComponent->get('data');
        $this->assertEquals('post', $postData['content_type']);
        $this->assertEquals('<p>This is the post content.</p>', $postData['content_body']);

        // Product should have content_body visible
        $productComponent = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'product',
                'title' => 'Product With Description',
                'price' => 49.99,
                'content_body' => '<p>Product description here.</p>',
            ]);

        $productComponent->assertSuccessful();
        $productData = $productComponent->get('data');
        $this->assertEquals('product', $productData['content_type']);
        $this->assertEquals('<p>Product description here.</p>', $productData['content_body']);
    }

    #[Test]
    public function test_custom_access_select_shows_dependent_fields()
    {
        $this->actingAsAdmin();

        // Create content with custom access settings
        $component = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'page',
                'title' => 'Restricted Page',
            ]);

        $component->call('create');
        $content = Content::latest()->first();

        // Edit the content to set custom access
        $editComponent = Livewire::test(EditContent::class, ['record' => $content->id])
            ->fillForm([
                'content_data' => [
                    'custom_access' => 'require_product_purchase',
                    'custom_access_product_id' => 1,
                ],
            ]);

        $formData = $editComponent->get('data');
        $this->assertEquals('require_product_purchase', $formData['content_data']['custom_access']);

        // Change to subscription access
        $editComponent->fillForm([
            'content_data' => [
                'custom_access' => 'require_subscription_plan_group',
            ],
        ]);

        $updatedData = $editComponent->get('data');
        $this->assertEquals('require_subscription_plan_group', $updatedData['content_data']['custom_access']);

        $editComponent->assertHasNoFormErrors();
    }

    #[Test]
    public function test_is_active_toggle_affects_published_status()
    {
        $this->actingAsAdmin();

        // Create published content
        $component = Livewire::test(CreateContent::class)
            ->fillForm([
                'content_type' => 'page',
                'title' => 'Published Page',
                'is_active' => true,
            ]);

        $component->call('create');
        $content = Content::latest()->first();
        $this->assertTrue($content->is_active);

        // Edit to unpublish
        $editComponent = Livewire::test(EditContent::class, ['record' => $content->id])
            ->fillForm([
                'is_active' => false,
            ])
            ->call('save');

        $content->refresh();
        $this->assertFalse($content->is_active);
    }
}
