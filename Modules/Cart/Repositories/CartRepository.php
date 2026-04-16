<?php

namespace Modules\Cart\Repositories;


use MicroweberPackages\Repository\Repositories\CachingModelRepository;
use Modules\Cart\Models\Cart;


class CartRepository extends CachingModelRepository
{

    protected string $modelClass = Cart::class;

    public function getCartItems()
    {
        $sid = app()->user_manager->session_id();

        return $this->cached(__FUNCTION__, func_get_args(), function () use ($sid) {
            return Cart::queryCartItems($sid);
        });
    }

    public function getCartAmount()
    {
        $cartItems = $this->getCartItems();

        return Cart::queryCartAmount(is_array($cartItems) ? $cartItems : []);
    }

    public function getCartItemsCount()
    {
        $cartItems = $this->getCartItems();

        return Cart::queryCartItemsCount(is_array($cartItems) ? $cartItems : []);
    }
}
