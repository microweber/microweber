<?php

namespace Modules\Post\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Modules\Post\Filament\Admin\Resources\PostResource;
use Modules\Post\Filament\Admin\Resources\PostResource\Pages\ListPosts;
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
    public function it_factory_creates_post(): void
    {
        $post = Post::factory()->create(['title' => 'UniqueTestPost']);

        $this->assertDatabaseHas('content', [
            'id' => $post->id,
            'title' => 'UniqueTestPost',
            'content_type' => 'post',
        ]);
    }

    #[Test]
    public function it_pages_exist(): void
    {
        $pages = PostResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    #[Test]
    public function it_has_correct_model(): void
    {
        $this->assertEquals(Post::class, PostResource::getModel());
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $post = Post::factory()->create();
        Livewire::test(ListPosts::class)->callTableAction('delete', $post);
        $this->assertDatabaseMissing('content', ['id' => $post->id, 'is_deleted' => 0]);
    }
}
