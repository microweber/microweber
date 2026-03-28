<?php

namespace Tests\Feature\Filament\Resources;

use Livewire\Livewire;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\ListPaymentProviders;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class PaymentProviderResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return PaymentProviderResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListPaymentProviders::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(PaymentProviderResource::getModel());
    }
}
