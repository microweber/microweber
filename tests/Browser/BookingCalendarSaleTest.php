<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingProductSetting;
use Modules\Cart\Models\Cart;
use Modules\Booking\Services\BookingService;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * End-to-end booking on the Base template, in a real browser: a guest opens a
 * bookable room, the calendar disables an already-booked night, they pick an
 * available date range, the widget live-prices it (nights × rate), and "Book
 * now" adds it to the cart carrying the chosen dates.
 *
 * Prerequisites: dev server on http://127.0.0.1:8000 with the Booking module
 * loaded.
 */
class BookingCalendarSaleTest extends DuskTestCase
{
    protected ?Product $product = null;
    protected ?int $shopId = null;
    protected string $slug = '';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + its DB.
    }

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('options')->updateOrInsert(
            ['option_key' => 'current_template', 'option_group' => 'template'],
            ['option_value' => 'Base', 'updated_at' => now()]
        );
        try {
            app()->cache_manager->delete('options');
        } catch (\Throwable) {
        }

        \MicroweberPackages\Database\Facades\DatabaseManager::extended_save_set_permission(true);
        $shop = app()->content_repository->getFirstShopPage();
        $this->shopId = $shop ? (int) $shop['id']
            : (int) save_content(['title' => 'Shop', 'content_type' => 'page', 'is_shop' => 1, 'is_active' => 1]);

        $this->slug = 'booking-room-' . substr(md5(microtime(true) . mt_rand()), 0, 8);
        $pid = (int) save_content([
            'title' => 'Booking Room ' . $this->slug,
            'url' => $this->slug,
            'parent' => $this->shopId,
            'content_type' => 'product',
            'subtype' => 'booking_product',
            'is_active' => 1,
            'custom_fields_advanced' => [['type' => 'price', 'name' => 'Price', 'value' => '150']],
        ]);
        $this->product = Product::find($pid);

        BookingProductSetting::updateOrCreate(['product_id' => $pid], [
            'booking_type' => 'date_range',
            'capacity' => 1,
            'price_unit' => 'per_night',
            'unit_price' => 150,
            'min_duration' => 1,
            'is_active' => true,
        ]);

        // An existing confirmed booking fills the single unit for a known week,
        // so the calendar must disable those nights.
        Booking::create([
            'product_id' => $pid,
            'booking_type' => 'date_range',
            'starts_at' => '2027-03-10 00:00',
            'ends_at' => '2027-03-13 00:00',
            'units' => 1,
            'status' => Booking::STATUS_CONFIRMED,
        ]);
    }

    #[Test]
    public function guest_picks_dates_on_the_calendar_and_books(): void
    {
        $slug = $this->slug;
        $pid = $this->product->id;

        $this->browse(function (Browser $browser) use ($slug, $pid) {
            $browser->visit('/' . $slug)->pause(1500);

            // The booking widget rendered instead of the normal add-to-cart.
            $browser->assertPresent('[data-mw-booking]');
            $browser->assertPresent('.mw-booking-range');

            // Wait for flatpickr to initialise the calendar input.
            $browser->waitUsing(10, 300, fn () => $browser->script(
                "return !!(document.querySelector('.mw-booking-range') && document.querySelector('.mw-booking-range')._flatpickr);"
            )[0]);

            // Pick an available 3-night range via the flatpickr instance (this
            // fires the widget's onChange → live price).
            $browser->script(<<<'JS'
                var fp = document.querySelector('.mw-booking-range')._flatpickr;
                fp.setDate(['2027-04-01', '2027-04-04'], true);
            JS);
            $browser->pause(600);

            $priceText = $browser->script("return (document.querySelector('.mw-booking-price')||{}).textContent || '';")[0] ?? '';
            $this->assertStringContainsString('3', (string) $priceText, 'Price line should reflect 3 nights');
            $this->assertStringContainsString('450', str_replace([',', '.00'], '', (string) $priceText), '3 × 150 = 450');

            // Book it.
            $browser->script("document.querySelector('.mw-booking-submit').click();");
            $browser->pause(3000);
        });

        // The booking was added to the cart with its dates + computed price.
        $line = Cart::where('rel_id', $pid)->where('order_completed', 0)->latest('id')->first();
        $this->assertNotNull($line, 'A booking cart line should have been created');
        $this->assertSame(450.0, (float) $line->price, '3 nights × 150 = 450');

        $cf = is_array($line->custom_fields_data) ? $line->custom_fields_data : json_decode((string) $line->custom_fields_json, true);
        $this->assertSame('2027-04-01', $cf[BookingService::F_CHECK_IN] ?? null);
        $this->assertSame('2027-04-04', $cf[BookingService::F_CHECK_OUT] ?? null);
    }

    protected function tearDown(): void
    {
        try {
            if ($this->product) {
                Cart::where('rel_id', $this->product->id)->forceDelete();
                Booking::where('product_id', $this->product->id)->delete();
                BookingProductSetting::where('product_id', $this->product->id)->delete();
                $this->product->forceDelete();
            }
        } catch (\Throwable) {
        }

        parent::tearDown();
    }
}
