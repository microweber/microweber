<?php

namespace Modules\Cart\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Modules\Cart\Models\Cart;

class CartModelTest extends TestCase
{
    #[Test]
    public function it_cart_guarded_fields(): void {
        // AI-81 / TICKET-AR (cycle-71 2026-05-08): Cart switched from
        // $fillable (allow-list) to $guarded (deny-list). Verify the
        // server-trust-only columns reject mass-assignment AND the
        // legitimate user-facing columns still accept it.
        $cart = new Cart();

        $expectedGuarded = [
            'id',
            'session_id',
            'user_id',
            'amount',
            'is_paid',
            'confirmed_at',
            'created_at',
            'updated_at',
        ];
        $this->assertEquals($expectedGuarded, $cart->getGuarded());

        // Negative: every guarded column rejects mass-assignment.
        foreach ($expectedGuarded as $col) {
            $this->assertFalse(
                $cart->isFillable($col),
                "Cart: \$guarded column '{$col}' must NOT be mass-assignable"
            );
        }

        // Positive: every legitimate user-facing column still accepts
        // mass-assignment via the implicit allow-rest behaviour.
        $userFacing = [
            'rel_type', 'rel_id', 'price', 'currency', 'qty',
            'order_id', 'order_completed',
            'description', 'link', 'other_info', 'custom_fields_data',
        ];
        foreach ($userFacing as $col) {
            $this->assertTrue(
                $cart->isFillable($col),
                "Cart: legitimate user-facing column '{$col}' must remain mass-assignable"
            );
        }
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
