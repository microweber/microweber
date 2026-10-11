<?php

namespace MicroweberPackages\LiveEdit\Http\Middleware;

use Illuminate\Http\Request;
use MicroweberPackages\LiveEdit\Events\ServingModuleSettings;

/**
 * @deprecated
 */
class DispatchServingModuleSettings
{
    public function handle(Request $request, \Closure $next)
    {
        ServingModuleSettings::dispatch();

        return $next($request);
    }
}
