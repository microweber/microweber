<?php

namespace Modules\Tag\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Tag\Filament\Resources\TaggedResource;
use Modules\Tag\Filament\Resources\TaggedResource\Pages\ListTagged;
use Modules\Tag\Models\Tagged;
use Modules\Tag\Models\Tag;
use Modules\Content\Models\Content;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TaggedResourceTest extends TestCase
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

        Livewire::test(ListTagged::class)
            ->fillForm([
                'taggable_id' => $content->id,
                'taggable_type' => 'content',
                'tag_name' => $tag->name,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('tagged', ['taggable_id' => $content->id, 'tag_name' => $tag->name]);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $tagged = Tagged::factory()->create();
        Livewire::test(ListTagged::class)->callTableAction('delete', $tagged);
        $this->assertDatabaseMissing('tagged', ['id' => $tagged->id]);
    }
}
