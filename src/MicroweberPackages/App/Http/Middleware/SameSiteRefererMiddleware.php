<?php

namespace MicroweberPackages\App\Http\Middleware;

use Illuminate\Support\Str;

use Closure;
use Illuminate\Http\Request;

class SameSiteRefererMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $full_url = $request->headers->get('referer');
        if (!$full_url) {
            $error = 'You are not allowed to make requests from this domain';
            return abort(403, $error);
        }
        if ($full_url) {
            $result = $this->isSameSite($full_url);
            if (!$result) {
                $error = 'You are not allowed to make requests from this address';
                return abort(403, $error);
            }
        }
        return $next($request);

    }

    public function isSameSite($url)
    {
        return Str::startsWith($url, site_url());
    }


}

