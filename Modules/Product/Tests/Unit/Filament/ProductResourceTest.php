<?php

namespace Modules\Product\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Modules\Product\Filament\Admin\Resources\ProductResource;
use Modules\Product\Filament\Admin\Resources\ProductResource\Pages\ListProducts;
use Modules\Product\Filament\Admin\Resources\ProductResource\Pages\CreateProduct;
use Modules\Product\Filament\Admin\Resources\ProductResource\Pages\EditProduct;
use Modules\Product\Models\Product;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class ProductResourceTest extends TestCase
{
    use LazilyRefreshDatabase;
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListProducts::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $products = Product::factory()->count(3)->create();
        Livewire::test(ListProducts::class)->assertCanSeeTableRecords($products);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        Livewire::test(CreateProduct::class)
            ->fillForm([
                'title' => 'Test Product',
                'content_type' => 'product',
                'subtype' => 'product',
                'price' => 99.99,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('content', ['title' => 'Test Product', 'content_type' => 'product']);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $product = Product::factory()->create(['title' => 'Original']);
        Livewire::test(EditProduct::class, ['record' => $product->id])
            ->fillForm(['title' => 'Updated'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('content', ['id' => $product->id, 'title' => 'Updated']);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $product = Product::factory()->create();
        Livewire::test(ListProducts::class)->callTableAction('delete', $product);
        $this->assertDatabaseMissing('content', ['id' => $product->id]);
    }

    #[Test]
    public function it_pages_exist(): void
    {
        $pages = ProductResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    #[Test]
    public function it_can_set_product_inventory_fields(): void
    {
        Livewire::test(CreateProduct::class)
            ->fillForm([
                'title' => 'Test Product',
                'content_type' => 'product',
                'price' => 50.00,
                'content_data.sku' => 'SKU-123',
                'content_data.quantity' => 100,
                'content_data.track_quantity' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('content', ['title' => 'Test Product']);
    }

    #[Test]
    public function it_can_set_product_shipping_fields(): void
    {
        Livewire::test(CreateProduct::class)
            ->fillForm([
                'title' => 'Test Product',
                'content_type' => 'product',
                'price' => 50.00,
                'content_data.physical_product' => true,
                'content_data.weight' => 1.5,
                'content_data.width' => 10,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('content', ['title' => 'Test Product']);
    }
}
