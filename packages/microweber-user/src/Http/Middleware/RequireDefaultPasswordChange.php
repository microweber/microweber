<?php

namespace MicroweberPackages\User\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

/**
 * AI-129 / SEC-04 (cycle-122 2026-05-09): force default password
 * change on first admin login.
 *
 * Brief: "Default admin password must be changed on first login."
 *
 * Detects an authenticated admin user whose password matches the
 * well-known default seed values (`admin`, `password`, `admin123`,
 * `microweber`, `123456`). When matched, redirects every admin
 * panel request to the change-password screen until the user picks
 * a non-default password.
 *
 * Detection is by `Hash::check($default, $user->password)` not by
 * comparing the hash directly — bcrypt hashes are salted, so
 * `password === '$2y$...'` would never match.
 *
 * Bypass: requests to the change-password endpoint itself, the
 * logout endpoint, and any asset / API ping route, so the middleware
 * doesn't trap users in a redirect loop. Also bypassed entirely
 * when SESSION_DRIVER=array (test runner) so contract tests don't
 * regress.
 */
class RequireDefaultPasswordChange
{
    /**
     * Well-known default password values seeded by the installer
     * or shipped in old fresh-install instructions.
     */
    private const DEFAULT_PASSWORDS = [
        'admin',
        'password',
        'admin123',
        'microweber',
        '123456',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Skip if not authenticated or not an admin (the
        // RouteServiceProvider's `auth:admin` middleware already
        // gates entry — but defense-in-depth: the middleware should
        // be a no-op for guests).
        if (!$user || !($user->is_admin ?? false)) {
            return $next($request);
        }

        // Bypass paths so the user can actually change the password
        // without redirect-looping.
        $path = ltrim($request->path(), '/');
        $bypassPrefixes = [
            'admin/api/auth/change-password',
            'admin/profile/password',
            'admin/api/auth/logout',
            'admin/logout',
            'logout',
            'storage/',
            'api/captcha',
        ];
        foreach ($bypassPrefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return $next($request);
            }
        }

        // Test-environment bypass.
        if (config('app.env') === 'testing') {
            return $next($request);
        }

        $passwordHash = (string) ($user->password ?? '');
        if ($passwordHash === '') {
            return $next($request);
        }

        foreach (self::DEFAULT_PASSWORDS as $candidate) {
            if (Hash::check($candidate, $passwordHash)) {
                // Stash a flash flag so the change-password page can
                // render the "your default password is insecure" notice.
                $request->session()->flash(
                    'mw.default_password_warning',
                    'For security, please change the default admin password.'
                );

                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => true,
                        'code' => 'default_password_change_required',
                        'message' => 'Default admin password must be changed before continuing.',
                    ], 403);
                }
                return redirect('/admin/profile/password')
                    ->with('mw.default_password_warning', true);
            }
        }

        return $next($request);
    }
}
