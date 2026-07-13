<?php

declare(strict_types=1);

namespace MicroweberPackages\Passport\Http\Middleware;

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
 * token does not generate one write per request.
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

        if (!$user || !method_exists($user, 'token')) {
            return;
        }

        $accessToken = $user->token();

        // Passport 12+ stores the token ID in `id`, older versions
        // may use `oauth_access_token_id`.
        $tokenId = $accessToken?->id ?? $accessToken?->oauth_access_token_id ?? null;

        if (!$tokenId) {
            return;
        }

        $interval = max(1, (int) config('microweber-passport.token_usage_stamp_interval_seconds', 60));

        $cacheKey = 'mw_passport:token_last_used:' . $tokenId;

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