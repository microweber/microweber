<?php
namespace Modules\Tax\Tests;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Checkout\Repositories\CheckoutManager;
use Modules\Coupons\Models\Coupon;
use Modules\Shop\Tests\Unit\ShopTestHelperTrait;
use Modules\Tax\Models\TaxType;

class TaxCartTest extends TestCase
{
     use ShopTestHelperTrait;
    public function testTaxAppliedOnCheckout()
    {
        TaxType::truncate();
        Coupon::truncate();
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


        $this->assertSame($checkoutStatus['amount'], floatval('61.0'));
        $this->assertSame($checkoutStatus['taxes_amount'], floatval('11.00'));

    }
}
