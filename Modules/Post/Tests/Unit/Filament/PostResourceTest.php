<?php

namespace Modules\Post\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Modules\Post\Filament\Admin\Resources\PostResource;
use Modules\Post\Filament\Admin\Resources\PostResource\Pages\ListPosts;
use Modules\Post\Filament\Admin\Resources\PostResource\Pages\CreatePost;
use Modules\Post\Filament\Admin\Resources\PostResource\Pages\EditPost;
use Modules\Post\Models\Post;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class PostResourceTest extends TestCase
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
        Livewire::test(ListPosts::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $posts = Post::factory()->count(3)->create();
        Livewire::test(ListPosts::class)->assertCanSeeTableRecords($posts);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        Livewire::test(CreatePost::class)
            ->fillForm([
                'title' => 'Test Post',
                'content_type' => 'post',
                'subtype' => 'post',
                'content_body' => '<p>Test content</p>',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('content', ['title' => 'Test Post', 'content_type' => 'post']);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $post = Post::factory()->create(['title' => 'Original']);
        Livewire::test(EditPost::class, ['record' => $post->id])
            ->fillForm(['title' => 'Updated'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('content', ['id' => $post->id, 'title' => 'Updated']);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $post = Post::factory()->create();
        Livewire::test(ListPosts::class)->callTableAction('delete', $post);
        $this->assertDatabaseMissing('content', ['id' => $post->id]);
    }

    #[Test]
    public function it_pages_exist(): void
    {
        $pages = PostResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }
}
