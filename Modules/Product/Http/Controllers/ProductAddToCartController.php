<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Modules\Content\Models\Content;

/**
 * Add a product to the cart by visiting a shareable link:
 *   GET /add-to-cart/{id}?qty=N
 *
 * The cart is session-backed, so the route runs in the web middleware group.
 * Price/title are resolved server-side by CartService::updateCart() from the
 * content id — the link never carries a price.
 */
class ProductAddToCartController extends Controller
{
    public function add(Request $request, $id)
    {
        $id = (int) $id;
        $qty = (int) $request->query('qty', 1);
        if ($qty < 1) {
            $qty = 1;
        }

        $product = Content::query()
            ->where('id', $id)
            ->where('content_type', 'product')
            ->first();

        if (!$product) {
            abort(404);
        }

        $result = app('cart_service')->updateCart([
            'content_id' => $id,
            'for' => morph_name(Content::class),
            'for_id' => $id,
            'qty' => $qty,
        ]);

        $cartUrl = Route::has('cart') ? route('cart') : url('cart');

        if (is_array($result) && isset($result['error'])) {
            return redirect($cartUrl)->with('mw-cart-error', $result['error']);
        }

        return redirect($cartUrl)->with('mw-cart-success', true);
    }
}
