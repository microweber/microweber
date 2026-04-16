<?php

namespace Modules\Shipping\Tests\Filament;

use Livewire\Livewire;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\ListShippingProviders;
use Tests\Feature\Filament\FilamentResourceTestCase;
use PHPUnit\Framework\Attributes\Test;

class ShippingResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return ShippingProviderResource::class;
    }

    #[Test]
    public function it_can_render_shipping_providers_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListShippingProviders::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_non_admin_cannot_access_shipping(): void
    {
        $this->actingAsUser();

        $response = $this->get(ShippingProviderResource::getUrl('index'));
        $response->assertForbidden();
    }
}
