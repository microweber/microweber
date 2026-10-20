<?php

namespace Modules\Booking\Tests\Feature;

use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingProductSetting;
use Modules\Booking\Services\AvailabilityService;
use Modules\Booking\Services\BookingService;
use Modules\Cart\Models\Cart;
use Modules\Order\Events\OrderWasPaid;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Real end-to-end booking confirmation: a bookable product is bought in a paid
 * order whose cart line carries the chosen dates; the real OrderWasPaid event
 * runs the registered listener which confirms a Booking that then consumes
 * availability. No mock order objects — real Order + cart rows + real event.
 */
class BookingConfirmationFlowTest extends TestCase
{
    protected array $productIds = [];
    protected array $orderIds = [];

    private function makeBookableProduct(int $capacity = 1): Product
    {
        $p = new Product(['subtype' => 'booking_product']);
        $p->title = 'room-' . uniqid();
        $p->url = $p->title;
        $p->content_type = 'product';
        $p->save();
        $this->productIds[] = $p->id;

        BookingProductSetting::create([
            'product_id' => $p->id,
            'booking_type' => 'date_range',
            'capacity' => $capacity,
            'price_unit' => 'per_night',
            'unit_price' => 100,
            'is_active' => true,
        ]);

        return $p;
    }

    private function makePaidOrderFor(Product $p, string $checkIn, string $checkOut, int $units = 1, float $price = 300): Order
    {
        $order = Order::factory()->create([
            'email' => 'guest-' . uniqid() . '@example.com',
            'order_completed' => 1,
            'is_paid' => 1,
            'amount' => $price,
            'currency' => 'USD',
        ]);
        $this->orderIds[] = $order->id;

        Cart::create([
            'rel_id' => $p->id,
            'rel_type' => morph_name(Product::class),
            'title' => $p->title,
            'qty' => 1,
            'price' => $price,
            'order_id' => $order->id,
            'order_completed' => 1,
            'session_id' => 'bk-' . uniqid(),
            'custom_fields_data' => [
                BookingService::F_CHECK_IN => $checkIn,
                BookingService::F_CHECK_OUT => $checkOut,
                BookingService::F_UNITS => $units,
                BookingService::F_GUESTS => 2,
            ],
        ]);

        return $order;
    }

    #[Test]
    public function paying_an_order_confirms_a_booking_that_consumes_availability(): void
    {
        $product = $this->makeBookableProduct(capacity: 1);
        $order = $this->makePaidOrderFor($product, '2026-09-10', '2026-09-13', 1, 300);

        $availability = new AvailabilityService();
        // Before payment nothing is reserved.
        $this->assertTrue($availability->isAvailable($product->id, '2026-09-10', '2026-09-13', 1, 1));

        event(new OrderWasPaid($order));

        $booking = Booking::where('order_id', $order->id)->first();
        $this->assertNotNull($booking, 'A booking must be confirmed for the paid order');
        $this->assertSame(Booking::STATUS_CONFIRMED, $booking->status);
        $this->assertSame('2026-09-10', $booking->starts_at->format('Y-m-d'));
        $this->assertSame('2026-09-13', $booking->ends_at->format('Y-m-d'));
        $this->assertSame(2, (int) $booking->guests);
        $this->assertSame('guest', explode('-', (string) $booking->customer_email)[0]);

        // The confirmed booking now consumes the single unit → no longer available.
        $this->assertFalse($availability->isAvailable($product->id, '2026-09-11', '2026-09-12', 1, 1));
        // A non-overlapping stay is still free.
        $this->assertTrue($availability->isAvailable($product->id, '2026-09-20', '2026-09-22', 1, 1));
    }

    #[Test]
    public function confirmation_is_idempotent_across_repeated_paid_events(): void
    {
        $product = $this->makeBookableProduct(capacity: 1);
        $order = $this->makePaidOrderFor($product, '2026-10-01', '2026-10-04');

        event(new OrderWasPaid($order));
        event(new OrderWasPaid($order));

        $this->assertSame(1, Booking::where('order_id', $order->id)->count());
    }

    #[Test]
    public function a_second_paid_order_for_the_same_dates_is_still_recorded_lock_on_pay(): void
    {
        // Capacity 1: once one order is paid for the dates, a second paid order
        // for the same dates is still recorded (the money changed hands) — it's
        // flagged in the log for the admin to resolve, not silently dropped.
        $product = $this->makeBookableProduct(capacity: 1);
        $orderA = $this->makePaidOrderFor($product, '2026-11-01', '2026-11-03');
        $orderB = $this->makePaidOrderFor($product, '2026-11-01', '2026-11-03');

        event(new OrderWasPaid($orderA));
        event(new OrderWasPaid($orderB));

        $this->assertSame(1, Booking::where('order_id', $orderA->id)->count());
        $this->assertSame(1, Booking::where('order_id', $orderB->id)->count());
    }

    protected function tearDown(): void
    {
        if (!empty($this->orderIds)) {
            Booking::whereIn('order_id', $this->orderIds)->delete();
            Cart::whereIn('order_id', $this->orderIds)->forceDelete();
            Order::whereIn('id', $this->orderIds)->forceDelete();
        }
        if (!empty($this->productIds)) {
            BookingProductSetting::whereIn('product_id', $this->productIds)->delete();
            Product::whereIn('id', $this->productIds)->forceDelete();
        }

        parent::tearDown();
    }
}
