<?php

namespace Modules\Offer\Tests\Filament;

use Livewire\Livewire;
use Modules\Offer\Filament\Admin\Resources\OfferResource;
use Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\ListOffers;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class OfferResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return OfferResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListOffers::class)->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(OfferResource::getModel());
    }
}
