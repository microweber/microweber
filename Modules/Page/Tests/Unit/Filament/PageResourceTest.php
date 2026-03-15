<?php

namespace Modules\Page\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Modules\Page\Filament\Resources\PageResource;
use Modules\Page\Filament\Resources\PageResource\Pages\ListPages;
use Modules\Page\Models\Page;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class PageResourceTest extends TestCase
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
        Livewire::test(ListPages::class)->assertSuccessful();
    }

    #[Test]
    public function it_factory_creates_page(): void
    {
        $page = Page::factory()->create(['title' => 'UniqueTestPage']);

        $this->assertDatabaseHas('content', [
            'id' => $page->id,
            'title' => 'UniqueTestPage',
            'content_type' => 'page',
        ]);
    }

    #[Test]
    public function it_pages_exist(): void
    {
        $pages = PageResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    #[Test]
    public function it_has_correct_model(): void
    {
        $this->assertEquals(Page::class, PageResource::getModel());
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $page = Page::factory()->create();
        Livewire::test(ListPages::class)->callTableAction('delete', $page);
        $this->assertDatabaseMissing('content', ['id' => $page->id, 'is_deleted' => 0]);
    }
}
