<?php

namespace Modules\Cart\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class CartApiController extends Controller
{
    public function updateCart(Request $request)
    {


         return update_cart($request->all());
    }

    public function removeCartItem(Request $request)
    {
        return remove_cart_item($request->all());
    }

    public function updateCartItemQty(Request $request)
    {
        return update_cart_item_qty($request->all());
    }
}
