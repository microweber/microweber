<?php

namespace Modules\Page\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Page\Filament\Resources\PageResource;
use Modules\Page\Filament\Resources\PageResource\Pages\ListPages;
use Modules\Page\Filament\Resources\PageResource\Pages\CreatePage;
use Modules\Page\Filament\Resources\PageResource\Pages\EditPage;
use Modules\Page\Models\Page;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PageResourceTest extends TestCase
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
        Livewire::test(ListPages::class)->assertSuccessful();
    }

    #[Test]
    public function test_index_page_shows_all_records(): void
    {
        $pages = Page::factory()->count(3)->create();
        Livewire::test(ListPages::class)->assertCanSeeTableRecords($pages);
    }

    #[Test]
    public function test_create_page_saves_new_record(): void
    {
        Livewire::test(CreatePage::class)
            ->fillForm([
                'title' => 'Test Page',
                'content_type' => 'page',
                'subtype' => 'page',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('content', ['title' => 'Test Page', 'content_type' => 'page']);
    }

    #[Test]
    public function test_edit_page_updates_record(): void
    {
        $page = Page::factory()->create(['title' => 'Original']);
        Livewire::test(EditPage::class, ['record' => $page->id])
            ->fillForm(['title' => 'Updated'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('content', ['id' => $page->id, 'title' => 'Updated']);
    }

    #[Test]
    public function test_delete_action_removes_record(): void
    {
        $page = Page::factory()->create();
        Livewire::test(ListPages::class)->callTableAction('delete', $page);
        $this->assertDatabaseMissing('content', ['id' => $page->id]);
    }

    #[Test]
    public function test_pages_exist(): void
    {
        $pages = PageResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    #[Test]
    public function test_sorting_by_column_changes_order(): void
    {
        // Create pages with different titles and positions for sorting
        $pageA = Page::factory()->create([
            'title' => 'Alpha Page',
            'position' => 3,
            'created_at' => now()->subDays(5),
        ]);
        $pageB = Page::factory()->create([
            'title' => 'Beta Page',
            'position' => 2,
            'created_at' => now()->subDays(3),
        ]);
        $pageC = Page::factory()->create([
            'title' => 'Charlie Page',
            'position' => 1,
            'created_at' => now()->subDays(1),
        ]);

        // Test sorting by title ascending
        Livewire::test(ListPages::class)
            ->sortTable('title', 'asc')
            ->assertCanSeeTableRecords([$pageA, $pageB, $pageC], inOrder: true);

        // Test sorting by position ascending (default)
        Livewire::test(ListPages::class)
            ->sortTable('position', 'asc')
            ->assertCanSeeTableRecords([$pageC, $pageB, $pageA], inOrder: true);

        // Test sorting by created_at descending
        Livewire::test(ListPages::class)
            ->sortTable('created_at', 'desc')
            ->assertCanSeeTableRecords([$pageC, $pageB, $pageA], inOrder: true);
    }

    #[Test]
    public function test_filter_by_boolean_field(): void
    {
        // Create pages with different active statuses
        $activePage = Page::factory()->create([
            'title' => 'Active Page',
            'is_active' => 1,
        ]);
        $inactivePage = Page::factory()->create([
            'title' => 'Inactive Page',
            'is_active' => 0,
        ]);

        // Filter by active status
        Livewire::test(ListPages::class)
            ->filterTable('is_active', 1)
            ->assertCanSeeTableRecords([$activePage])
            ->assertCanNotSeeTableRecords([$inactivePage]);

        // Filter by inactive status
        Livewire::test(ListPages::class)
            ->filterTable('is_active', 0)
            ->assertCanSeeTableRecords([$inactivePage])
            ->assertCanNotSeeTableRecords([$activePage]);
    }

    #[Test]
    public function test_bulk_delete_removes_selected_records(): void
    {
        $page1 = Page::factory()->create(['title' => 'Page 1']);
        $page2 = Page::factory()->create(['title' => 'Page 2']);
        $page3 = Page::factory()->create(['title' => 'Page 3']);

        // Select and bulk delete first two pages
        Livewire::test(ListPages::class)
            ->callTableBulkAction('delete', [$page1, $page2])
            ->assertHasNoTableBulkActionErrors();

        // Assert deleted records are gone
        $this->assertDatabaseMissing('content', ['id' => $page1->id]);
        $this->assertDatabaseMissing('content', ['id' => $page2->id]);

        // Assert third page still exists
        $this->assertDatabaseHas('content', ['id' => $page3->id]);
    }
}
