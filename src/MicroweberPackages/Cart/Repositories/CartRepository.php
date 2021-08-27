<?php

namespace MicroweberPackages\Cart\Repositories;


use MicroweberPackages\Cart\Models\Cart;
use MicroweberPackages\Repository\Repositories\AbstractRepository;


class CartRepository extends AbstractRepository
{

    public $model = Cart::class;

    public function getCartItems()
    {
        $sid = app()->user_manager->session_id();

        //return $this->cacheCallback(__FUNCTION__, [$sid], function () use ($sid) {

            $cartItems = \DB::table('cart')
                // ->select(['id', 'qty'])
                ->where('order_completed', 0)
                ->where('session_id', $sid)
                ->get();

            $cartItems = collect($cartItems)->map(function ($option) {
                return (array)$option;
            })->toArray();

            return $cartItems;
      //  });

    }

    public function getCartAmount()
    {
        $amount = 0;
        $sumq = $this->getCartItems();

        if (is_array($sumq)) {
            foreach ($sumq as $value) {
                $amount = $amount + (intval($value['qty']) * floatval($value['price']));
            }
        }
        return $amount;
    }

    public function getCartItemsCount()
    {

        $sumq = $this->getCartItems();

        $different_items = 0;

        if (is_array($sumq)) {
            foreach ($sumq as $value) {
                $different_items = $different_items + $value['qty'];
            }
        }

        return $different_items;

    }
}
