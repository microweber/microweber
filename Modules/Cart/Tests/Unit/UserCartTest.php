<?php

namespace Modules\Cart\Tests\Unit;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Cart\Models\UserCart;
use Modules\Cart\Scopes\UserCartScope;

class UserCartTest extends TestCase
{
    public function testUserCartHasGlobalScope()
    {
        $userCart = new UserCart();

        $globalScopes = $userCart->getGlobalScopes();

        $this->assertArrayHasKey(UserCartScope::class, $globalScopes);
        $this->assertInstanceOf(UserCartScope::class, $globalScopes[UserCartScope::class]);
    }

    public function testUserCartInheritsFromCart()
    {
        $userCart = new UserCart();

        $this->assertInstanceOf(\Modules\Cart\Models\Cart::class, $userCart);
    }

    public function testUserCartHasSameAttributesAsCart()
    {
        $userCart = new UserCart();

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

        $this->assertEquals($expectedFillable, $userCart->getFillable());
    }
}
