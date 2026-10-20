<?php

namespace Modules\Booking\Tests\Unit;

use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingProductSetting;
use Modules\Booking\Services\AvailabilityService;
use Modules\Booking\Services\BookingService;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * The availability engine for both booking types: half-open overlap, N-unit
 * capacity, pending-not-consuming, turnaround buffer, and duration pricing.
 */
class AvailabilityServiceTest extends TestCase
{
    protected array $productIds = [];
    protected AvailabilityService $availability;
    protected BookingService $booking;

    protected function setUp(): void
    {
        parent::setUp();
        $this->availability = new AvailabilityService();
        $this->booking = new BookingService($this->availability);
    }

    private function makeProduct(array $settingAttrs = []): Product
    {
        $p = new Product(['subtype' => 'booking_product']);
        $p->title = 'room-' . uniqid();
        $p->url = $p->title;
        $p->content_type = 'product';
        $p->save();
        $this->productIds[] = $p->id;

        BookingProductSetting::create(array_merge([
            'product_id' => $p->id,
            'booking_type' => BookingProductSetting::TYPE_DATE_RANGE,
            'capacity' => 1,
            'price_unit' => 'per_night',
            'unit_price' => 100,
            'is_active' => true,
        ], $settingAttrs));

        return $p;
    }

    private function book(int $productId, string $start, string $end, int $units = 1, string $status = Booking::STATUS_CONFIRMED, string $type = 'date_range'): Booking
    {
        return Booking::create([
            'order_id' => null,
            'product_id' => $productId,
            'booking_type' => $type,
            'starts_at' => $start,
            'ends_at' => $end,
            'units' => $units,
            'status' => $status,
        ]);
    }

    #[Test]
    public function capacity_is_consumed_by_overlapping_confirmed_bookings(): void
    {
        $p = $this->makeProduct(['capacity' => 2]);

        // Two rooms; both booked for the same week → the week is full.
        $this->book($p->id, '2026-06-10 00:00', '2026-06-15 00:00', 1);
        $this->book($p->id, '2026-06-10 00:00', '2026-06-15 00:00', 1);

        $this->assertSame(0, $this->availability->remainingUnits($p->id, '2026-06-11 00:00', '2026-06-13 00:00'));
        $this->assertFalse($this->availability->isAvailable($p->id, '2026-06-11 00:00', '2026-06-13 00:00', 1, 2));

        // A non-overlapping week is still fully available.
        $this->assertSame(2, $this->availability->remainingUnits($p->id, '2026-07-01 00:00', '2026-07-05 00:00'));
    }

    #[Test]
    public function checkout_day_is_free_half_open_ranges_do_not_block(): void
    {
        $p = $this->makeProduct(['capacity' => 1]);
        // Stay 10th → 12th (checkout on the 12th).
        $this->book($p->id, '2026-06-10 00:00', '2026-06-12 00:00', 1);

        // A new stay starting on the 12th must NOT be blocked by the checkout.
        $this->assertTrue($this->availability->isAvailable($p->id, '2026-06-12 00:00', '2026-06-14 00:00', 1, 1));
        // But a stay overlapping the 11th is blocked.
        $this->assertFalse($this->availability->isAvailable($p->id, '2026-06-11 00:00', '2026-06-13 00:00', 1, 1));
    }

    #[Test]
    public function pending_bookings_do_not_consume_availability(): void
    {
        $p = $this->makeProduct(['capacity' => 1]);
        $this->book($p->id, '2026-06-10 00:00', '2026-06-12 00:00', 1, Booking::STATUS_PENDING);

        // Unpaid (pending) → still available (availability locks only on pay).
        $this->assertTrue($this->availability->isAvailable($p->id, '2026-06-10 00:00', '2026-06-12 00:00', 1, 1));
    }

    #[Test]
    public function turnaround_buffer_blocks_adjacent_bookings(): void
    {
        $p = $this->makeProduct(['capacity' => 1, 'buffer_minutes' => 24 * 60]); // 1-day buffer
        $this->book($p->id, '2026-06-10 00:00', '2026-06-12 00:00', 1);

        // Without buffer the 12th would be free; with a 1-day buffer it's blocked.
        $this->assertFalse(
            $this->availability->isAvailable($p->id, '2026-06-12 00:00', '2026-06-14 00:00', 1, 1, 24 * 60)
        );
    }

