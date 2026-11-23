<?php

namespace Modules\Payment\Tests\Unit;

use Modules\Payment\Drivers\MomoMtn;
use Modules\Payment\Drivers\PayPal;
use Modules\Payment\Drivers\Stripe;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract for per-provider currency support: a driver declares which currencies
 * it can charge in (empty = any), and supportsCurrency() honours that list. The
 * checkout uses this to hide/flag providers that can't take the selected currency.
 */
class SupportedCurrenciesTest extends TestCase
{
    #[Test]
    public function a_provider_with_no_declared_list_supports_any_currency(): void
    {
        $stripe = new Stripe();

        // Stripe accepts 135+ currencies → no restriction (empty list).
        $this->assertSame([], $stripe->supportedCurrencies());
        $this->assertTrue($stripe->supportsCurrency('GBP'));
        $this->assertTrue($stripe->supportsCurrency('JPY'));
        $this->assertTrue($stripe->supportsCurrency('ANY'));
    }

    #[Test]
    public function paypal_only_supports_its_declared_currencies(): void
    {
        $paypal = new PayPal();

        $this->assertNotEmpty($paypal->supportedCurrencies());
        $this->assertTrue($paypal->supportsCurrency('USD'));
        $this->assertTrue($paypal->supportsCurrency('GBP'));
        // Case-insensitive.
        $this->assertTrue($paypal->supportsCurrency('eur'));
        // A currency PayPal doesn't settle in.
        $this->assertFalse($paypal->supportsCurrency('NGN'));
        $this->assertFalse($paypal->supportsCurrency('XYZ'));
    }

    #[Test]
    public function momomtn_is_restricted_to_eur(): void
    {
        $momo = new MomoMtn();

        $this->assertSame(['EUR'], $momo->supportedCurrencies());
        $this->assertTrue($momo->supportsCurrency('EUR'));
        $this->assertFalse($momo->supportsCurrency('USD'));
        $this->assertFalse($momo->supportsCurrency('GBP'));
    }
}
