<?php

declare(strict_types=1);

namespace MicroweberPackages\User\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Audits Passport personal-access-token usage by stamping
 * `oauth_access_tokens.last_used_at` and `last_used_ip` when an
 * authenticated API request is served.
 *
 * The stamp is throttled by a short-lived cache key so a high-traffic
 * token does not generate one write per request. Only the first
 * request per token in each configured window performs the DB write;
 * every other request in the window is a no-op.
 */
class StampTokenLastUsed
{
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        $this->stamp($request);

        return $response;
    }

    private function stamp(Request $request): void
    {
        $user = $request->user();

        if (! $user || ! method_exists($user, 'token')) {
            return;
        }

        $accessToken = $user->token();
        $tokenId = $accessToken?->oauth_access_token_id ?? $accessToken?->id;

        if (! $tokenId) {
            return;
        }

        $interval = max(1, (int) config('passport.token_usage_stamp_interval_seconds', 60));

        $cacheKey = 'passport:token_last_used:' . $tokenId;

        // Microweber's bundled TaggableFileStore does not return a reliable
        // boolean from put(), which makes Cache::add() unusable as a mutex.
        // An explicit get/put pair gives us the same "first caller wins"
        // semantics with no dependency on that quirk.
        if (Cache::get($cacheKey) !== null) {
            return;
        }

        Cache::put($cacheKey, 1, $interval);

        DB::table('oauth_access_tokens')
            ->where('id', $tokenId)
            ->update([
                'last_used_at' => now(),
                'last_used_ip' => $request->ip() ? substr((string) $request->ip(), 0, 45) : null,
            ]);
    }
}
