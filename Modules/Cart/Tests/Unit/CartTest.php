<?php

namespace Modules\Cart\Tests\Unit;

use Modules\Cart\Models\Cart;
use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

class CartTest extends TestCase
{
    public static $content_id = 1;

    protected function tearDown(): void
    {
        empty_cart();
        Cart::query()
            ->where('session_id', app()->user_manager->session_id())
            ->delete();

        parent::tearDown();
    }

    #[Test]

    public function it_add_to_cart(): void {
        empty_cart();
        app()->database_manager->extended_save_set_permission(true);

        $params = array(
            'title' => 'My new product',
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => array(
                array('type' => 'dropdown', 'name' => 'Color', 'value' => array('Purple', 'Blue')),
                array('type' => 'price', 'name' => 'Price', 'value' => '30'),

            ),
            'is_active' => 1,);


        $saved_id = save_content($params);
        $get = get_content_by_id($saved_id);

        $this->assertEquals($saved_id, ($get['id']));
        self::$content_id = $saved_id;

        $add_to_cart = array(
            'content_id' => self::$content_id,
            'color' => 'Purple',
            'non_existing' => 'must_not_be_added'
            // 'price' => 30,
        );
        $cart_add = update_cart($add_to_cart);
        $this->assertEquals(isset($cart_add['success']), true);
        $this->assertEquals(isset($cart_add['product']), true);
        $this->assertEquals($cart_add['product']['price'], 30);
        $this->assertEquals($cart_add['product']['custom_fields_data']['color'], 'Purple');
        $this->assertEquals(isset($cart_add['product']['custom_fields_data']['non_existing']), false);

        $cart_items = get_cart();
        $this->assertEquals($cart_items[0]['qty'], 1);

        $cart_add = update_cart($add_to_cart);
        $cart_items = get_cart();
        $this->assertEquals($cart_items[0]['qty'], 2);


    }

    #[Test]

    public function it_add_to_cart_not_a_product(): void {
        //  empty_cart();
        app()->database_manager->extended_save_set_permission(true);

        $params = array(
            'title' => 'My page',
            'content_type' => 'page',
            'subtype' => 'static',

            'is_active' => 1,);


        $saved_id = save_content($params);
        $get = get_content_by_id($saved_id);

        $this->assertEquals($saved_id, ($get['id']));

        $add_to_cart = array(
            'content_id' => $saved_id,

        );
        $cart_add = update_cart($add_to_cart);
        $this->assertEquals(isset($cart_add['error']), true);

    }

    #[Test]

    public function it_get_cart(): void {
        empty_cart();
        app()->database_manager->extended_save_set_permission(true);

        // Create a product first (needed when tests run in separate processes)
        $params = array(
            'title' => 'Test product for get_cart',
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => array(
                array('type' => 'price', 'name' => 'Price', 'value' => '30'),
            ),
            'is_active' => 1,
        );
        $saved_id = save_content($params);

        $add_to_cart = array(
            'content_id' => $saved_id,
            'qty' => 2,
            'price' => 350,
        );
        $cart_add = update_cart($add_to_cart);
        $cart_items = get_cart();

        $this->assertEquals(isset($cart_add['success']), true);
        $this->assertEquals(!empty($cart_items), true);
    }

    #[Test]

    public function it_sum_cart(): void {
        empty_cart();
        app()->database_manager->extended_save_set_permission(true);

        // Create a product first (needed when tests run in separate processes)
        $params = array(
            'title' => 'Test product for sum_cart',
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => array(
                array('type' => 'price', 'name' => 'Price', 'value' => '30'),
            ),
            'is_active' => 1,
        );
        $saved_id = save_content($params);

        $add_to_cart = array(
            'content_id' => $saved_id,
            'qty' => 3,
            'price' => 1300, // wrong price on purpose
        );
        $cart_add = update_cart($add_to_cart);
        $cart_items = get_cart();

        $sum = cart_sum();
        $this->assertEquals($sum, 90);

        $this->assertEquals(isset($cart_add['success']), true);
        $this->assertEquals(!empty($cart_items), true);
    }

