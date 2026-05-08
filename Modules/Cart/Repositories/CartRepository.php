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
        // AI-81 / TICKET-AR (cycle-71 2026-05-08): route through the
        // SQL-side aggregator so we don't materialise the cart rows
        // just to add up two columns. The cached cart-items result
        // is still consulted as a fast path — if it's already in the
        // request cache we use the in-memory version (avoiding a
        // second DB query for the price total when the items list
        // was already needed earlier in the same request).
        $cartItems = $this->getCartItems();
        if (is_array($cartItems) && !empty($cartItems)) {
            return Cart::queryCartAmount($cartItems);
        }

        $sid = app()->user_manager->session_id();

        return $this->cached(__FUNCTION__, func_get_args(), function () use ($sid) {
            return Cart::queryCartAmountForSession($sid);
        });
    }

    public function getCartItemsCount()
    {
        // AI-81 / TICKET-AR (cycle-71 2026-05-08): same SQL-side
        // aggregator pattern as getCartAmount() — see comment there.
        $cartItems = $this->getCartItems();
        if (is_array($cartItems) && !empty($cartItems)) {
            return Cart::queryCartItemsCount($cartItems);
        }

        $sid = app()->user_manager->session_id();

        return $this->cached(__FUNCTION__, func_get_args(), function () use ($sid) {
            return Cart::queryCartItemsCountForSession($sid);
        });
    }
}
