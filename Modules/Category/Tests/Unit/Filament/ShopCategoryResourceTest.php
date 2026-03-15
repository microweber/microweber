<?php

namespace Modules\Category\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Modules\Category\Filament\Admin\Resources\ShopCategoryResource;
use Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\ListShopCategories;
use Modules\Category\Models\Category;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class ShopCategoryResourceTest extends TestCase
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
        Livewire::test(ListShopCategories::class)->assertSuccessful();
    }

    #[Test]
    public function it_pages_exist(): void
    {
        $pages = ShopCategoryResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }
}
