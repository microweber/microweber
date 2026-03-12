<?php

namespace Modules\Tag\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Tag\Filament\Resources\TagGroupResource;
use Modules\Tag\Filament\Resources\TagGroupResource\Pages\ListTagGroups;
use Modules\Tag\Models\TagGroup;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TagGroupResourceTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
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
        Livewire::test(ListTagGroups::class)
            ->fillForm(['name' => 'New Group', 'slug' => 'new-group'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('tag_groups', ['name' => 'New Group']);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $group = TagGroup::factory()->create();
        Livewire::test(ListTagGroups::class)->callTableAction('delete', $group);
        $this->assertDatabaseMissing('tag_groups', ['id' => $group->id]);
    }
}
