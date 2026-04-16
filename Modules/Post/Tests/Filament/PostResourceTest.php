<?php

namespace Modules\Post\Tests\Filament;

use Livewire\Livewire;
use Modules\Post\Filament\Admin\Resources\PostResource;
use Modules\Post\Filament\Admin\Resources\PostResource\Pages\ListPosts;
use Tests\Feature\Filament\FilamentResourceTestCase;
use PHPUnit\Framework\Attributes\Test;

class PostResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return PostResource::class;
    }

    #[Test]
    public function it_can_render_posts_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListPosts::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_non_admin_cannot_access_posts(): void
    {
        $this->actingAsUser();

        $response = $this->get(PostResource::getUrl('index'));
        $response->assertForbidden();
    }
}
