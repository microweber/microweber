<?php

namespace Modules\Checkout\Tests\Feature;

use Modules\Checkout\Services\CheckoutService;
use Modules\Order\Models\Order;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Order-integrity regression: the public checkout must NOT let the client set
 * financial / payment-state fields. Previously prepareOrderData() did
 * array_merge($orderData, $data) with the raw request winning, so a checkout
 * POST could carry amount=0.01 (gateway charges a penny) or is_paid=1 (order
 * marked paid with no payment via the no-gateway branch).
 */
class CheckoutOrderIntegrityTest extends TestCase
{
    protected ?int $productId = null;

    protected function setUp(): void
    {
        parent::setUp();

        save_option(['option_key' => 'shop_require_terms', 'option_value' => '0', 'option_group' => 'website']);

        $this->productId = save_content([
            'title' => 'Integrity Test Product ' . uniqid(),
            'content_type' => 'product',
            'subtype' => 'product',
            'is_active' => 1,
            'custom_fields_advanced' => [
                ['type' => 'price', 'name' => 'Price', 'value' => '50.00'],
            ],
        ]);

        empty_cart();
    }

    private function baseCheckoutData(): array
    {
        return [
            'first_name' => 'Integrity',
            'last_name' => 'Tester',
            'email' => 'integrity' . uniqid() . '@example.com',
            'phone' => '+1234567890',
            'address' => '1 Test Street',
            'city' => 'Testville',
            'state' => 'TS',
            'zip' => '12345',
            'country' => 'US',
        ];
    }

    #[Test]
    public function client_cannot_override_server_computed_order_fields(): void
    {
        // Assert the merge contract directly on prepareOrderData(): the client's
        // financial/status fields must never win over the server values. (This
        // is cart-session independent, so it pins the fix deterministically.)
        $svc = app(CheckoutService::class);
        $method = new \ReflectionMethod($svc, 'prepareOrderData');
        $method->setAccessible(true);

        $data = $this->baseCheckoutData();
        $data['amount'] = 0.01;            // attacker-chosen price
        $data['is_paid'] = 1;
        $data['order_status'] = 'completed';
        $data['order_completed'] = 1;
        $data['taxes_amount'] = 777;
        $data['discount_value'] = 9999;
        $data['payment_status'] = 'paid';
        $data['transaction_id'] = 'FAKE-TXN';

        $out = $method->invoke($svc, $data);

        $this->assertSame((float) cart_total(), (float) $out['amount'], 'amount must be the server total');
        $this->assertNotEquals(0.01, (float) $out['amount'], 'penny-price must not stick');
        $this->assertSame(0, (int) $out['is_paid']);
        $this->assertSame('new', (string) $out['order_status']);
        $this->assertNotEquals(777, (float) $out['taxes_amount'], 'taxes must be server-computed');
        $this->assertNotEquals(9999, (float) $out['discount_value'], 'discount must be server-computed');
        $this->assertArrayNotHasKey('payment_status', $out, 'payment_status must never come from the client');
        $this->assertArrayNotHasKey('transaction_id', $out, 'transaction_id must never come from the client');
        // Customer fields the client IS allowed to set still pass through.
        $this->assertSame($data['email'], $out['email']);
        $this->assertSame($data['city'], $out['city']);
    }

    #[Test]
    public function client_cannot_mark_the_order_paid_without_payment(): void
    {
        update_cart(['content_id' => $this->productId, 'qty' => 1]);

        $data = $this->baseCheckoutData();
        // No payment_provider_id -> the no-gateway branch; attacker forces paid.
        $data['is_paid'] = 1;
        $data['order_status'] = 'completed';
        $data['payment_status'] = 'paid';

        $result = app(CheckoutService::class)->checkout($data);
        $order = Order::find($result['order_id']);

        $this->assertNotNull($order);
        $this->assertSame(0, (int) $order->is_paid, 'order must NOT be paid without a verified payment');
        $this->assertSame('new', (string) $order->order_status, 'order_status must come from the server');
    }
}
