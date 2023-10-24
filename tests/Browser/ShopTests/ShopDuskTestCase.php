<?php

namespace Tests\Browser\ShopTests;

use Laravel\Dusk\Browser;
use MicroweberPackages\Order\Models\Order;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;
use function app;
use function get_option;
use function save_option;
use function Tests\Browser\str_contains;

class ShopDuskTestCase extends DuskTestCase
{

    protected function assertPreConditions(): void
    {

        parent::assertPreConditions();

        $save = array(
            'option_key' => 'shipping_gw_shop/shipping/gateways/country',
            'option_group' => 'shipping',
            'option_value' => 'y'
        );
        save_option($save);


        // enable paypal
        $option = array();
        $option['option_value'] = '1';
        $option['option_key'] = 'payment_gw_shop/payments/gateways/paypal';
        $option['option_group'] = 'payments';
        $option['module'] = 'shop/payments';
        save_option($option);

        // enable bank_transfer
        $option = array();
        $option['option_value'] = '1';
        $option['option_key'] = 'payment_gw_shop/payments/gateways/bank_transfer';
        $option['option_group'] = 'payments';
        $option['module'] = 'shop/payments';
        save_option($option);


        $option = array();
        $option['option_value'] = 'y';
        $option['option_key'] = 'paypalexpress_testmode';
        $option['option_group'] = 'payments';
        save_option($option);


        $option = array();
        $option['option_value'] = 'info@microweber.com';
        $option['option_key'] = 'paypalexpress_username';
        $option['option_group'] = 'payments';
        save_option($option);


        $option = array();
        $option['option_value'] = 'n';
        $option['option_key'] = 'is_active';
        $option['option_group'] = 'multilanguage_settings';
        save_option($option);
    }


    protected function _browserToShopAndAddTocart($browser)
    {
        $shop_page = app()->content_repository->getFirstShopPage();
        if (!$shop_page) {
            $title = 'Shop';
            $shop_page = array(
                'title' => $title,
                'content_type' => 'page',
                'layout_file' => 'layouts/shop.php',
                'is_shop' => 1,
                'is_active' => 1
            );
            $saved_id_shop = save_content($shop_page);
        } else {
            $saved_id_shop = $shop_page['id'];
        }

        $title = 'Product Title ' . time();

        $params = [
            'title' =>$title,
            'parent' => $saved_id_shop,
            'content_type' => 'product',
            'content' => '<p>Product content</p>',
            'subtype' => 'product',
            'custom_fields_advanced' => array(
                array('type'=>'dropdown','name'=>'Color', 'value' => array('Purple','Blue')),
                array('type'=>'price','name'=>'Price', 'value' => '9.99'),

            ),
            'is_active' => 1,
        ];
        app()->database_manager->extended_save_set_permission(true);

        $saved_id = save_content($params);
        $browser->pause(500);
        $link = content_link($saved_id);

        $browser->visit($link );


//        $browser->waitForText('Shop');
//        $browser->clickLink('Shop');
//
        $browser->waitForText($title);

        $browser->within(new ChekForJavascriptErrors(), function ($browser) {
            $browser->validate();
        });


        //    $browser->script('$(".name-of-product-shop").first().click()');
        //

        $browser->click('.price button');
        $browser->pause(500);
        $browser->waitForText('Continue shopping',30);
        //   $browser->assertSee('Proceed to Checkout');
//
        //    $browser->seeLink('Proceed to Checkout');
        $browser->clickLink('Proceed to Checkout');
        $browser->pause(3000);

    }


    protected function _browserToCheckoutAndFillShippingInfo($browser, $uniqueId)
    {
        $browser->waitForText('First Name');
        $browser->type('first_name', 'Bozhidar' . $uniqueId);
        $browser->type('last_name', 'Slaveykov' . $uniqueId);
        $browser->type('email', 'bobi' . $uniqueId . '@microweber.com');
        $browser->type('phone', $uniqueId);
        $browser->click('.js-checkout-continue');


        $browser->pause(2000);
        $browser->waitForText('Shipping method');

        $browser->radio('shipping_gw', 'shop/shipping/gateways/country');

        $browser->pause(7000);
        $browser->waitForText('Address for delivery');
        $browser->assertSee('Address for delivery');

        $browser->select('country', 'Bulgaria');
        $browser->type('Address[city]', 'Sofia' . $uniqueId);
        $browser->type('Address[zip]', '1000' . $uniqueId);
        $browser->type('Address[state]', 'Sofia' . $uniqueId);
        $browser->type('Address[address]', 'Vitosha 143' . $uniqueId);
        $browser->type('other_info', 'I want my order soon as posible.' . $uniqueId);

        $browser->scrollTo('.js-checkout-continue');

        $browser->pause(1000);

        $browser->click('.js-checkout-continue');

        $browser->waitForText('Payment method');
        $browser->assertSee('Payment method');
        $browser->pause(1000);

    }

}
