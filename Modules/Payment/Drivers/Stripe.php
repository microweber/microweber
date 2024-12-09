<?php

namespace Modules\Payment\Drivers;

use Filament\Forms\Components\TextInput;
use Omnipay\Omnipay;

class Stripe extends AbstractPaymentMethod
{
    public string $provider = 'stripe';
    private $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('Stripe\Checkout');

        // Set API keys if available
        $model = $this->getModel();
        if ($model && isset($model->settings['secret_key'])) {
            $this->gateway->setApiKey($model->settings['secret_key']);
        }
    }

    public function title(): string
    {
        return 'Stripe';
    }

    public function getSettingsForm(): array
    {
        return [
            TextInput::make('settings.publishable_key')
                ->label('Publishable Key')
                ->required()
                ->helperText('Your Stripe publishable key from the Stripe dashboard'),

            TextInput::make('settings.secret_key')
                ->label('Secret Key')
                ->required()
                ->password()
                ->helperText('Your Stripe secret key from the Stripe dashboard'),

            TextInput::make('settings.webhook_secret')
                ->label('Webhook Secret')
                ->helperText('Optional: Your Stripe webhook signing secret for verifying webhook events'),
        ];
    }

    public function getForm(): array
    {
        // No form fields needed as we're using Stripe Checkout
        return [];
    }

    public function process($data = []): array
    {



        try {
            $model = $this->getModel();
            if (!$model || !$model->settings) {
                throw new \Exception('Stripe is not configured properly');
            }

            // Set API key
            $this->gateway->setApiKey($model->settings['secret_key']);

            // Create Checkout Session
            $response = $this->gateway->purchase([
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'USD',
                'successUrl' => $data['returnUrl'],
                'cancelUrl' => $data['cancelUrl'],
                'metadata' => [
                    'order_id' => $data['order_id'] ?? null,
                    'customer_email' => $data['email'] ?? null,
                ],
                'mode' => 'payment',

            ])->send();
dd($response);
            if ($response->isRedirect()) {
                return [
                    'success' => true,
                    'transactionId' => $response->getTransactionReference(),
                    'redirectUrl' => $response->getRedirectUrl(),
                    'providerResponse' => $response->getData(),
                ];
            } else {
                throw new \Exception($response->getMessage());
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function verifyPayment(array $data): array
    {
        try {
            $model = $this->getModel();
            if (!$model || !$model->settings) {
                return [
                    'success' => false,
                    'message' => 'Stripe is not configured properly',
                ];
            }

            $this->gateway->setApiKey($model->settings['secret_key']);

            // Retrieve the checkout session
            $response = $this->gateway->fetchTransaction([
                'transactionReference' => $data['session_id']
            ])->send();

            if ($response->isSuccessful()) {
                $session = $response->getData();
                if ($session['payment_status'] === 'paid') {
                    return [
                        'success' => true,
                        'transactionId' => $session['id'],
                        'amount' => $session['amount_total'],
                        'currency' => $session['currency'],
                        'status' => 'completed', // or 'pending'
                        'providerResponse' => $session,
                    ];
                }
            }

            return [
                'success' => false,
                'message' => $response->getMessage() ?? 'Payment verification failed',
                'providerResponse' => $response->getData(),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
