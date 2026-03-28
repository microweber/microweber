<?php

namespace Tests\Feature\Filament\Resources;

use Livewire\Livewire;
use Modules\Tag\Filament\Resources\TagGroupResource;
use Modules\Tag\Filament\Resources\TagGroupResource\Pages\ListTagGroups;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class TagGroupResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return TagGroupResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListTagGroups::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(TagGroupResource::getModel());
    }
}
