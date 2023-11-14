<?php

namespace MicroweberPackages\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use GrahamCampbell\SecurityCore\Security;
use MicroweberPackages\Helper\HTMLClean;

class PageViewMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        event_trigger('mw.pageview', $request);

        return $next($request);
    }

}