    #[Test]
    public function time_slot_capacity_and_available_slots(): void
    {
        $p = $this->makeProduct([
            'booking_type' => BookingProductSetting::TYPE_TIME_SLOT,
            'capacity' => 1,
            'price_unit' => 'per_slot',
            'unit_price' => 50,
            'slot_minutes' => 60,
            'opens_at' => '09:00',
            'closes_at' => '12:00',
        ]);

        // Book the 10:00–11:00 slot.
        $this->book($p->id, '2026-06-10 10:00', '2026-06-10 11:00', 1, Booking::STATUS_CONFIRMED, 'time_slot');

        $this->assertFalse($this->availability->isAvailable($p->id, '2026-06-10 10:00', '2026-06-10 11:00', 1, 1));
        $this->assertTrue($this->availability->isAvailable($p->id, '2026-06-10 11:00', '2026-06-10 12:00', 1, 1));

        // 09–12 window, 60-min slots = 3 slots; 10:00 taken → 2 open.
        $slots = $this->availability->availableSlots($p->id, '2026-06-10');
        $starts = array_column($slots, 'start');
        $this->assertContains('2026-06-10 09:00', $starts);
        $this->assertContains('2026-06-10 11:00', $starts);
        $this->assertNotContains('2026-06-10 10:00', $starts);
    }

    #[Test]
    public function pricing_follows_the_configured_unit(): void
    {
        $nightly = new BookingProductSetting(['booking_type' => 'date_range', 'price_unit' => 'per_night', 'unit_price' => 100]);
        $this->assertSame(300.0, $this->booking->priceFor($nightly, '2026-06-10', '2026-06-13', 1)); // 3 nights
        $this->assertSame(600.0, $this->booking->priceFor($nightly, '2026-06-10', '2026-06-13', 2)); // × 2 rooms

        $hourly = new BookingProductSetting(['booking_type' => 'time_slot', 'price_unit' => 'per_hour', 'unit_price' => 40]);
        $this->assertSame(80.0, $this->booking->priceFor($hourly, '2026-06-10 09:00', '2026-06-10 11:00', 1)); // 2h

        $perSlot = new BookingProductSetting(['booking_type' => 'time_slot', 'price_unit' => 'per_slot', 'unit_price' => 50, 'slot_minutes' => 60]);
        $this->assertSame(100.0, $this->booking->priceFor($perSlot, '2026-06-10 09:00', '2026-06-10 11:00', 1)); // 2 slots

        $flat = new BookingProductSetting(['booking_type' => 'time_slot', 'price_unit' => 'flat', 'unit_price' => 25]);
        $this->assertSame(25.0, $this->booking->priceFor($flat, '2026-06-10 09:00', '2026-06-10 18:00', 1));
    }

    #[Test]
    public function validate_request_enforces_duration_and_availability(): void
    {
        $p = $this->makeProduct(['capacity' => 1, 'min_duration' => 2, 'max_duration' => 7]);

        $this->assertSame('below_min_duration', $this->availability->validateRequest($p->id, '2026-06-10', '2026-06-11', 1)['reason']);
        $this->assertSame('above_max_duration', $this->availability->validateRequest($p->id, '2026-06-10', '2026-06-20', 1)['reason']);
        $this->assertTrue($this->availability->validateRequest($p->id, '2026-06-10', '2026-06-13', 1)['ok']);

        $this->book($p->id, '2026-06-10 00:00', '2026-06-13 00:00', 1);
        $this->assertSame('unavailable', $this->availability->validateRequest($p->id, '2026-06-11', '2026-06-13', 1)['reason']);
    }

    protected function tearDown(): void
    {
        if (!empty($this->productIds)) {
            Booking::whereIn('product_id', $this->productIds)->delete();
            BookingProductSetting::whereIn('product_id', $this->productIds)->delete();
            Product::whereIn('id', $this->productIds)->forceDelete();
        }

        parent::tearDown();
    }
}
