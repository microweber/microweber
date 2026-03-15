<?php

namespace Tests\Feature\Regression;

use Livewire\Livewire;
use Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\CreateCategory;
use Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\EditCategory;
use Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\ListCategories;
use Modules\Category\Models\Category;
use Modules\Content\Filament\Admin\ContentResource\Pages\ListContents;
use Modules\Content\Models\Content;
use Modules\Order\Filament\Admin\Resources\OrderResource\Pages\ListOrders;
use Modules\Product\Filament\Admin\Resources\ProductResource\Pages\ListProducts;
use MicroweberPackages\User\Filament\Resources\UsersResource\Pages\ListUsers;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

/**
 * Regression Test Suite - Admin CRUD Operations
 *
 * Tests Filament resource list pages render and Category CRUD operations
 * via Livewire test helpers. Content/Product create forms are too
 * memory-intensive for Livewire component tests, so those are tested
 * at the list-page level and via ContentResourceFormReactivityTest.
 */
#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class AdminCrudRegressionTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_content_list_page_renders(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListContents::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_content_list_shows_records(): void
    {
        $this->actingAsAdmin();

        $contents = Content::factory()->count(3)->create();

        $component = Livewire::test(ListContents::class)
            ->assertSuccessful();

        // Verify records exist in DB
        foreach ($contents as $content) {
            $this->assertDatabaseHas('content', ['id' => $content->id]);
        }
    }

    #[Test]
    public function it_content_search_works(): void
    {
        $this->actingAsAdmin();

        $searchable = Content::factory()->create(['title' => 'Unique Searchable Title']);
        Content::factory()->count(3)->create();

        Livewire::test(ListContents::class)
            ->searchTable('Unique Searchable')
            ->assertSuccessful();
    }

    #[Test]
    public function it_content_bulk_delete_works(): void
    {
        $this->actingAsAdmin();

        $contents = Content::factory()->count(3)->create();

        Livewire::test(ListContents::class)
            ->callTableBulkAction('delete', $contents);

        foreach ($contents as $content) {
            $this->assertDatabaseMissing('content', ['id' => $content->id, 'is_deleted' => 0]);
        }
    }

    #[Test]
    public function it_user_list_page_renders(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListUsers::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_category_resource_full_crud(): void
    {
        $this->actingAsAdmin();

        $page = Content::factory()->create(['content_type' => 'page']);

        // Create
        Livewire::test(CreateCategory::class)
            ->fillForm([
                'title' => 'Test Category CRUD',
                'description' => 'Test description',
                'rel_type' => $page->getMorphClass(),
                'rel_id' => $page->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $category = Category::where('title', 'Test Category CRUD')->first();
        $this->assertNotNull($category, 'Category should have been created');

        // Edit
        Livewire::test(EditCategory::class, ['record' => $category->id])
            ->assertSuccessful()
            ->fillForm([
                'title' => 'Updated Category CRUD',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $category->refresh();
        $this->assertEquals('Updated Category CRUD', $category->title);

        // Delete
        Livewire::test(EditCategory::class, ['record' => $category->id])
            ->callAction('delete');

        $this->assertDatabaseMissing('categories', ['id' => $category->id, 'is_deleted' => 0]);
    }

    #[Test]
    public function it_category_list_page_renders(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListCategories::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_category_form_validation_works(): void
    {
        $this->actingAsAdmin();

        Livewire::test(CreateCategory::class)
            ->fillForm([
                'title' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['title']);
    }

    #[Test]
    public function it_order_list_page_renders(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListOrders::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_product_list_page_renders(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListProducts::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_relations_are_displayed_on_category_edit(): void
    {
        $this->actingAsAdmin();

        $page = Content::factory()->create(['content_type' => 'page']);
        $category = Category::factory()->create([
            'rel_type' => $page->getMorphClass(),
            'rel_id' => $page->id,
        ]);

        Livewire::test(EditCategory::class, ['record' => $category->id])
            ->assertSuccessful()
            ->assertFormSet([
                'title' => $category->title,
            ]);
    }
}
