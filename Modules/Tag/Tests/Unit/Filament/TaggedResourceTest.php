<?php

namespace Modules\Tag\Tests\Unit\Filament;

use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Tag\Filament\Resources\TaggedResource;
use Modules\Tag\Filament\Resources\TaggedResource\Pages\CreateTagged;
use Modules\Tag\Filament\Resources\TaggedResource\Pages\ListTagged;
use Modules\Tag\Models\Tagged;
use Modules\Tag\Models\Tag;
use Modules\Content\Models\Content;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TaggedResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
        DB::table('tagging_tags')->delete();
        DB::table('tagging_tagged')->delete();
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListTagged::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $tagged = Tagged::factory()->count(3)->create();
        Livewire::test(ListTagged::class)->assertCanSeeTableRecords($tagged);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        $content = Content::factory()->create();
        $tag = Tag::factory()->create();

        Livewire::test(CreateTagged::class)
            ->fillForm([
                'taggable_id' => $content->id,
                'taggable_type' => 'content',
                'tag_name' => $tag->name,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('tagging_tagged', ['taggable_id' => $content->id, 'tag_name' => $tag->name]);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $tagged = Tagged::factory()->create();
        Livewire::test(ListTagged::class)->callTableAction('delete', $tagged);
        $this->assertDatabaseMissing('tagging_tagged', ['id' => $tagged->id]);
    }
}
