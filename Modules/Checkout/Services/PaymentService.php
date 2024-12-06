<?php

namespace Modules\Checkout\Services;

use Modules\Payment\Models\Payment;
use Modules\Payment\Models\PaymentProvider;
use Modules\Payment\Repositories\PaymentMethodManager;

class PaymentService
{
    protected $paymentMethodManager;

    public function __construct(PaymentMethodManager $paymentMethodManager)
    {
        $this->paymentMethodManager = $paymentMethodManager;
    }

    public function initiatePayment(array $data)
    {
        $provider = PaymentProvider::where('is_active', true)
            ->where('driver', $data['payment_method'])
            ->firstOrFail();

        $payment = Payment::create([
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? config('payment.default_currency'),
            'status' => 'pending',
            'payment_provider_id' => $provider->id,
            'metadata' => $data['metadata'] ?? [],
        ]);

        $driver = $this->paymentMethodManager->driver($provider->driver);

        $paymentData = [
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'transaction_id' => $payment->id,
            'returnUrl' => route('checkout.payment.return'),
            'cancelUrl' => route('checkout.payment.cancel'),
            'notifyUrl' => route('checkout.payment.notify'),
            ...$data['extra_params'] ?? [],
        ];

        $response = $driver->initiatePayment($paymentData);

        $payment->update([
            'transaction_id' => $response['transaction_id'] ?? $payment->id,
            'provider_response' => $response,
        ]);

        return [
            'success' => true,
            'payment' => $payment,
            'redirect_url' => $response['redirect_url'] ?? null,
            'payment_data' => $response['payment_data'] ?? null,
        ];
    }

    public function getAvailablePaymentMethods()
    {
        return PaymentProvider::where('is_active', true)
            ->get()
            ->map(function ($provider) {
                return [
                    'id' => $provider->id,
                    'name' => $provider->name,
                    'driver' => $provider->driver,
                    'icon' => $provider->icon,
                    'description' => $provider->description,
                ];
            });
    }
}
