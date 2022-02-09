<?php

namespace MicroweberPackages\Checkout\Http\Middleware;

use Closure;

class CheckoutV2
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $checkCart = cart_get_items_count();

        if (!$checkCart) {
            //$shop_page = get_content('single=true&content_type=page&is_shop=1');
            $shop_page = app()->content_repository->getFirstShopPage();

            $redir = site_url();
            if ($shop_page and isset($shop_page['id'])) {
                $link = content_link($shop_page['id']);
                if ($link) {
                    $redir = $link;
                }

            }

            return redirect($redir);
        }

        $requiresRegistration = get_option('shop_require_registration', 'website') == '1';
        if ($requiresRegistration and is_logged() == false) {
            return redirect(route('checkout.login'));
        }

        return $next($request);
    }
}
