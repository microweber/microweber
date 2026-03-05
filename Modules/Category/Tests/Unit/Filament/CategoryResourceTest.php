<?php

namespace Modules\Category\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Category\Filament\Admin\Resources\CategoryResource;
use Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\ListCategories;
use Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\CreateCategory;
use Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\EditCategory;
use Modules\Category\Models\Category;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CategoryResourceTest extends TestCase
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
        Livewire::test(ListCategories::class)->assertSuccessful();
    }

    #[Test]
    public function test_index_page_shows_all_records(): void
    {
        $categories = Category::factory()->count(3)->create();
        Livewire::test(ListCategories::class)->assertCanSeeTableRecords($categories);
    }

    #[Test]
    public function test_create_page_saves_new_record(): void
    {
        Livewire::test(CreateCategory::class)
            ->fillForm([
                'title' => 'Test Category',
                'url' => 'test-category',
                'description' => 'Test description',
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('categories', ['title' => 'Test Category']);
    }

    #[Test]
    public function test_edit_page_updates_record(): void
    {
        $category = Category::factory()->create(['title' => 'Original']);
        Livewire::test(EditCategory::class, ['record' => $category->id])
            ->fillForm(['title' => 'Updated'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'title' => 'Updated']);
    }

    #[Test]
    public function test_delete_action_removes_record(): void
    {
        $category = Category::factory()->create();
        Livewire::test(ListCategories::class)->callTableAction('delete', $category);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    #[Test]
    public function test_table_has_required_columns(): void
    {
        Livewire::test(ListCategories::class)
            ->assertTableColumnExists('title')
            ->assertTableColumnExists('url');
    }

    #[Test]
    public function test_global_search_returns_results(): void
    {
        $category = Category::factory()->create(['title' => 'Searchable Category']);
        $results = CategoryResource::getGlobalSearchResults('Searchable');
        $this->assertNotEmpty($results);
    }
}
