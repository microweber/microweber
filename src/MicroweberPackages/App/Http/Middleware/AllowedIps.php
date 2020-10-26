<?php

namespace MicroweberPackages\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllowedIps
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::check() &&  Auth::user()->is_admin == 1) {
            $allowedIps = config('microweber.admin_allowed_ips');
            if ($allowedIps) {
                $allowedIps = explode(',', $allowedIps);
                $allowedIps = array_trim($allowedIps);
                if (!empty($allowedIps)) {
                    $isAllowed = false;
                    foreach ($allowedIps as $allowedIp) {
                        $is = \Symfony\Component\HttpFoundation\IpUtils::checkIp(user_ip(), $allowedIp);
                        if ($is) {
                            $isAllowed = $is;
                        }
                    }
                    if (!$isAllowed) {

                        Auth::logout();

                        $error = 'You are not allowed to login from this IP address';

                        if ($request->expectsJson()) {
                            return response()->json(['error' => $error], 401);
                        }
                        return abort(403, $error);
                    }
                }
            }

            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Can\'t authorize'], 401);
        }

        return abort(403, 'Can\'t authorize');
    }

}