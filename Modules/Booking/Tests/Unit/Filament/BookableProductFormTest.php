<?php

namespace Modules\Booking\Tests\Unit\Filament;

use Livewire\Livewire;
use Modules\Booking\Models\BookingProductSetting;
use Modules\Product\Filament\Admin\Resources\ProductResource\Pages\CreateProduct;
use Modules\Product\Filament\Admin\Resources\ProductResource\Pages\EditProduct;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

/**
 * The "Bookable product" toggle + booking settings section on the Product form:
 * it maps to the booking_product subtype and round-trips the per-product
 * settings row (booking_product_settings).
 */
class BookableProductFormTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected ?Product $product = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function create_form_exposes_the_booking_fields(): void
    {
        Livewire::test(CreateProduct::class)
            ->assertSuccessful()
            ->assertFormFieldExists('is_bookable')
            ->assertFormFieldExists('booking_type')
            ->assertFormFieldExists('booking_capacity')
            ->assertFormFieldExists('booking_price_unit');
    }

    #[Test]
    public function creating_a_bookable_product_persists_the_subtype_and_settings(): void
    {
        $title = 'Room ' . uniqid();

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'title' => $title,
                'price' => 120,
                'is_bookable' => true,
                'booking_type' => 'date_range',
                'booking_capacity' => 3,
                'booking_price_unit' => 'per_night',
                'booking_unit_price' => 120,
                'booking_min_duration' => 2,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->product = Product::where('title', $title)->first();
        $this->assertNotNull($this->product, 'Product should have been created');
        $this->assertSame('booking_product', $this->product->subtype);

        $setting = BookingProductSetting::where('product_id', $this->product->id)->first();
        $this->assertNotNull($setting, 'A booking settings row should be created');
        $this->assertSame('date_range', $setting->booking_type);
        $this->assertSame(3, (int) $setting->capacity);
        $this->assertSame('per_night', $setting->price_unit);
        $this->assertSame('120.0000', (string) $setting->unit_price);
        $this->assertSame(2, (int) $setting->min_duration);
        $this->assertTrue((bool) $setting->is_active);
    }

    #[Test]
    public function edit_form_reflects_a_bookable_products_settings(): void
    {
        $this->product = new Product(['subtype' => 'booking_product']);
        $this->product->title = 'Suite ' . uniqid();
        $this->product->url = $this->product->title;
        $this->product->content_type = 'product';
        $this->product->save();

        BookingProductSetting::create([
            'product_id' => $this->product->id,
            'booking_type' => 'time_slot',
            'capacity' => 2,
            'price_unit' => 'per_hour',
            'unit_price' => 40,
            'slot_minutes' => 60,
            'is_active' => true,
        ]);

        Livewire::test(EditProduct::class, ['record' => $this->product->id])
            ->assertSuccessful()
            ->assertFormSet([
                'is_bookable' => true,
                'booking_type' => 'time_slot',
                'booking_capacity' => 2,
                'booking_price_unit' => 'per_hour',
                'booking_slot_minutes' => 60,
            ]);
    }

    protected function tearDown(): void
    {
        if ($this->product) {
            BookingProductSetting::where('product_id', $this->product->id)->delete();
            $this->product->forceDelete();
        }

        parent::tearDown();
    }
}
