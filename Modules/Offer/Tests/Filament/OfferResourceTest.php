<?php

namespace Modules\Offer\Tests\Filament;

use Livewire\Livewire;
use Modules\Offer\Filament\Admin\Resources\OfferResource;
use Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\ListOffers;
use Tests\Feature\Filament\FilamentResourceTestCase;
use PHPUnit\Framework\Attributes\Test;

class OfferResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return OfferResource::class;
    }

    #[Test]
    public function it_can_render_offers_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListOffers::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_non_admin_cannot_access_offers(): void
    {
        $this->actingAsUser();

        $response = $this->get(OfferResource::getUrl('index'));
        $response->assertForbidden();
    }
}
