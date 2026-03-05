<?php

namespace Modules\Tag\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Tag\Filament\Resources\TagResource;
use Modules\Tag\Filament\Resources\TagResource\Pages\ListTags;
use Modules\Tag\Filament\Resources\TagResource\Pages\CreateTag;
use Modules\Tag\Filament\Resources\TagResource\Pages\EditTag;
use Modules\Tag\Models\Tag;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TagResourceTest extends TestCase
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
        Livewire::test(ListTags::class)->assertSuccessful();
    }

    #[Test]
    public function test_index_page_shows_all_records(): void
    {
        $tags = Tag::factory()->count(3)->create();
        Livewire::test(ListTags::class)->assertCanSeeTableRecords($tags);
    }

    #[Test]
    public function test_index_page_supports_pagination(): void
    {
        Tag::factory()->count(15)->create();
        Livewire::test(ListTags::class)->assertSuccessful();
    }

    #[Test]
    public function test_index_page_supports_search(): void
    {
        $tag = Tag::factory()->create(['name' => 'Test Tag']);
        Livewire::test(ListTags::class)
            ->searchTable('Test Tag')
            ->assertCanSeeTableRecords([$tag]);
    }

    #[Test]
    public function test_create_page_renders_form(): void
    {
        Livewire::test(CreateTag::class)->assertSuccessful()->assertFormExists();
    }

    #[Test]
    public function test_create_page_validates_required_fields(): void
    {
        Livewire::test(CreateTag::class)
            ->fillForm(['name' => ''])
            ->call('create')
            ->assertHasFormErrors(['name']);
    }

    #[Test]
    public function test_create_page_saves_new_record(): void
    {
        Livewire::test(CreateTag::class)
            ->fillForm(['name' => 'New Tag', 'slug' => 'new-tag', 'suggest' => true])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('tags', ['name' => 'New Tag', 'slug' => 'new-tag']);
    }

    #[Test]
    public function test_edit_page_pre_fills_form_data(): void
    {
        $tag = Tag::factory()->create(['name' => 'Edit Tag']);
        Livewire::test(EditTag::class, ['record' => $tag->id])
            ->assertSuccessful()
            ->assertFormSet(['name' => 'Edit Tag']);
    }

    #[Test]
    public function test_edit_page_updates_record(): void
    {
        $tag = Tag::factory()->create(['name' => 'Original']);
        Livewire::test(EditTag::class, ['record' => $tag->id])
            ->fillForm(['name' => 'Updated'])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('tags', ['id' => $tag->id, 'name' => 'Updated']);
    }

    #[Test]
    public function test_delete_action_removes_record(): void
    {
        $tag = Tag::factory()->create();
        Livewire::test(ListTags::class)->callTableAction('delete', $tag);
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }

    #[Test]
    public function test_table_has_required_columns(): void
    {
        Livewire::test(ListTags::class)
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('slug')
            ->assertTableColumnExists('count')
            ->assertTableColumnExists('suggest');
    }
}
