<?php

namespace Modules\Content\Tests\Filament;

use Livewire\Livewire;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Content\Filament\Admin\ContentResource\Pages\CreateContent;
use Modules\Content\Filament\Admin\ContentResource\Pages\EditContent;
use Modules\Content\Filament\Admin\ContentResource\Pages\ListContents;
use Modules\Content\Models\Content;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class ContentResourceTest extends FilamentResourceTestCase
{
    private array $createdIds = [];

    protected function getResourceClass(): string
    {
        return ContentResource::class;
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
        Livewire::test(ListContents::class)->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertEquals(Content::class, ContentResource::getModel());
    }

    #[Test]
    public function it_can_create_content(): void
    {
        $this->actingAsAdmin();

        $component = Livewire::test(CreateContent::class)
            ->fillForm([
                'title' => 'Filament Test Page',
                'content_type' => 'page',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $content = Content::where('title', 'Filament Test Page')->first();
        $this->assertNotNull($content);
        $this->createdIds[] = $content->id;
        $this->assertEquals('page', $content->content_type);
    }

    #[Test]
    public function it_can_load_edit_page(): void
    {
        $this->actingAsAdmin();

        $content = new Content();
        $content->title = 'Filament Edit Test';
        $content->content_type = 'page';
        $content->is_active = true;
        $content->save();
        $this->createdIds[] = $content->id;

        Livewire::test(EditContent::class, ['record' => $content->id])
            ->assertSuccessful();
    }
}
