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
                //   'payment_method_types' => ['card'],
                'mode' => 'payment',
                'line_items' => [[
                    'price_data' => [
                        'currency' => $data['currency'] ?? 'USD',
                        'unit_amount' => $data['amount'],
                        'product_data' => [
                            'name' => $data['description'] ?? 'Order Payment',
                        ],
                    ],
                    'quantity' => 1,
                ]],
            ])->send();

            if ($response->isSuccessful()) {
                $data = ($response->getData());

                return [
                    'success' => true,
                    'transactionId' => $data['id'],
                    'redirectUrl' => $data['url'],
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

    public function verifyPayment(array $data): bool
    {


        try {
            $model = $this->getModel();
            if (!$model || !$model->settings) {
                return false;
            }
           $transaction_id = $data['order']['transaction_id'] ?? null;
            $this->gateway->setApiKey($model->settings['secret_key']);

            // Retrieve the checkout session
            $response = $this->gateway->fetchTransaction([
                'transactionReference' => $transaction_id
            ])->send();

            if ($response->isSuccessful()) {
                $session = $response->getData();
               
                return $session['payment_status'] === 'paid';
            }

            return false;

        } catch (\Exception $e) {
            return false;
        }
    }
}
