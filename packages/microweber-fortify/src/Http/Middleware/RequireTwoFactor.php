<?php

namespace MicroweberPackages\Fortify\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireTwoFactor
{
    public function handle(Request $request, Closure $next, ?string $guard = null)
    {
        $user = Auth::guard($guard)->user();

        if (!$user) {
            return $next($request);
        }

        // Check if 2FA is required
        $requireAll = $this->getOption('require2fa_all');
        $requireAdmin = $this->getOption('require2fa_admin_only');

        if (!$requireAll && !$requireAdmin) {
            return $next($request);
        }

        // Check if user is admin (for admin-only requirement)
        if ($requireAdmin && !$requireAll) {
            $isAdmin = false;
            if (method_exists($user, 'is_admin') && $user->is_admin == 1) {
                $isAdmin = true;
            } elseif (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                $isAdmin = true;
            } elseif (isset($user->is_admin) && $user->is_admin == 1) {
                $isAdmin = true;
            }

            if (!$isAdmin) {
                return $next($request);
            }
        }

        // Skip if user already has 2FA enabled and confirmed
        if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
            return $next($request);
        }

        // Skip if already on setup route
        $setupRoute = config('microweber-fortify.setup_route', '/two-factor/setup');
        if ($request->is(ltrim($setupRoute, '/')) || $request->is('logout') || $request->is('livewire/*')) {
            return $next($request);
        }

        // Redirect to 2FA setup
        return redirect($setupRoute);
    }

    /**
     * Get an option value - supports Microweber get_option or falls back to config.
     */
    protected function getOption(string $key): bool
    {
        // Check Microweber user options
        if (function_exists('get_option')) {
            $value = get_option($key, 'users');
            if ($value !== null && $value !== false) {
                return (bool) $value;
            }
        }

        // Fall back to config
        return (bool) config("microweber-fortify.{$key}", false);
    }
}