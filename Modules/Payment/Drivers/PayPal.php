<?php

namespace Modules\Payment\Drivers;

use Filament\Forms;
use Omnipay\Omnipay;

class PayPal extends AbstractPaymentMethod
{
    public string $provider = 'paypal';
    protected $gateway;

    public function logo(): string
    {
        return asset('modules/payment/img/paypal.png');
    }

    public function title(): string
    {
        return 'PayPal';
    }

    public function process($data = [])
    {
        $model = $this->getModel();
        $settings = $model->settings ?? [];

        try {
            /* @var $gateway \Omnipay\PayPal\ExpressGateway */
            $this->gateway = Omnipay::create('PayPal_Express');

            $this->gateway->setUsername($settings['paypal_api_username'] ?? '');
            $this->gateway->setPassword($settings['paypal_api_password'] ?? '');
            $this->gateway->setSignature($settings['paypal_api_signature'] ?? '');
            $this->gateway->setTestMode($settings['paypal_test_mode'] ?? true);

            $response = $this->gateway->authorize([
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'returnUrl' => $data['returnUrl'],
                'cancelUrl' => $data['cancelUrl'],
                'notifyUrl' => $data['notifyUrl'],
            ])->send();

            if ($response->isRedirect()) {
                return [
                    'success' => true,
                    'transactionId' => $response->getTransactionReference(),
                    'redirectUrl' => $response->getRedirectUrl(),
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
            $this->gateway = Omnipay::create('PayPal_Express');
            $model = $this->getModel();
            $settings = $model->settings ?? [];

            $this->gateway->setUsername($settings['paypal_api_username'] ?? '');
            $this->gateway->setPassword($settings['paypal_api_password'] ?? '');
            $this->gateway->setSignature($settings['paypal_api_signature'] ?? '');
            $this->gateway->setTestMode($settings['paypal_test_mode'] ?? true);

            $response = $this->gateway->completePurchase([
                'transactionReference' => $data['token'],
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'payerId' => $data['PayerID'],
            ])->send();

            if ($response->isSuccessful()) {
                $responseData = $response->getData();
                return [
                    'success' => true,
                    'transactionId' => $response->getTransactionReference(),
                    'amount' => $data['amount'],
                    'currency' => $data['currency'],
                    'status' => 'completed',
                    'providerResponse' => $responseData,
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

    protected function formatItems(array $items)
    {
        return array_map(function ($item) {
            return [
                'name' => $item['name'] ?? '',
                'description' => $item['description'] ?? '',
                'quantity' => $item['quantity'] ?? 1,
                'price' => $item['price'] ?? 0,
            ];
        }, $items);
    }

    protected function formatCustomerData(array $customer)
    {
        return [
            'firstName' => $customer['first_name'] ?? '',
            'lastName' => $customer['last_name'] ?? '',
            'email' => $customer['email'] ?? '',
            'phone' => $customer['phone'] ?? '',
            'billingAddress1' => $customer['address'] ?? '',
            'billingCity' => $customer['city'] ?? '',
            'billingState' => $customer['state'] ?? '',
            'billingCountry' => $customer['country'] ?? '',
            'billingPostcode' => $customer['zip'] ?? '',
        ];
    }

    public function getSettingsForm(): array
    {
        return [
            Forms\Components\Section::make()
                ->statePath('settings')
                ->reactive()
                ->schema(function (Forms\Components\Section $component, Forms\Set $set, Forms\Get $get, ?array $state) {
                    $provider = $get('provider');

                    return [
                        Forms\Components\TextInput::make('paypal_api_username')
                            ->label('API Username')
                            ->columnSpan('full')
                            ->placeholder('Enter your PayPal API username')
                            ->default(''),
                        Forms\Components\TextInput::make('paypal_api_password')
                            ->label('API Password')
                            ->columnSpan('full')
                            ->placeholder('Enter your PayPal API password')
                            ->default(''),
                        Forms\Components\TextInput::make('paypal_api_signature')
                            ->label('API Signature')
                            ->columnSpan('full')
                            ->placeholder('Enter your PayPal API signature')
                            ->default(''),
                        Forms\Components\Toggle::make('paypal_test_mode')
                            ->label('Test Mode')
                            ->columnSpan('full'),
                    ];
                })
                ->visible(function (Forms\Get $get) {
                    return (
                        $get('provider') === 'paypal'
                    );
                })
        ];
    }

    public function getForm(): array
    {
        return [
            Forms\Components\Section::make()
                ->schema(function (Forms\Components\Section $component, Forms\Set $set, Forms\Get $get, ?array $state) {
                    return [
                        Forms\Components\Placeholder::make('')
                            ->content('You will be redirected to PayPal to complete your purchase.')
                    ];
                })
        ];
    }
}
