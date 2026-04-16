<?php

namespace Modules\Payment\Tests\Filament;

use Livewire\Livewire;
use Modules\Payment\Filament\Admin\Resources\PaymentResource;
use Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages\ListPayments;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\ListPaymentProviders;
use Tests\Feature\Filament\FilamentResourceTestCase;
use PHPUnit\Framework\Attributes\Test;

class PaymentResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return PaymentResource::class;
    }

    #[Test]
    public function it_can_render_payments_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListPayments::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_payment_providers_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListPaymentProviders::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_non_admin_cannot_access_payments(): void
    {
        $this->actingAsUser();

        $response = $this->get(PaymentResource::getUrl('index'));
        $response->assertForbidden();
    }
}
