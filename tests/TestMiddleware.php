<?php

namespace Tests;

use Closure;

class TestMiddleware
{
    public function handle($request, Closure $next)
    {
        // Bypass all white label checks
        app()->bind('white_label', function() {
            return new class {
                public function isEnabled() { return false; }
            };
        });
        
        return $next($request);
    }
}