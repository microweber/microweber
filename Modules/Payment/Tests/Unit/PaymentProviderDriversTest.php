<?php

namespace Modules\Payment\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Payment\Models\PaymentProvider;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\CreatePaymentProvider;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\ListPaymentProviders;
use Tests\TestCase;

class PaymentProviderDriversTest extends TestCase
{


    public function testPaymentProviderResourceDriversRenderMethod()
    {
        PaymentProvider::truncate();

        // Test PayPal driver
        $data = ['name' => 'PayPal Provider', 'provider' => 'paypal', 'is_active' => 1];
        $test = Livewire::test(CreatePaymentProvider::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasNoActionErrors()
            ->assertNotified()
            ->assertHasNoErrors();


        $this->assertTrue(PaymentProvider::where('name', 'PayPal Provider')->exists());

        // Test Stripe driver
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
        //tesit if seetings are seved

        $get = PaymentProvider::where('name', 'Stripe Provider')->first();
        $this->assertEquals('stripe', $get->settings['publishable_key']);
        $this->assertEquals('stripe', $get->settings['secret_key']);

        // Test PayOnDelivery driver
        $data = ['name' => 'PayOnDelivery Provider', 'provider' => 'pay_on_delivery', 'is_active' => 1];
        Livewire::test(CreatePaymentProvider::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasNoActionErrors()
            ->assertNotified()
            ->assertHasNoErrors();

        $this->assertTrue(PaymentProvider::where('name', 'PayOnDelivery Provider')->exists());


        $all = PaymentProvider::all();
        $this->assertCount(3, $all);

        foreach ($all as $provider) {
            $driver = app()->payment_method_manager->driver($provider->provider);
            $this->assertNotNull($driver->logo());
            $this->assertNotNull($driver->title());

        }


    }
}
