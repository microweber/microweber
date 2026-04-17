<?php

namespace Modules\Payment\Tests\Filament;

use Livewire\Livewire;
use Modules\Payment\Filament\Admin\Resources\PaymentResource;
use Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages\ListPayments;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\ListPaymentProviders;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class PaymentResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return PaymentResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListPayments::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_payment_providers_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListPaymentProviders::class)->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(PaymentResource::getModel());
    }
}
