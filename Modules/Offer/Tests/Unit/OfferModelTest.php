<?php

namespace Modules\Offer\Tests\Unit;

use Tests\TestCase;
use Modules\Offer\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OfferModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate', ['--database' => 'sqlite']);
    }

    public function test_offer_creation()
    {
        $offer = Offer::add([
            'product_id' => 1,
            'price_id' => 1,
            'offer_price' => 9.99,
            'is_active' => 1
        ]);

        $this->assertDatabaseHas('offers', [
            'id' => $offer->id,
            'offer_price' => 9.99
        ]);
    }

    public function test_offer_price_formatting()
    {
        $offer = Offer::add([
            'product_id' => 1,
            'price_id' => 1,
            'offer_price' => '19,99',
            'is_active' => 1
        ]);

        $this->assertEquals(19.99, $offer->offer_price);
    }

    public function test_expiry_date_handling()
    {
        $offer = Offer::add([
            'product_id' => 1,
            'price_id' => 1,
            'offer_price' => 9.99,
            'expires_at' => '2025-12-31',
            'is_active' => 1
        ]);

        $this->assertDatabaseHas('offers', [
            'id' => $offer->id,
            'expires_at' => '2025-12-31 00:00:00'
        ]);
    }

    public function test_get_price_method()
    {
        $offer = Offer::add([
            'product_id' => 1,
            'price_id' => 1,
            'offer_price' => 9.99,
            'is_active' => 1
        ]);

        $price = Offer::getPrice(1, 1);
        $this->assertEquals(9.99, $price['offer_price']);
    }

    public function test_offer_deletion()
    {
        $offer = Offer::add([
            'product_id' => 1,
            'price_id' => 1,
            'offer_price' => 9.99,
            'is_active' => 1
        ]);

        $result = Offer::deleteById($offer->id);
        $this->assertTrue($result);
        $this->assertDatabaseMissing('offers', ['id' => $offer->id]);
    }
}