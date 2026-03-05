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
}
