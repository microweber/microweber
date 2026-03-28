<?php

namespace Tests\Feature\Filament\Resources;

use Livewire\Livewire;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\ListShippingProviders;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class ShippingProviderResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return ShippingProviderResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListShippingProviders::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(ShippingProviderResource::getModel());
    }
}