    #[Test]
    public function it_ignores_attacker_supplied_price_and_rel_type_for_content_products(): void
    {
        empty_cart();
        app()->database_manager->extended_save_set_permission(true);

        $productId = save_content([
            'title' => 'Canonical Price Product',
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => [
                ['type' => 'price', 'name' => 'Price', 'value' => '30'],
            ],
            'is_active' => 1,
        ]);

        $cartAdd = update_cart([
            'content_id' => $productId,
            'rel_type' => 'customer',
            'rel_id' => 999999,
            'price' => '9999.99',
            'qty' => 1,
        ]);

        $this->assertArrayHasKey('success', $cartAdd);
        $this->assertSame(30.0, (float) $cartAdd['product']['price']);

        $cartItem = Cart::query()->latest('id')->first();
        $this->assertNotNull($cartItem);
        $this->assertSame(morph_name(\Modules\Content\Models\Content::class), $cartItem->rel_type);
        $this->assertSame($productId, (int) $cartItem->rel_id);
        $this->assertSame(30.0, (float) $cartItem->price);
    }

    #[Test]
    public function it_rejects_unexpected_cart_relation_types(): void
    {
        empty_cart();

        $result = update_cart([
            'rel_type' => 'customer',
            'rel_id' => 123,
            'price' => 10,
        ]);

        $this->assertArrayHasKey('error', $result);
        $this->assertSame('Invalid cart item type', $result['error']);
    }

    #[Test]
    public function it_surfaces_stock_limit_warnings_instead_of_silent_caps(): void
    {
        empty_cart();
        app()->database_manager->extended_save_set_permission(true);

        $productId = save_content([
            'title' => 'Limited Stock Product',
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => [
                ['type' => 'price', 'name' => 'Price', 'value' => '50'],
            ],
            'data_qty' => 2,
            'is_active' => 1,
        ]);

        $result = update_cart([
            'content_id' => $productId,
            'qty' => 5,
        ]);

        $this->assertArrayHasKey('success', $result);
        $this->assertSame(2, $result['cart_items_quantity']);
        $this->assertArrayHasKey('warnings', $result);
        $this->assertSame('stock_limit', $result['warnings'][0]['code']);
        $this->assertSame(5, $result['warnings'][0]['requested_quantity']);
        $this->assertSame(2, $result['warnings'][0]['adjusted_quantity']);
    }

    #[Test]
    public function it_surfaces_max_qty_warnings_when_updating_quantity(): void
    {
        empty_cart();
        app()->database_manager->extended_save_set_permission(true);

        $productId = save_content([
            'title' => 'Max Qty Product',
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => [
                ['type' => 'price', 'name' => 'Price', 'value' => '20'],
            ],
            'data_max_qty_per_order' => 3,
            'is_active' => 1,
        ]);

        $addResult = update_cart([
            'content_id' => $productId,
            'qty' => 1,
        ]);

        $updateResult = update_cart_item_qty([
            'id' => $addResult['product']['cart_item_id'],
            'qty' => 9,
        ]);

        $this->assertArrayHasKey('success', $updateResult);
        $this->assertArrayHasKey('warnings', $updateResult);
        $this->assertSame('max_qty_per_order', $updateResult['warnings'][0]['code']);
        $this->assertSame(9, $updateResult['warnings'][0]['requested_quantity']);
        $this->assertSame(3, $updateResult['warnings'][0]['adjusted_quantity']);
        $this->assertSame(3, (int) get_cart()[0]['qty']);
    }

    #[Test]
    public function it_keeps_variants_separate_by_custom_fields_data(): void
    {
        empty_cart();
        app()->database_manager->extended_save_set_permission(true);

        $productId = save_content([
            'title' => 'Variant Product',
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => [
                ['type' => 'dropdown', 'name' => 'Color', 'value' => ['Purple', 'Blue']],
                ['type' => 'price', 'name' => 'Price', 'value' => '30'],
            ],
            'is_active' => 1,
        ]);

        update_cart([
            'content_id' => $productId,
            'color' => 'Purple',
        ]);

        update_cart([
            'content_id' => $productId,
            'color' => 'Blue',
        ]);

        $cartItems = get_cart();

        $this->assertCount(2, $cartItems);
        $colors = array_map(static fn ($item) => $item['custom_fields_data']['color'] ?? null, $cartItems);
        sort($colors);
        $this->assertSame(['Blue', 'Purple'], $colors);
    }

}
