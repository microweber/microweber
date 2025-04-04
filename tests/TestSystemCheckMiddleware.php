<?php

namespace Tests;

use Closure;

class TestSystemCheckMiddleware
{
    public function handle($request, Closure $next)
    {
        // Force enable all required features
        config(['shop.enable_coupons' => true]);
        config(['shop.enable_taxes' => false]);
        config(['white_label.enabled' => false]);
        
        return $next($request);
    }
}