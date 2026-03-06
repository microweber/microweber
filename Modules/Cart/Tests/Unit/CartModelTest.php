<?php

namespace Modules\Cart\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Cart\Models\Cart;

class CartModelTest extends TestCase
{
    #[Test]
    public function it_cart_fillable_fields(): void {
        $cart = new Cart();

        $expectedFillable = [
            'rel_type',
            'rel_id',

            'price',
            'currency',
            'qty',

            'order_id',
            'order_completed',

            'description',
            'link',
            'other_info',
            'custom_fields_data',
        ];

        $this->assertEquals($expectedFillable, $cart->getFillable());
    }

    #[Test]

    public function it_custom_fields_json_casting(): void {
        $cart = new Cart();
        $cart->fill(['custom_fields_data' => ['color' => 'red', 'size' => 'large']]);
        $cart->save();

        $find = Cart::find($cart->id);

        $this->assertIsArray($find->custom_fields_data);
        $this->assertEquals('red', $find->custom_fields_data['color']);
        $this->assertEquals('large', $find->custom_fields_data['size']);
    }

    #[Test]

    public function it_order_relationship(): void {
        $cart = new Cart();

        $this->assertTrue(method_exists($cart, 'order'));
        $relation = $cart->order();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class, $relation);

        // Test the relationship keys based on actual implementation
        $this->assertEquals('order_id', $relation->getLocalKeyName()); // The key on the Cart model
        $this->assertEquals('id', $relation->getForeignKeyName()); // The key on the Order model
    }

    #[Test]

    public function it_products_relationship(): void {
        $cart = new Cart();

        $this->assertTrue(method_exists($cart, 'products'));
        $relation = $cart->products();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relation);

        // Test the relationship keys based on actual implementation
        $this->assertEquals('rel_id', $relation->getLocalKeyName()); // The key on the Cart model
        $this->assertEquals('id', $relation->getForeignKeyName()); // The key on the Product model
    }
}
