<?php

namespace Tests\Feature\Filament\Resources;

use Livewire\Livewire;
use Modules\Post\Filament\Admin\Resources\PostResource;
use Modules\Post\Filament\Admin\Resources\PostResource\Pages\ListPosts;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

#[RunTestsInSeparateProcesses]
class PostResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return PostResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListPosts::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(PostResource::getModel());
    }
}
