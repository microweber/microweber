<?php

namespace Modules\Cart\Tests\Unit;

use Modules\Cart\Services\CartTotalsService;
use PHPUnit\Framework\Attributes\Test;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Contract: cart discounts are computed on the BASE currency amount, never on the
 * converted/display amount. A fixed coupon value is the base-currency value (a
 * "$10 off" coupon takes 10 off the base sum, not 10 of the shopper's currency);
 * a percentage applies to the base sum. The display layer (currency_format) then
 * converts the result — the discount math itself stays in the base currency.
 */
class DiscountOnBaseCurrencyTest extends TestCase
{
    private function calc(float $sum, string $type, float $value): float
    {
        $svc = app(CartTotalsService::class);
        $m = new ReflectionMethod($svc, 'calculateDiscount');
        $m->setAccessible(true);

        return (float) $m->invoke($svc, $sum, $type, $value);
    }

    #[Test]
    public function a_percentage_discount_applies_to_the_base_sum(): void
    {
        // 10% of a 100 base sum = 10 base, regardless of the shopper's currency.
        $this->assertSame(10.0, $this->calc(100.0, 'percentage', 10.0));
        $this->assertSame(25.0, $this->calc(250.0, 'percentage', 10.0));
    }

    #[Test]
    public function a_fixed_discount_is_the_base_currency_value(): void
    {
        // A "$10 off" coupon removes 10 from the base sum — it is NOT reinterpreted
        // as 10 units of the shopper's display currency.
        $this->assertSame(10.0, $this->calc(100.0, 'fixed_amount', 10.0));
    }

    #[Test]
    public function a_discount_never_exceeds_the_sum(): void
    {
        // A fixed discount larger than the sum is capped at the sum (no negative).
        $this->assertSame(50.0, $this->calc(50.0, 'fixed_amount', 80.0));
        // A percentage that would overshoot is likewise capped.
        $this->assertSame(50.0, $this->calc(50.0, 'percentage', 150.0));
    }
}
