<?php

namespace Modules\Product\Tests\Filament;

use Livewire\Livewire;
use Modules\Product\Filament\Admin\Resources\ProductResource;
use Modules\Product\Filament\Admin\Resources\ProductResource\Pages\ListProducts;
use Modules\Product\Filament\Admin\Resources\ProductInventoryResource;
use Modules\Product\Filament\Admin\Resources\ProductInventoryResource\Pages\ListProductInventory;
use Tests\Feature\Filament\FilamentResourceTestCase;
use PHPUnit\Framework\Attributes\Test;

class ProductResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return ProductResource::class;
    }

    #[Test]
    public function it_can_render_products_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListProducts::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_inventory_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListProductInventory::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_non_admin_cannot_access_products(): void
    {
        $this->actingAsUser();

        $response = $this->get(ProductResource::getUrl('index'));
        $response->assertForbidden();
    }
}
