<?php

namespace Modules\Tax\Tests\Filament;

use Livewire\Livewire;
use Modules\Tax\Filament\Admin\Resources\TaxResource;
use Modules\Tax\Filament\Admin\Resources\TaxResource\Pages\ListTaxes;
use Modules\Tax\Filament\Admin\Resources\TaxRateResource;
use Modules\Tax\Filament\Admin\Resources\TaxRateResource\Pages\ListTaxRates;
use Tests\Feature\Filament\FilamentResourceTestCase;
use PHPUnit\Framework\Attributes\Test;

class TaxResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return TaxResource::class;
    }

    #[Test]
    public function it_can_render_taxes_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListTaxes::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_tax_rates_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListTaxRates::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_non_admin_cannot_access_taxes(): void
    {
        $this->actingAsUser();

        $response = $this->get(TaxResource::getUrl('index'));
        $response->assertForbidden();
    }
}
