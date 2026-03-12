<?php

namespace Modules\Content\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Content\Filament\Admin\ContentResource\Pages\ListContents;
use Modules\Content\Filament\Admin\ContentResource\Pages\CreateContent;
use Modules\Content\Filament\Admin\ContentResource\Pages\EditContent;
use Modules\Content\Models\Content;
use Modules\Category\Models\Category;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ContentResourceTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    protected function getResourceClass(): string
    {
        return ContentResource::class;
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListContents::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $contents = Content::factory()->count(3)->create();

        Livewire::test(ListContents::class)
            ->assertCanSeeTableRecords($contents);
    }

    #[Test]
    public function it_index_page_supports_pagination(): void
    {
        Content::factory()->count(15)->create();

        Livewire::test(ListContents::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_supports_search(): void
    {
        $content = Content::factory()->create([
            'title' => 'Test Content Search',
        ]);

        Livewire::test(ListContents::class)
            ->searchTable('Test Content')
            ->assertCanSeeTableRecords([$content]);
    }

    #[Test]
    public function it_create_page_renders_form(): void
    {
        Livewire::test(CreateContent::class)
            ->assertSuccessful()
            ->assertFormExists();
    }

    #[Test]
    public function it_create_page_validates_required_fields(): void
    {
        Livewire::test(CreateContent::class)
            ->fillForm([
                'title' => '',
                'content_type' => '',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'title',
            ]);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        Livewire::test(CreateContent::class)
            ->fillForm([
                'title' => 'Test Content',
                'content_type' => 'page',
                'subtype' => 'static',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('content', [
            'title' => 'Test Content',
            'content_type' => 'page',
            'subtype' => 'static',
        ]);
    }

    #[Test]
    public function it_edit_page_pre_fills_form_data(): void
    {
        $content = Content::factory()->create([
            'title' => 'Edit Test Content',
            'content_type' => 'page',
        ]);

        Livewire::test(EditContent::class, ['record' => $content->id])
            ->assertSuccessful()
            ->assertFormSet([
                'title' => 'Edit Test Content',
            ]);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $content = Content::factory()->create([
            'title' => 'Original Title',
            'content_type' => 'page',
        ]);

        Livewire::test(EditContent::class, ['record' => $content->id])
            ->fillForm([
                'title' => 'Updated Title',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('content', [
            'id' => $content->id,
            'title' => 'Updated Title',
        ]);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $content = Content::factory()->create();

        Livewire::test(ListContents::class)
            ->callTableAction('delete', $content);

        $this->assertDatabaseMissing('content', [
            'id' => $content->id,
        ]);
    }

    #[Test]
    public function it_can_filter_by_content_type(): void
    {
        $page = Content::factory()->create(['content_type' => 'page']);
        $post = Content::factory()->create(['content_type' => 'post']);

        Livewire::test(ListContents::class)
            ->filterTable('content_type', 'page')
            ->assertCanSeeTableRecords([$page])
            ->assertCanNotSeeTableRecords([$post]);
    }

    #[Test]
    public function it_can_filter_by_is_active(): void
    {
        $published = Content::factory()->create(['is_active' => 1]);
        $unpublished = Content::factory()->create(['is_active' => 0]);

        Livewire::test(ListContents::class)
            ->filterTable('is_active', 1)
            ->assertCanSeeTableRecords([$published])
            ->assertCanNotSeeTableRecords([$unpublished]);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListContents::class)
            ->assertTableColumnExists('title')
            ->assertTableColumnExists('price_display')
            ->assertTableColumnExists('is_active');
    }

    #[Test]
    public function it_can_create_product_content(): void
    {
        Livewire::test(CreateContent::class)
            ->fillForm([
                'title' => 'Test Product',
                'content_type' => 'product',
                'price' => '99.99',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('content', [
            'title' => 'Test Product',
            'content_type' => 'product',
        ]);
    }

    #[Test]
    public function it_global_search_returns_results(): void
    {
        $content = Content::factory()->create([
            'title' => 'Global Search Test',
        ]);

        $results = ContentResource::getGlobalSearchResults('Global Search');
        $this->assertNotEmpty($results);
    }

    #[Test]
    public function it_can_filter_by_category(): void
    {
        $category = Category::factory()->create();
        $content = Content::factory()->create();
        $content->categories()->attach($category->id);

        Livewire::test(ListContents::class)
            ->filterTable('category_id', $category->id)
            ->assertSuccessful();
    }
}
