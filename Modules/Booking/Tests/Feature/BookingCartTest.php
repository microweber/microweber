<?php

namespace Modules\Booking\Tests\Feature;

use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingProductSetting;
use Modules\Booking\Services\BookingService;
use Modules\Cart\Models\Cart;
use Modules\Cart\Services\CartService;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Adding a bookable product to the cart carries the chosen dates onto the line
 * and prices it by duration (nights × unit price), and a request for dates that
 * are already fully booked is rejected.
 */
class BookingCartTest extends TestCase
{
    protected ?Product $product = null;

    private function makeRoom(int $capacity = 1, float $nightly = 100): Product
    {
        $p = new Product(['subtype' => 'booking_product']);
        $p->title = 'cart-room-' . uniqid();
        $p->url = $p->title;
        $p->content_type = 'product';
        $p->save();
        $this->product = $p;

        BookingProductSetting::create([
            'product_id' => $p->id,
            'booking_type' => 'date_range',
            'capacity' => $capacity,
            'price_unit' => 'per_night',
            'unit_price' => $nightly,
            'is_active' => true,
        ]);

        return $p;
    }

    #[Test]
    public function adding_a_bookable_product_prices_by_nights_and_records_the_dates(): void
    {
        $p = $this->makeRoom(nightly: 100);

        $service = app(CartService::class);
        $result = $service->updateCart([
            'content_id' => $p->id,
            'title' => $p->title,
            'qty' => 1,
            'price' => 100,
            BookingService::F_CHECK_IN => '2026-12-10',
            BookingService::F_CHECK_OUT => '2026-12-13', // 3 nights
            BookingService::F_UNITS => 1,
            BookingService::F_GUESTS => 2,
        ]);

        $this->assertArrayNotHasKey('error', $result, 'Adding available dates should succeed: ' . json_encode($result));

        $line = Cart::where('rel_id', $p->id)->where('order_completed', 0)->latest('id')->first();
        $this->assertNotNull($line, 'A cart line should be created');
        $this->assertSame(300.0, (float) $line->price, '3 nights × 100 = 300');

        $cf = is_array($line->custom_fields_data) ? $line->custom_fields_data : json_decode((string) $line->custom_fields_json, true);
        $this->assertSame('2026-12-10', $cf[BookingService::F_CHECK_IN] ?? null);
        $this->assertSame('2026-12-13', $cf[BookingService::F_CHECK_OUT] ?? null);
        $this->assertEquals(2, $cf[BookingService::F_GUESTS] ?? null);
    }

    #[Test]
    public function adding_unavailable_dates_is_rejected(): void
    {
        $p = $this->makeRoom(capacity: 1, nightly: 100);

        // A confirmed booking already fills the single unit for these nights.
        Booking::create([
            'product_id' => $p->id,
            'booking_type' => 'date_range',
            'starts_at' => '2026-12-10 00:00',
            'ends_at' => '2026-12-13 00:00',
            'units' => 1,
            'status' => Booking::STATUS_CONFIRMED,
        ]);

        $result = app(CartService::class)->updateCart([
            'content_id' => $p->id,
            'title' => $p->title,
            'qty' => 1,
            'price' => 100,
            BookingService::F_CHECK_IN => '2026-12-11',
            BookingService::F_CHECK_OUT => '2026-12-12',
            BookingService::F_UNITS => 1,
        ]);

        $this->assertArrayHasKey('error', $result, 'Unavailable dates must be rejected');
        $this->assertSame(0, Cart::where('rel_id', $p->id)->where('order_completed', 0)->count());
    }

    protected function tearDown(): void
    {
        if ($this->product) {
            Cart::where('rel_id', $this->product->id)->forceDelete();
            Booking::where('product_id', $this->product->id)->delete();
            BookingProductSetting::where('product_id', $this->product->id)->delete();
            $this->product->forceDelete();
        }

        parent::tearDown();
    }
}
