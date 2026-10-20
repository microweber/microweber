<?php

namespace Modules\Booking\Tests\Feature;

use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingProductSetting;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * The public availability feed the storefront calendar consumes: fully-booked
 * nights come back in `unavailable` (date_range), and open slots in `slots`
 * (time_slot).
 */
class AvailabilityApiTest extends TestCase
{
    protected ?Product $product = null;

    private function make(string $type, array $attrs = []): Product
    {
        $p = new Product(['subtype' => 'booking_product']);
        $p->title = 'api-' . uniqid();
        $p->url = $p->title;
        $p->content_type = 'product';
        $p->save();
        $this->product = $p;

        BookingProductSetting::create(array_merge([
            'product_id' => $p->id,
            'booking_type' => $type,
            'capacity' => 1,
            'price_unit' => $type === 'time_slot' ? 'per_slot' : 'per_night',
            'unit_price' => 50,
            'is_active' => true,
        ], $attrs));

        return $p;
    }

    #[Test]
    public function date_range_feed_lists_fully_booked_nights(): void
    {
        $p = $this->make('date_range');
        Booking::create([
            'product_id' => $p->id, 'booking_type' => 'date_range',
            'starts_at' => '2026-12-10 00:00', 'ends_at' => '2026-12-12 00:00',
            'units' => 1, 'status' => Booking::STATUS_CONFIRMED,
        ]);

        $res = $this->getJson(route('booking.availability', [
            'product_id' => $p->id, 'from' => '2026-12-08', 'to' => '2026-12-15',
        ]));

        $res->assertOk()->assertJsonPath('type', 'date_range');
        $unavailable = $res->json('unavailable');
        $this->assertContains('2026-12-10', $unavailable);
        $this->assertContains('2026-12-11', $unavailable);
        $this->assertNotContains('2026-12-12', $unavailable); // checkout day is free
        $this->assertNotContains('2026-12-08', $unavailable);
    }

    #[Test]
    public function time_slot_feed_lists_open_slots(): void
    {
        $p = $this->make('time_slot', [
            'slot_minutes' => 60, 'opens_at' => '09:00', 'closes_at' => '12:00',
        ]);
        Booking::create([
            'product_id' => $p->id, 'booking_type' => 'time_slot',
            'starts_at' => '2026-12-10 10:00', 'ends_at' => '2026-12-10 11:00',
            'units' => 1, 'status' => Booking::STATUS_CONFIRMED,
        ]);

        $res = $this->getJson(route('booking.availability', [
            'product_id' => $p->id, 'date' => '2026-12-10',
        ]));

        $res->assertOk()->assertJsonPath('type', 'time_slot');
        $starts = array_column($res->json('slots'), 'start');
        $this->assertContains('2026-12-10 09:00', $starts);
        $this->assertContains('2026-12-10 11:00', $starts);
        $this->assertNotContains('2026-12-10 10:00', $starts); // booked
    }

    #[Test]
    public function non_bookable_product_returns_404(): void
    {
        $p = new Product();
        $p->title = 'plain-' . uniqid();
        $p->url = $p->title;
        $p->content_type = 'product';
        $p->save();
        $this->product = $p;

        $this->getJson(route('booking.availability', ['product_id' => $p->id]))
            ->assertNotFound();
    }

    protected function tearDown(): void
    {
        if ($this->product) {
            Booking::where('product_id', $this->product->id)->delete();
            BookingProductSetting::where('product_id', $this->product->id)->delete();
            $this->product->forceDelete();
        }

        parent::tearDown();
    }
}
