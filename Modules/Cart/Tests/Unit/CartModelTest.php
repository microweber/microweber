<?php

namespace Modules\Cart\Tests\Unit;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Cart\Models\Cart;

class CartModelTest extends TestCase
{
    public function testCartFillableFields()
    {
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
            'custom_fields_json',
        ];

        $this->assertEquals($expectedFillable, $cart->getFillable());
    }

    public function testCustomFieldsJsonCasting()
    {
        $cart = new Cart();
        $cart->custom_fields_json = ['color' => 'red', 'size' => 'large'];

        $this->assertIsArray($cart->custom_fields_json);
        $this->assertEquals('red', $cart->custom_fields_json['color']);
        $this->assertEquals('large', $cart->custom_fields_json['size']);
    }

    public function testOrderRelationship()
    {
        $cart = new Cart();

        $this->assertTrue(method_exists($cart, 'order'));
        $relation = $cart->order();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class, $relation);

        // Test the relationship keys based on actual implementation
        $this->assertEquals('order_id', $relation->getLocalKeyName()); // The key on the Cart model
        $this->assertEquals('id', $relation->getForeignKeyName()); // The key on the Order model
    }

    public function testProductsRelationship()
    {
        $cart = new Cart();

        $this->assertTrue(method_exists($cart, 'products'));
        $relation = $cart->products();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relation);

        // Test the relationship keys based on actual implementation
        $this->assertEquals('rel_id', $relation->getLocalKeyName()); // The key on the Cart model
        $this->assertEquals('id', $relation->getForeignKeyName()); // The key on the Product model
    }
}
