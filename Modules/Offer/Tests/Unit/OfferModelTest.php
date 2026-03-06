<?php

namespace Modules\Offer\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

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

    #[Test]

    public function it_offer_creation(): void {
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

    #[Test]

    public function it_offer_price_formatting(): void {
        $offer = Offer::add([
            'product_id' => 1,
            'price_id' => 1,
            'offer_price' => '19,99',
            'is_active' => 1
        ]);

        $this->assertEquals(19.99, $offer->offer_price);
    }

    #[Test]

    public function it_expiry_date_handling(): void {
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

    #[Test]

    public function it_get_price_method(): void {
        $offer = Offer::add([
            'product_id' => 1,
            'price_id' => 1,
            'offer_price' => 9.99,
            'is_active' => 1
        ]);

        $price = Offer::getPrice(1, 1);
        $this->assertEquals(9.99, $price['offer_price']);
    }

    #[Test]

    public function it_offer_deletion(): void {
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