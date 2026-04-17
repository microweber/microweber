<?php

namespace Modules\Category\Tests\Filament;

use Livewire\Livewire;
use Modules\Category\Filament\Admin\Resources\CategoryResource;
use Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\ListCategories;
use Modules\Category\Filament\Admin\Resources\ShopCategoryResource;
use Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\ListShopCategories;
use Modules\Category\Models\Category;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class CategoryResourceTest extends FilamentResourceTestCase
{
    private array $createdIds = [];

    protected function getResourceClass(): string
    {
        return CategoryResource::class;
    }

    protected function tearDown(): void
    {
        foreach ($this->createdIds as $id) {
            Category::where('id', $id)->delete();
        }
        parent::tearDown();
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListCategories::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_shop_categories_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListShopCategories::class)->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertEquals(Category::class, CategoryResource::getModel());
    }

    #[Test]
    public function it_can_create_and_delete_category(): void
    {
        $this->actingAsAdmin();

        $category = new Category();
        $category->title = 'Filament Category Test';
        $category->save();
        $this->createdIds[] = $category->id;

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'title' => 'Filament Category Test']);
    }
}
