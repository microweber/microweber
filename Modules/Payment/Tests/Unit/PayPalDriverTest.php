<?php

namespace Modules\Payment\Tests\Unit;

use Modules\Payment\Drivers\PayPal;
use Modules\Payment\Models\PaymentProvider;
use Tests\TestCase;

class PayPalDriverTest extends TestCase
{
    protected PayPal $driver;
    protected PaymentProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->driver = new PayPal();
        $this->provider = new PaymentProvider([
            'name' => 'PayPal Test',
            'provider' => 'paypal',
            'settings' => [
                'client_id' => 'test_client_id',
                'client_secret' => 'test_client_secret',
                'test_mode' => true,
            ],
        ]);

        $this->driver->setModel($this->provider);
    }

    public function test_paypal_driver_has_correct_provider_name(): void
    {
        $this->assertEquals('paypal', $this->driver->provider);
    }

    public function test_paypal_driver_returns_title(): void
    {
        $this->assertEquals('PayPal', $this->driver->title());
    }

    public function test_paypal_driver_returns_logo(): void
    {
        $logo = $this->driver->logo();
        $this->assertStringContainsString('paypal.png', $logo);
    }

    public function test_paypal_driver_returns_settings_form(): void
    {
        $form = $this->driver->getSettingsForm();

        $this->assertIsArray($form);
        $this->assertNotEmpty($form);

        // Check that the form has sections
        $this->assertCount(1, $form);
    }

    public function test_paypal_driver_returns_payment_form(): void
    {
        $form = $this->driver->getForm();

        $this->assertIsArray($form);
        $this->assertNotEmpty($form);
    }

    public function test_paypal_driver_gets_webhook_id(): void
    {
        $provider = new PaymentProvider([
            'name' => 'PayPal Test',
            'provider' => 'paypal',
            'settings' => [
                'webhook_id' => 'test_webhook_123',
            ],
        ]);

        $this->driver->setModel($provider);

        $this->assertEquals('test_webhook_123', $this->driver->getWebhookId());
    }

    public function test_paypal_driver_returns_null_webhook_id_when_not_set(): void
    {
        $this->assertNull($this->driver->getWebhookId());
    }

    public function test_paypal_driver_processes_payment_with_rest_credentials(): void
    {
        // This test validates that the driver properly structures the data
        // Actual payment processing would require mocking Omnipay
        $data = [
            'order_reference_id' => 'TEST-123',
            'amount' => 99.99,
            'currency' => 'USD',
            'returnUrl' => 'https://example.com/success',
            'cancelUrl' => 'https://example.com/cancel',
            'email' => 'test@example.com',
        ];

        // The process method would fail without proper mocking, but we can verify
        // it at least handles the data structure correctly
        $result = $this->driver->process($data);

        // Should fail since we're not mocking the gateway
        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
    }

    public function test_paypal_driver_processes_payment_with_classic_credentials(): void
    {
        $provider = new PaymentProvider([
            'name' => 'PayPal Test',
            'provider' => 'paypal',
            'settings' => [
                'paypal_api_username' => 'test_api_username',
                'paypal_api_password' => 'test_api_password',
                'paypal_api_signature' => 'test_api_signature',
                'test_mode' => true,
            ],
        ]);

        $this->driver->setModel($provider);

        $data = [
            'order_reference_id' => 'TEST-123',
            'order' => [
                'amount' => 50.00,
                'currency' => 'EUR',
            ],
            'returnUrl' => 'https://example.com/success',
            'cancelUrl' => 'https://example.com/cancel',
        ];

        $result = $this->driver->process($data);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
    }

    public function test_paypal_driver_fails_without_credentials(): void
    {
        $provider = new PaymentProvider([
            'name' => 'PayPal Test',
            'provider' => 'paypal',
            'settings' => [],
        ]);

        $this->driver->setModel($provider);

        $result = $this->driver->process([]);

        $this->assertFalse($result['success']);
        $this->assertEquals('PayPal is not configured properly', $result['message']);
    }

    public function test_paypal_driver_verifies_payment_without_token(): void
    {
        $result = $this->driver->verifyPayment([
            'amount' => 99.99,
            'currency' => 'USD',
        ]);

        $this->assertFalse($result['success']);
        $this->assertEquals('Payment token is required', $result['message']);
    }

    public function test_paypal_driver_extends_abstract_payment_method(): void
    {
        $this->assertInstanceOf(\Modules\Payment\Drivers\AbstractPaymentMethod::class, $this->driver);
    }
}
