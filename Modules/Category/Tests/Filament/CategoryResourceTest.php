<?php

namespace Modules\Category\Tests\Filament;

use Livewire\Livewire;
use Modules\Category\Filament\Admin\Resources\CategoryResource;
use Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\ListCategories;
use Modules\Category\Filament\Admin\Resources\ShopCategoryResource;
use Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\ListShopCategories;
use Tests\Feature\Filament\FilamentResourceTestCase;
use PHPUnit\Framework\Attributes\Test;

class CategoryResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return CategoryResource::class;
    }

    #[Test]
    public function it_can_render_categories_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListCategories::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_shop_categories_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListShopCategories::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_non_admin_cannot_access_categories(): void
    {
        $this->actingAsUser();

        $response = $this->get(CategoryResource::getUrl('index'));
        $response->assertForbidden();
    }
}
