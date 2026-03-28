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
        $data = ['name' => 'Paypal', 'provider' => 'paypal', 'is_active' => 1];
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
        PaymentProvider::where('name', 'Stripe Provider')->delete();
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
