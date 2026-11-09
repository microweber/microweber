<?php

namespace Modules\Payment\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Livewire\Livewire;
use Modules\Payment\Models\PaymentProvider;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\CreatePaymentProvider;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\ListPaymentProviders;
use Tests\TestCase;

class PaymentProviderResourceTest extends TestCase
{


    #[Test]


    public function it_payment_provider_resource_paypal(): void {
        PaymentProvider::where('name', 'Paypal')->delete();
        // Test form rendering
        Livewire::test(CreatePaymentProvider::class)
            ->assertFormFieldExists('name')
            ->assertFormFieldExists('provider')
            ->assertFormFieldExists('is_active');

        // Test form submission
        $data = ['name' => 'Paypal', 'provider' => 'paypal', 'settings.webhook_id' => 'WH-TEST-123', 'is_active' => 1];
        Livewire::test(CreatePaymentProvider::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasNoActionErrors()
            ->assertNotified()
            ->assertHasNoErrors();


        $this->assertTrue(PaymentProvider::where('name', 'Paypal')->exists());

        // Test deletion
        $provider = PaymentProvider::where('name', 'Paypal')->first();
        Livewire::test(ListPaymentProviders::class)
            ->callTableAction('delete', $provider)
            ->assertHasNoTableActionErrors();

        $this->assertFalse(PaymentProvider::where('name', 'Paypal')->exists());
    }


    #[Test]


    public function it_payment_provider_resource_stripe(): void {
        // The provider <select> only offers drivers NOT already set up (it excludes
        // a driver whose title matches an existing provider's name). A leftover
        // Stripe provider from a sibling test therefore drops "stripe" from the
        // options and fails validation. Clear any conflicting stripe provider —
        // by driver key AND by the driver title — before testing.
        PaymentProvider::where('provider', 'stripe')
            ->orWhere('name', 'Stripe')
            ->orWhere('name', 'Stripe Provider')
            ->delete();
        // Test form rendering
        Livewire::test(CreatePaymentProvider::class)
            ->assertFormFieldExists('name')
            ->assertFormFieldExists('provider')
            ->assertFormFieldExists('is_active');

        // Test form submission
        $data = [
            'name' => 'Stripe',
            'provider' => 'stripe',
            'settings.publishable_key' => 'stripe',
            'settings.secret_key' => 'stripe',
            'settings.webhook_secret' => 'whsec_test',
            'is_active' => 1
        ];
        Livewire::test(CreatePaymentProvider::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasNoActionErrors()
            ->assertNotified()
            ->assertHasNoErrors();

        $this->assertTrue(PaymentProvider::where('name', 'Stripe')->exists());


    }
}
