<?php

namespace Modules\Category\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Category\Filament\Admin\Resources\ShopCategoryResource;
use Modules\Category\Filament\Admin\Resources\ShopCategoryResource\Pages\ListShopCategories;
use Modules\Category\Models\Category;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ShopCategoryResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsAdmin();
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    protected function actingAsAdmin(): User
    {
        $user = User::factory()->create(['is_admin' => 1]);
        $this->actingAs($user);
        return $user;
    }

    #[Test]
    public function test_index_page_loads_without_errors(): void
    {
        Livewire::test(ListShopCategories::class)->assertSuccessful();
    }

    #[Test]
    public function test_pages_exist(): void
    {
        $pages = ShopCategoryResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }
}
