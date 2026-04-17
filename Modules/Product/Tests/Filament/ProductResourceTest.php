<?php

namespace Modules\Product\Tests\Filament;

use Livewire\Livewire;
use Modules\Content\Models\Content;
use Modules\Product\Filament\Admin\Resources\ProductResource;
use Modules\Product\Filament\Admin\Resources\ProductResource\Pages\ListProducts;
use Modules\Product\Filament\Admin\Resources\ProductInventoryResource;
use Modules\Product\Filament\Admin\Resources\ProductInventoryResource\Pages\ListProductInventory;
use Modules\Product\Filament\Admin\Resources\ProductPricingRuleResource;
use Modules\Product\Filament\Admin\Resources\ProductPricingRuleResource\Pages\ListProductPricingRules;
use Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource;
use Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource\Pages\ListProductVariantAttributes;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class ProductResourceTest extends FilamentResourceTestCase
{
    private array $createdIds = [];

    protected function getResourceClass(): string
    {
        return ProductResource::class;
    }

    protected function tearDown(): void
    {
        foreach ($this->createdIds as $id) {
            Content::where('id', $id)->delete();
        }
        parent::tearDown();
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListProducts::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_inventory_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListProductInventory::class)->assertSuccessful();
    }

    #[Test]
    public function it_pricing_rules_resource_exists(): void
    {
        $this->assertTrue(class_exists(ProductPricingRuleResource::class));
        $this->assertNotNull(ProductPricingRuleResource::getModel());
    }

    #[Test]
    public function it_can_render_variant_attributes_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListProductVariantAttributes::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_create_and_cleanup_product(): void
    {
        $this->actingAsAdmin();

        $content = new Content();
        $content->title = 'Filament Product Test';
        $content->content_type = 'product';
        $content->is_active = true;
        $content->save();
        $this->createdIds[] = $content->id;

        $this->assertDatabaseHas('content', ['id' => $content->id, 'content_type' => 'product']);
    }
}
