<?php

namespace Modules\Booking\Tests\Unit\Filament;

use Livewire\Livewire;
use Modules\Booking\Filament\Resources\BookingResource\Pages\ListBookings;
use Modules\Booking\Models\Booking;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class BookingResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected ?Booking $booking = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    private function makeBooking(string $status = Booking::STATUS_CONFIRMED): Booking
    {
        return $this->booking = Booking::create([
            'product_id' => 999000001,
            'booking_type' => 'date_range',
            'starts_at' => '2026-12-10 14:00',
            'ends_at' => '2026-12-13 10:00',
            'units' => 1,
            'status' => $status,
            'customer_email' => 'guest@example.com',
            'price' => 300,
            'currency' => 'USD',
        ]);
    }

    #[Test]
    public function list_page_renders(): void
    {
        $this->makeBooking();
        Livewire::test(ListBookings::class)->assertSuccessful();
    }

    #[Test]
    public function check_in_then_check_out_transitions_status(): void
    {
        $b = $this->makeBooking(Booking::STATUS_CONFIRMED);

        Livewire::test(ListBookings::class)
            ->callTableAction('check_in', $b)
            ->assertHasNoTableActionErrors();
        $this->assertSame(Booking::STATUS_CHECKED_IN, $b->fresh()->status);
        $this->assertNotNull($b->fresh()->checked_in_at);

        Livewire::test(ListBookings::class)
            ->callTableAction('check_out', $b)
            ->assertHasNoTableActionErrors();
        $this->assertSame(Booking::STATUS_CHECKED_OUT, $b->fresh()->status);
        $this->assertNotNull($b->fresh()->checked_out_at);
    }

    #[Test]
    public function cancel_releases_the_booking(): void
    {
        $b = $this->makeBooking(Booking::STATUS_CONFIRMED);

        Livewire::test(ListBookings::class)
            ->callTableAction('cancel_booking', $b)
            ->assertHasNoTableActionErrors();

        $this->assertSame(Booking::STATUS_CANCELLED, $b->fresh()->status);
        $this->assertFalse($b->fresh()->isConsuming());
    }

    protected function tearDown(): void
    {
        if ($this->booking) {
            $this->booking->delete();
        }

        parent::tearDown();
    }
}
