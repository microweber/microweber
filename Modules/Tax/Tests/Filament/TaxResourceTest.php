<?php

namespace Modules\Tax\Tests\Filament;

use Livewire\Livewire;
use Modules\Tax\Filament\Admin\Resources\TaxResource;
use Modules\Tax\Filament\Admin\Resources\TaxResource\Pages\ListTaxes;
use Modules\Tax\Filament\Admin\Resources\TaxRateResource;
use Modules\Tax\Filament\Admin\Resources\TaxRateResource\Pages\ListTaxRates;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class TaxResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return TaxResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListTaxes::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_tax_rates_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListTaxRates::class)->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(TaxResource::getModel());
    }
}
