<?php

namespace MicroweberPackages\Fortify\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireTwoFactor
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure(Request): Response  $next
     * @param  string|null  $guard
     * @return Response
     */
    public function handle(Request $request, Closure $next, ?string $guard = null): Response
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
            $isAdmin = $this->isAdminUser($user);

            if (!$isAdmin) {
                return $next($request);
            }
        }

        // Skip if user already has 2FA enabled and confirmed
        if ($user instanceof \MicroweberPackages\Fortify\Contracts\TwoFactorAuthenticatable) { // @phpstan-ignore instanceof.alwaysTrue
            if ($user->getTwoFactorSecret() && $user->getTwoFactorConfirmedAt()) {
                return $next($request);
            }
        } elseif (!empty($user->two_factor_secret) && !empty($user->two_factor_confirmed_at)) { // @phpstan-ignore property.notFound
            return $next($request);
        }

        // Skip if already on setup route, logout, or Livewire internal routes
        /** @var string $setupRoute */
        $setupRoute = config('microweber-fortify.setup_route', '/two-factor/setup');
        if ($request->is(ltrim($setupRoute, '/')) || $request->is('logout') || $request->is('livewire/*')) {
            return $next($request);
        }

        // Redirect to 2FA setup
        return redirect($setupRoute);
    }

    /**
     * Determine if the given user is an admin, using multiple detection strategies.
     *
     * This method deliberately uses dynamic checks because it must work with
     * any User model — not just the Microweber CMS User class.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     */
    protected function isAdminUser(object $user): bool
    {
        /** @phpstan-ignore booleanAnd.leftAlwaysTrue */
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }

        /** @phpstan-ignore booleanAnd.leftAlwaysTrue */
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        // Fallback: is_admin attribute
        if (isset($user->is_admin) && (int) $user->is_admin === 1) {
            return true;
        }

        return false;
    }

    /**
     * Get an option value — supports Microweber get_option() or falls back to config.
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
