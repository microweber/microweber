<?php

namespace Modules\Cart\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Cart\Models\UserCart;
use Modules\Cart\Scopes\UserCartScope;

class UserCartTest extends TestCase
{
    #[Test]
    public function it_user_cart_has_global_scope(): void {
        $userCart = new UserCart();

        $globalScopes = $userCart->getGlobalScopes();

        $this->assertArrayHasKey(UserCartScope::class, $globalScopes);
        $this->assertInstanceOf(UserCartScope::class, $globalScopes[UserCartScope::class]);
    }

    #[Test]

    public function it_user_cart_inherits_from_cart(): void {
        $userCart = new UserCart();

        $this->assertInstanceOf(\Modules\Cart\Models\Cart::class, $userCart);
    }

    #[Test]

    public function it_user_cart_has_same_attributes_as_cart(): void {
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
