<?php

namespace MicroweberPackages\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use GrahamCampbell\SecurityCore\Security;

class XSS
{
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$input) {
            if (is_string($input)) {
                $input = Security::create()->clean($input);
            }
        });

        $request->merge($input);
        return $next($request);
    }

}