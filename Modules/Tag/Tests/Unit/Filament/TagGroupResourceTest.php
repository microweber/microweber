<?php

namespace Modules\Tag\Tests\Unit\Filament;

use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Tag\Filament\Resources\TagGroupResource;
use Modules\Tag\Filament\Resources\TagGroupResource\Pages\CreateTagGroup;
use Modules\Tag\Filament\Resources\TagGroupResource\Pages\ListTagGroups;
use Modules\Tag\Models\TagGroup;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TagGroupResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
        DB::table('tagging_tags')->delete();
        DB::table('tagging_tagged')->delete();
        DB::table('tagging_tag_groups')->delete();
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListTagGroups::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $groups = TagGroup::factory()->count(3)->create();
        Livewire::test(ListTagGroups::class)->assertCanSeeTableRecords($groups);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        Livewire::test(CreateTagGroup::class)
            ->fillForm(['name' => 'New Group'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('tagging_tag_groups', ['name' => 'New Group']);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $group = TagGroup::factory()->create();
        Livewire::test(ListTagGroups::class)->callTableAction('delete', $group);
        $this->assertDatabaseMissing('tagging_tag_groups', ['id' => $group->id]);
    }
}
