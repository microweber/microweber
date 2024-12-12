<?php

namespace Modules\Payment\Drivers;

use Filament\Forms;
use Modules\Payment\Enums\PaymentStatus;
use Omnipay\Omnipay;

class Mollie extends AbstractPaymentMethod
{
    public string $provider = 'mollie';
    protected $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('Mollie');

        // Set API key if available
        $model = $this->getModel();
        if ($model && isset($model->settings['api_key'])) {
            $this->gateway->setApiKey($model->settings['api_key']);
        }
    }

    public function title(): string
    {
        return 'Mollie';
    }

    public function getSettingsForm(): array
    {
        return [
            Forms\Components\TextInput::make('settings.api_key')
                ->label('API Key')
                ->required()
                ->password()
                ->helperText('Your Mollie API key from the Mollie dashboard'),

            Forms\Components\TextInput::make('settings.profile_id')
                ->label('Profile ID')
                ->required()
                ->helperText('Your Mollie Profile ID from the Mollie dashboard'),
        ];
    }

    public function getForm(): array
    {
        return [
            Forms\Components\Section::make()
                ->schema(function (Forms\Components\Section $component, Forms\Set $set, Forms\Get $get, ?array $state) {
                    return [
                        Forms\Components\Placeholder::make('')
                            ->content('You will be redirected to Mollie to complete your purchase.')
                    ];
                })
        ];
    }

    public function process($data = []): array
    {
        try {
            $model = $this->getModel();
            if (!$model || !$model->settings) {
                throw new \Exception('Mollie is not configured properly');
            }

            // Set API key
            $this->gateway->setApiKey($model->settings['api_key']);

            // Create payment
            $response = $this->gateway->purchase([
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'EUR',
                'returnUrl' => $data['returnUrl'],
                'cancelUrl' => $data['cancelUrl'],
                'notifyUrl' => $data['notifyUrl'] ?? null,
                'metadata' => [
                    'order_reference_id' => $data['order_reference_id'] ?? null,
                    'customer_email' => $data['email'] ?? null,
                ],
                'profileId' => $model->settings['profile_id'] ?? null,
                'description' => $data['description'] ?? 'Order Payment',
            ])->send();

            $data = $response->getData();


            if (isset($data['_links']['checkout']['href'])) {
                return [
                    'success' => true,
                    'transactionId' => $response->getTransactionReference(),
                    'redirectUrl' => $data['_links']['checkout']['href'],
                    'providerResponse' => $response->getData(),
                ];
            }

            return [
                'success' => false,
                'message' => $response->getMessage() ?? 'Payment failed',
                'providerResponse' => $response->getData(),
            ];

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
                    'message' => 'Mollie is not configured properly',
                ];
            }

            $this->gateway->setApiKey($model->settings['api_key']);


            // Fetch payment status
            $response = $this->gateway->fetchTransaction([
                'transactionReference' => $data['order']['transaction_id']
            ])->send();


            if ($response->isSuccessful()) {

                $paymentData = $response->getData();

                $data = $response->getData();
                $isPaid = $data['status'] == 'paid';

                return [
                    'success' => true,
                    'transactionId' => $paymentData['id'],
                    'amount' => $paymentData['amount']['value'],
                    'currency' => $paymentData['amount']['currency'],
                    'status' => $isPaid ? PaymentStatus::Completed : PaymentStatus::Pending,
                    'providerResponse' => $paymentData,
                ];
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
