<?php

declare(strict_types=1);

namespace Modules\Cart\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Cycle-163 / AI-204 (2026-05-10) — /cart standalone page controller.
 *
 * Microweber doesn't ship a `<module type="shop/cart">` renderer (only
 * `cart_add` is registered). The cycle-161 attempt to put `<module type=
 * "shop/cart"/>` in a Page.content body rendered empty because Page
 * bodies don't process @livewire directives.
 *
 * This controller skips both the Microweber module pipeline AND the
 * Page resolver — it returns a real blade view that uses @livewire
 * to embed the `Modules\Checkout\Livewire\CartItems` component (which
 * already has qty input + remove button + cart totals + empty state).
 *
 * The view extends the active template's master layout so the public
 * header + footer wrap correctly. Falls back to a minimal layout if
 * the active template doesn't ship a `layouts.master` view.
 */
class CartPageController extends Controller
{
    public function show(Request $request)
    {
        // BaseModuleServiceProvider::registerViews() registers the
        // module view namespace as `modules.cart` (lowercase),
        // NOT `cart::`.
        return view('modules.cart::page');
    }
}
