<?php

namespace Modules\Product\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * The shareable add-to-cart link (GET /add-to-cart/{id}) adds the product to
 * the session cart and redirects to the cart page; a bad id 404s.
 */
class AddToCartLinkTest extends TestCase
{
    protected array $productIds = [];

    protected function makeProduct(): Product
    {
        $p = new Product();
        $p->title = 'atc-' . uniqid();
        $p->url = $p->title;
        $p->content_type = 'product';
        $p->subtype = 'product';
        $p->save();
        $this->productIds[] = $p->id;

        return $p;
    }

    #[Test]
    public function link_adds_the_product_to_the_cart_and_redirects(): void
    {
        $product = $this->makeProduct();

        $response = $this->get('/add-to-cart/' . $product->id);

        $response->assertRedirect(route('cart'));
        $this->assertDatabaseHas('cart', ['rel_id' => $product->id, 'order_completed' => 0]);
    }

    #[Test]
    public function link_respects_the_qty_query_param(): void
    {
        $product = $this->makeProduct();

        $this->get('/add-to-cart/' . $product->id . '?qty=3')->assertRedirect(route('cart'));

        $qty = DB::table('cart')->where('rel_id', $product->id)->where('order_completed', 0)->value('qty');
        $this->assertEquals(3, (int) $qty);
    }

    #[Test]
    public function unknown_or_non_product_id_returns_404(): void
    {
        $this->get('/add-to-cart/99999999')->assertNotFound();
    }

    protected function tearDown(): void
    {
        if (!empty($this->productIds)) {
            DB::table('cart')->whereIn('rel_id', $this->productIds)->delete();
            Product::whereIn('id', $this->productIds)->forceDelete();
        }

        parent::tearDown();
    }
}
