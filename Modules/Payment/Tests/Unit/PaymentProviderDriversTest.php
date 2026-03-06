<?php

namespace Modules\Payment\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Payment\Models\PaymentProvider;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\CreatePaymentProvider;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\ListPaymentProviders;
use Tests\TestCase;

class PaymentProviderDriversTest extends TestCase
{


    #[Test]


    public function it_payment_provider_resource_drivers_render_method(): void {
        PaymentProvider::truncate();

        // Test PayPal driver
        $data = ['name' => 'PayPal', 'provider' => 'paypal', 'is_active' => 1];
        $test = Livewire::test(CreatePaymentProvider::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasNoActionErrors()
            ->assertNotified()
            ->assertHasNoErrors();

         $this->assertTrue(PaymentProvider::where('name', 'PayPal')->exists());

        // Test Stripe driver
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
        //tesit if seetings are seved

        $get = PaymentProvider::where('name', 'Stripe')->first();
        $this->assertEquals('stripe', $get->settings['publishable_key']);
        $this->assertEquals('stripe', $get->settings['secret_key']);

        // Test PayOnDelivery driver
        $data = ['name' => 'PayOnDelivery', 'provider' => 'pay_on_delivery', 'is_active' => 1];
        Livewire::test(CreatePaymentProvider::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasNoActionErrors()
            ->assertNotified()
            ->assertHasNoErrors();

        $this->assertTrue(PaymentProvider::where('name', 'PayOnDelivery')->exists());


        $all = PaymentProvider::all();
        $this->assertCount(3, $all);

        foreach ($all as $provider) {
            $driver = app()->payment_method_manager->driver($provider->provider);
            $this->assertNotNull($driver->logo());
            $this->assertNotNull($driver->title());

        }


    }
}
