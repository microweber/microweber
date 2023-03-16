<?php

namespace MicroweberPackages\LiveEdit\Http\Middleware;

use Illuminate\Http\Request;
use MicroweberPackages\LiveEdit\Events\ServingLiveEdit;

class DispatchServingLiveEdit
{
    public function handle(Request $request, \Closure $next)
    {
        ServingLiveEdit::dispatch();

        return $next($request);
    }
}
