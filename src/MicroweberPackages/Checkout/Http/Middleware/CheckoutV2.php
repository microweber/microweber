<?php

namespace MicroweberPackages\Checkout\Http\Middleware;

use Closure;

class CheckoutV2
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $checkCart = get_cart();
        if (!$checkCart) {
            return redirect(site_url('shop'));
        }

        $requiresRegistration = get_option('shop_require_registration', 'website') == '1';
        if ($requiresRegistration and is_logged() == false) {
            return redirect(route('checkout.login'));
        }

        return $next($request);
    }
}
