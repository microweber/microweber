<?php

namespace Modules\Payment\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Payment\Models\PaymentProvider;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\CreatePaymentProvider;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\ListPaymentProviders;
use Tests\TestCase;

class PaymentProviderResourceTest extends TestCase
{


    public function testPaymentProviderResourcePaypal()
    {
        PaymentProvider::where('name', 'Test Provider paypal')->delete();
        // Test form rendering
        Livewire::test(CreatePaymentProvider::class)
            ->assertFormFieldExists('name')
            ->assertFormFieldExists('provider')
            ->assertFormFieldExists('is_active');

        // Test form submission
        $data = ['name' => 'Test Provider paypal', 'provider' => 'paypal', 'is_active' => 1];
        Livewire::test(CreatePaymentProvider::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasNoActionErrors()
            ->assertNotified()
            ->assertHasNoErrors();


        $this->assertTrue(PaymentProvider::where('name', 'Test Provider paypal')->exists());

        // Test deletion
        $provider = PaymentProvider::where('name', 'Test Provider paypal')->first();
        Livewire::test(ListPaymentProviders::class)
            ->callTableAction('delete', $provider)
            ->assertHasNoTableActionErrors();

        $this->assertFalse(PaymentProvider::where('name', 'Test Provider paypal')->exists());
    }


    public function testPaymentProviderResourceStripe()
    {
        PaymentProvider::where('name', 'Stripe Provider')->delete();
        // Test form rendering
        Livewire::test(CreatePaymentProvider::class)
            ->assertFormFieldExists('name')
            ->assertFormFieldExists('provider')
            ->assertFormFieldExists('is_active');

        // Test form submission
        $data = [
            'name' => 'Stripe Provider',
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

        $this->assertTrue(PaymentProvider::where('name', 'Stripe Provider')->exists());


    }
}
