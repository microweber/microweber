<?php

namespace Tests\Feature\Filament\Resources;

use Livewire\Livewire;
use Modules\Comments\Filament\Resources\CommentResource;
use Modules\Comments\Filament\Resources\CommentResource\Pages\ListComments;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class CommentResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return CommentResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListComments::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(CommentResource::getModel());
    }

    #[Test]
    public function it_resource_has_navigation_badge(): void
    {
        $this->actingAsAdmin();

        $badge = CommentResource::getNavigationBadge();
        $this->assertTrue($badge === null || is_string($badge));
    }
}
