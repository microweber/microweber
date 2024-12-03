<?php

namespace Modules\Payment\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Payment\Models\Payment;
use Modules\Payment\Models\PaymentProvider;
use Tests\TestCase;
use Modules\Payment\Enums\PaymentStatus;

use Illuminate\Support\Facades\Event;
use Modules\Payment\Events\PaymentWasCreated;
use Modules\Payment\Events\PaymentWasDeleted;
use Modules\Payment\Events\PaymentWasUpdated;

class PaymentModelTest extends TestCase
{

    public function testPaymentProviderModel()
    {
        PaymentProvider::where('name', 'Test Provider')->delete();
        Payment::where('payment_provider_reference_id', '12345')->delete();

        // Fake events
        Event::fake();

        // Create a PaymentProvider
        $provider = PaymentProvider::create([
            'name' => 'Test Provider',
            'provider' => 'paypal',
            'is_active' => 1,
            'is_default' => 0,
            'settings' => ['paypal_email' => 'test@example.com'],
            'position' => 1,
        ]);

        $this->assertTrue(PaymentProvider::where('name', 'Test Provider')->exists());

        // Create a Payment
        $payment = Payment::create([
            'rel_id' => '1',
            'rel_type' => 'order',
            'payment_provider_id' => $provider->id,
            'payment_provider_reference_id' => '12345',
            'amount' => 100.00,
            'currency' => 'USD',
            'status' => PaymentStatus::Pending,
            'payment_data' => ['transaction_id' => 'txn_12345'],
        ]);

        $this->assertTrue(Payment::where('payment_provider_reference_id', '12345')->exists());

        // Assert that the PaymentWasCreated event was dispatched
        Event::assertDispatched(PaymentWasCreated::class, function ($event) use ($payment) {
            return $event->model->id === $payment->id;
        });

        // Update the Payment
        $payment->update(['status' => PaymentStatus::Completed]);

        // Assert that the PaymentWasUpdated event was dispatched
        Event::assertDispatched(PaymentWasUpdated::class, function ($event) use ($payment) {
            return $event->model->id === $payment->id;
        });

        // Delete the Payment
        $payment->delete();
        $this->assertFalse(Payment::where('payment_provider_reference_id', '12345')->exists());

        // Assert that the PaymentWasDeleted event was dispatched
        Event::assertDispatched(PaymentWasDeleted::class, function ($event) use ($payment) {
            return $event->model->id === $payment->id;
        });

        // Delete the PaymentProvider
        $provider->delete();
        $this->assertFalse(PaymentProvider::where('name', 'Test Provider')->exists());
    }
}
