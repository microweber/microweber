<?php

namespace Modules\Page\Tests\Filament;

use Livewire\Livewire;
use Modules\Content\Models\Content;
use Modules\Page\Filament\Resources\PageResource;
use Modules\Page\Filament\Resources\PageResource\Pages\ListPages;
use Modules\Page\Filament\Resources\PageResource\Pages\CreatePage;
use Modules\Page\Filament\Resources\PageResource\Pages\EditPage;
use Modules\Page\Models\Page;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class PageResourceTest extends FilamentResourceTestCase
{
    private array $createdIds = [];

    protected function getResourceClass(): string
    {
        return PageResource::class;
    }

    protected function tearDown(): void
    {
        foreach ($this->createdIds as $id) {
            Content::where('id', $id)->delete();
        }
        parent::tearDown();
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListPages::class)->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertEquals(Page::class, PageResource::getModel());
    }

    #[Test]
    public function it_can_create_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(CreatePage::class)
            ->fillForm([
                'title' => 'Filament Test Page Entry',
                'content_type' => 'page',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $page = Content::where('title', 'Filament Test Page Entry')->first();
        $this->assertNotNull($page);
        $this->createdIds[] = $page->id;
    }

    #[Test]
    public function it_can_load_edit_page(): void
    {
        $this->actingAsAdmin();

        $page = new Content();
        $page->title = 'Filament Page Edit Test';
        $page->content_type = 'page';
        $page->is_active = true;
        $page->save();
        $this->createdIds[] = $page->id;

        Livewire::test(EditPage::class, ['record' => $page->id])
            ->assertSuccessful();
    }
}
