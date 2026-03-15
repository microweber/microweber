<?php

namespace Tests\Feature\Filament\Resources;

use Livewire\Livewire;
use Modules\Category\Filament\Admin\Resources\ShopCategoryResource;
use Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\ListShopCategories;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

#[RunTestsInSeparateProcesses]
class ShopCategoryResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return ShopCategoryResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListShopCategories::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(ShopCategoryResource::getModel());
    }
}
