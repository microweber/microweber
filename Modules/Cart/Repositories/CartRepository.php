<?php

namespace Modules\Cart\Repositories;


use MicroweberPackages\Repository\Repositories\AbstractRepository;
use Modules\Cart\Models\Cart;


class CartRepository extends AbstractRepository
{

    public string $model = Cart::class;

    public function getCartItems()
    {
        $sid = app()->user_manager->session_id();

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($sid) {
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
