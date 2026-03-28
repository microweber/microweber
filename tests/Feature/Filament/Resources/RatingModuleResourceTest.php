<?php

namespace Tests\Feature\Filament\Resources;

use Livewire\Livewire;
use Modules\Rating\Filament\Resources\RatingModuleResource;
use Modules\Rating\Filament\Resources\RatingModuleResource\Pages\ListRatings;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class RatingModuleResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return RatingModuleResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListRatings::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(RatingModuleResource::getModel());
    }
}
