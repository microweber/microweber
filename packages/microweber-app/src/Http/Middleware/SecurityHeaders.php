<?php

namespace MicroweberPackages\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sets defensive HTTP response headers on every response.
 *
 * Closes the gaps flagged by the OOYES_AUDITS/01_SECURITY_AUDITOR.md /
 * SECURITY_AUDIT backlog (TODO.md A04 — Insecure Design):
 *   - Content-Security-Policy: frame-ancestors 'self' (anti-clickjacking)
 *   - X-Frame-Options: SAMEORIGIN (legacy browser fallback for the same)
 *   - X-Content-Type-Options: nosniff (MIME-sniff confusion)
 *   - Referrer-Policy: strict-origin-when-cross-origin (limit referrer leak)
 *
 * The middleware preserves any header the application already set explicitly
 * (admin pages can opt out of frame-ancestors, e.g. for the live-edit iframe).
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        if (! $response->headers->has('Content-Security-Policy')
            && ! $response->headers->has('Content-Security-Policy-Report-Only')) {
            $response->headers->set('Content-Security-Policy', "frame-ancestors 'self'");
        }

        if (! $response->headers->has('X-Frame-Options')) {
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        }

        if (! $response->headers->has('X-Content-Type-Options')) {
            $response->headers->set('X-Content-Type-Options', 'nosniff');
        }

        if (! $response->headers->has('Referrer-Policy')) {
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        }

        return $response;
    }
}
