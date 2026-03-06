<?php

namespace Modules\Comments\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Comments\Filament\Resources\CommentResource;
use Modules\Comments\Filament\Resources\CommentResource\Pages\ListComments;
use Modules\Comments\Filament\Resources\CommentResource\Pages\CreateComment;
use Modules\Comments\Filament\Resources\CommentResource\Pages\EditComment;
use Modules\Comments\Models\Comment;
use Modules\Content\Models\Content;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CommentResourceTest extends TestCase
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
        Livewire::test(ListComments::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $comments = Comment::factory()->count(3)->create();
        Livewire::test(ListComments::class)->assertCanSeeTableRecords($comments);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        $content = Content::factory()->create();

        Livewire::test(CreateComment::class)
            ->fillForm([
                'comment_name' => 'John Doe',
                'comment_email' => 'john@example.com',
                'comment_subject' => 'Test Subject',
                'comment_body' => 'Test comment body',
                'is_moderated' => false,
                'is_spam' => false,
                'rel_type' => morph_name(Content::class),
                'rel_id' => $content->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('comments', ['comment_name' => 'John Doe', 'comment_body' => 'Test comment body']);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $comment = Comment::factory()->create(['comment_name' => 'Original']);
        Livewire::test(EditComment::class, ['record' => $comment->id])
            ->fillForm(['comment_name' => 'Updated'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'comment_name' => 'Updated']);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $comment = Comment::factory()->create();
        Livewire::test(ListComments::class)->callTableAction('delete', $comment);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    #[Test]
    public function it_can_filter_by_is_moderated(): void
    {
        $approved = Comment::factory()->create(['is_moderated' => true]);
        $pending = Comment::factory()->create(['is_moderated' => false]);

        Livewire::test(ListComments::class)
            ->filterTable('is_moderated', true)
            ->assertCanSeeTableRecords([$approved])
            ->assertCanNotSeeTableRecords([$pending]);
    }

    #[Test]
    public function it_can_filter_by_is_spam(): void
    {
        $spam = Comment::factory()->create(['is_spam' => true]);
        $notSpam = Comment::factory()->create(['is_spam' => false]);

        Livewire::test(ListComments::class)
            ->filterTable('is_spam', true)
            ->assertCanSeeTableRecords([$spam])
            ->assertCanNotSeeTableRecords([$notSpam]);
    }

    #[Test]
    public function it_approve_action_exists(): void
    {
        $comment = Comment::factory()->create(['is_moderated' => false]);
        Livewire::test(ListComments::class)->assertTableActionExists('approve');
    }

    #[Test]
    public function it_spam_action_exists(): void
    {
        $comment = Comment::factory()->create();
        Livewire::test(ListComments::class)->assertTableActionExists('spam');
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListComments::class)
            ->assertTableColumnExists('comment_name')
            ->assertTableColumnExists('comment_email')
            ->assertTableColumnExists('comment_body')
            ->assertTableColumnExists('is_moderated')
            ->assertTableColumnExists('is_spam');
    }

    #[Test]
    public function it_sorting_by_column_changes_order(): void
    {
        // Create comments with different dates
        $commentA = Comment::factory()->create([
            'comment_name' => 'Alice',
            'created_at' => now()->subDays(5),
        ]);
        $commentB = Comment::factory()->create([
            'comment_name' => 'Bob',
            'created_at' => now()->subDays(3),
        ]);
        $commentC = Comment::factory()->create([
            'comment_name' => 'Charlie',
            'created_at' => now()->subDays(1),
        ]);

        // Test sorting by created_at descending
        Livewire::test(ListComments::class)
            ->sortTable('created_at', 'desc')
            ->assertCanSeeTableRecords([$commentC, $commentB, $commentA], inOrder: true);
    }

    #[Test]
    public function it_bulk_delete_removes_selected_records(): void
    {
        $comment1 = Comment::factory()->create(['comment_name' => 'Delete 1']);
        $comment2 = Comment::factory()->create(['comment_name' => 'Delete 2']);
        $comment3 = Comment::factory()->create(['comment_name' => 'Keep']);

        // Select and bulk delete first two comments
        Livewire::test(ListComments::class)
            ->callTableBulkAction('delete', [$comment1, $comment2])
            ->assertHasNoTableBulkActionErrors();

        // Assert deleted records are gone
        $this->assertDatabaseMissing('comments', ['id' => $comment1->id]);
        $this->assertDatabaseMissing('comments', ['id' => $comment2->id]);

        // Assert third comment still exists
        $this->assertDatabaseHas('comments', ['id' => $comment3->id]);
    }

    #[Test]
    public function it_bulk_approve_action_exists(): void
    {
        Livewire::test(ListComments::class)->assertTableBulkActionExists('approve');
    }

    #[Test]
    public function it_bulk_mark_as_spam_action_exists(): void
    {
        Livewire::test(ListComments::class)->assertTableBulkActionExists('mark_as_spam');
    }

    #[Test]
    public function it_global_search_returns_results(): void
    {
        $comment = Comment::factory()->create(['comment_name' => 'Searchable Comment']);
        $results = CommentResource::getGlobalSearchResults('Searchable');
        $this->assertNotEmpty($results);
    }
}
