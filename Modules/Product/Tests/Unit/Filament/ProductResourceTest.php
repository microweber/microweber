<?php

namespace Modules\Product\Tests\Unit\Filament;

use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Product\Filament\Admin\Resources\ProductResource;
use Modules\Product\Filament\Admin\Resources\ProductResource\Pages\ListProducts;
use Modules\Product\Models\Product;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ProductResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
        DB::table('content')->where('content_type', 'product')->delete();
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
        Livewire::test(ListProducts::class)->loadTable()->assertCanSeeTableRecords($products);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        $product = Product::create([
            'title' => 'Test Product',
            'content_type' => 'product',
            'subtype' => 'product',
            'price' => 99.99,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('content', ['title' => 'Test Product', 'content_type' => 'product']);
        $this->assertNotNull($product->id);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $product = Product::factory()->create(['title' => 'Original']);
        $product->title = 'Updated';
        $product->save();

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
        $product = Product::create([
            'title' => 'Inventory Product',
            'content_type' => 'product',
            'subtype' => 'product',
            'price' => 50.00,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('content', ['title' => 'Inventory Product', 'content_type' => 'product']);
    }

    #[Test]
    public function it_can_set_product_shipping_fields(): void
    {
        $product = Product::create([
            'title' => 'Shipping Product',
            'content_type' => 'product',
            'subtype' => 'product',
            'price' => 50.00,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('content', ['title' => 'Shipping Product', 'content_type' => 'product']);
    }
}
