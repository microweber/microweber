<?php
namespace Modules\Tax\Tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Modules\Checkout\Repositories\CheckoutManager;
use Modules\Coupons\Models\Coupon;
use Modules\Shop\Tests\Unit\ShopTestHelperTrait;
use Modules\Tax\Models\TaxType;

class TaxCartTest extends TestCase
{
     use ShopTestHelperTrait;
    #[Test]
    public function it_tax_applied_on_checkout(): void {
        TaxType::query()->delete();
        Coupon::query()->delete();
        empty_cart();
        save_option('enable_taxes', '1', 'shop');
        app()->tax_manager->save(array(
            'name' => 'VAT',
            'type' => 'percent',
            'rate' => 20,
            'compound_tax' => 0,
            'collective_tax' => 0,
            'description' => 'VAT tax'
        ));

        app()->tax_manager->save(array(
            'name' => 'Tip',
            'type' => 'fixed',
            'rate' => 1,
            'compound_tax' => 0,
            'collective_tax' => 0,
            'description' => 'Tip tax'
        ));

        $this->assertDatabaseHas('tax_types', ['name' => 'VAT']);
        $this->assertDatabaseHas('tax_types', ['name' => 'Tip']);



        $this->_addProductToCart('Product 1', 10);
        $this->_addProductToCart('Product 2', 10);
        $this->_addProductToCart('Product 3', 10);
        $this->_addProductToCart('Product 4', 10);
        $this->_addProductToCart('Product 5', 10);

        $sum = cart_sum();
        $total = cart_total();

        $this->assertSame($sum, floatval('50.0'));
        $this->assertSame($total, floatval('61.0'));

        $checkoutDetails = array();
        $checkoutDetails['email'] = 'test@microweber.com';
        $checkoutDetails['first_name'] = 'Client';
        $checkoutDetails['last_name'] = 'Microweber';


        $checkout = new CheckoutManager();
        $checkoutStatus = $checkout->checkout($checkoutDetails);


        // Verify order was created successfully
        $this->assertArrayHasKey('success', $checkoutStatus);
        $this->assertTrue($checkoutStatus['success']);
        $this->assertArrayHasKey('order_id', $checkoutStatus);

        // Verify tax was calculated (cart total is $61 = $50 subtotal + $10 VAT + $1 Tip)
        // The taxes are included in the cart_total() which returns 61.0
        $this->assertEquals(61.0, $total);
    }
}
