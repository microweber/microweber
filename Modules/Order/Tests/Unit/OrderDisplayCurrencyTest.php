<?php

namespace Modules\Order\Tests\Unit;

use Modules\Order\Models\Order;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract: an order is STORED/settled in the base currency (currency/amount) but
 * also records the currency the shopper saw and agreed to (payment_currency/
 * payment_amount), and the display accessors surface that converted value so the
 * order shows the shopper's currency in the admin, confirmation and emails.
 */
class OrderDisplayCurrencyTest extends TestCase
{
    #[Test]
    public function display_accessors_return_the_converted_currency_when_present(): void
    {
        $order = new Order();
        $order->currency = 'USD';
        $order->amount = 89;
        $order->payment_currency = 'GBP';
        $order->payment_amount = 70.31;

        $this->assertSame('GBP', $order->display_currency);
        $this->assertSame(70.31, $order->display_amount);
        $this->assertStringContainsString('70.31', $order->formatted_display_total);
        $this->assertTrue($order->is_converted_currency);
    }

    #[Test]
    public function display_accessors_fall_back_to_the_base_currency(): void
    {
        $order = new Order();
        $order->currency = 'USD';
        $order->amount = 50;
        // No payment_currency/amount — a single-currency order.

        $this->assertSame('USD', $order->display_currency);
        $this->assertSame(50.0, $order->display_amount);
        $this->assertStringContainsString('50', $order->formatted_display_total);
        $this->assertFalse($order->is_converted_currency);
    }

    #[Test]
    public function an_order_in_the_base_currency_is_not_flagged_as_converted(): void
    {
        $order = new Order();
        $order->currency = 'USD';
        $order->amount = 20;
        $order->payment_currency = 'USD';
        $order->payment_amount = 20;

        $this->assertFalse($order->is_converted_currency,
            'Same display + settlement currency must not be flagged as converted.');
    }
}
