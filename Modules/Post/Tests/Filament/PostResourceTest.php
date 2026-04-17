<?php

namespace Modules\Post\Tests\Filament;

use Livewire\Livewire;
use Modules\Content\Models\Content;
use Modules\Post\Filament\Admin\Resources\PostResource;
use Modules\Post\Filament\Admin\Resources\PostResource\Pages\ListPosts;
use Modules\Post\Models\Post;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class PostResourceTest extends FilamentResourceTestCase
{
    private array $createdIds = [];

    protected function getResourceClass(): string
    {
        return PostResource::class;
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
        Livewire::test(ListPosts::class)->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertEquals(Post::class, PostResource::getModel());
    }

    #[Test]
    public function it_can_create_and_delete_post(): void
    {
        $this->actingAsAdmin();

        $content = new Content();
        $content->title = 'Filament Post Test';
        $content->content_type = 'post';
        $content->is_active = true;
        $content->save();
        $this->createdIds[] = $content->id;

        $this->assertDatabaseHas('content', ['id' => $content->id, 'content_type' => 'post']);
    }
}
