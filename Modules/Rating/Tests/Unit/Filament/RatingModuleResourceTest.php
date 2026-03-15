<?php

namespace Modules\Rating\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Modules\Rating\Filament\Resources\RatingModuleResource;
use Modules\Rating\Filament\Resources\RatingModuleResource\Pages\ListRatings;
use Modules\Rating\Filament\Resources\RatingModuleResource\Pages\CreateRating;
use Modules\Rating\Filament\Resources\RatingModuleResource\Pages\EditRating;
use Modules\Rating\Models\Rating;
use Modules\Content\Models\Content;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class RatingModuleResourceTest extends TestCase
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
        Livewire::test(ListRatings::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $ratings = Rating::factory()->count(3)->create();
        Livewire::test(ListRatings::class)->assertCanSeeTableRecords($ratings);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        $content = Content::factory()->create();

        Livewire::test(CreateRating::class)
            ->fillForm([
                'rel_type' => 'content',
                'rel_id' => $content->id,
                'rating' => 5,
                'comment' => 'Great!',
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('ratings', ['rel_id' => $content->id, 'rating' => 5]);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $rating = Rating::factory()->create(['rating' => 3]);
        Livewire::test(EditRating::class, ['record' => $rating->id])
            ->fillForm(['rating' => 4])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('ratings', ['id' => $rating->id, 'rating' => 4]);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $rating = Rating::factory()->create();
        Livewire::test(ListRatings::class)->callTableAction('delete', $rating);
        $this->assertDatabaseMissing('ratings', ['id' => $rating->id]);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListRatings::class)
            ->assertTableColumnExists('rel_type')
            ->assertTableColumnExists('rel_id')
            ->assertTableColumnExists('rating')
            ->assertTableColumnExists('comment');
    }

    #[Test]
    public function it_rating_value_is_validated(): void
    {
        Livewire::test(CreateRating::class)
            ->fillForm([
                'rel_type' => 'content',
                'rel_id' => 1,
                'rating' => 6,
            ])
            ->call('create')
            ->assertHasFormErrors(['rating']);
    }
}
