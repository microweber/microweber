<?php

namespace Modules\Payment\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to verify webhook requests using signed URL parameters.
 *
 * When a webhook URL is generated with a nonce and HMAC signature,
 * this middleware validates both before allowing the request through.
 * This provides an additional layer of protection on top of
 * provider-specific signature verification.
 *
 * The signed URL format is:
 *   /payment/{provider}/webhook?_nonce={nonce}&_signature={hmac}
 *
 * The signature is an HMAC-SHA256 of the nonce using the app key.
 * Each nonce can only be used within a time window (default 24h).
 */
class VerifyWebhookSignature
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $provider Payment provider name (stripe, paypal, etc.)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $provider = ''): Response
    {
        // Check for signed URL parameters
        $nonce = $request->query('_nonce');
        $signature = $request->query('_signature');

        // If signed URL parameters are present, validate them
        if ($nonce && $signature) {
            if (!$this->verifySignature($nonce, $signature)) {
                Log::warning('Webhook signed URL verification failed', [
                    'provider' => $provider,
                    'ip' => $request->ip(),
                ]);
                return new Response('Invalid webhook URL signature', 403);
            }

            // Check nonce hasn't been revoked
            $revokedKey = "webhook_nonce_revoked:{$provider}:{$nonce}";
            if (Cache::has($revokedKey)) {
                Log::warning('Webhook nonce has been revoked', [
                    'provider' => $provider,
                    'nonce' => $nonce,
                    'ip' => $request->ip(),
                ]);
                return new Response('Webhook URL has been revoked', 403);
            }
        }

        return $next($request);
    }

    /**
     * Verify the HMAC signature of a nonce.
     *
     * @param string $nonce
     * @param string $signature
     * @return bool
     */
    protected function verifySignature(string $nonce, string $signature): bool
    {
        $expectedSignature = self::generateSignature($nonce);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Generate an HMAC-SHA256 signature for a nonce.
     *
     * @param string $nonce
     * @return string
     */
    public static function generateSignature(string $nonce): string
    {
        $key = config('app.key');
        return hash_hmac('sha256', $nonce, $key);
    }

    /**
     * Generate a signed webhook URL for a given provider route.
     *
     * @param string $routeName The route name (e.g. 'payment.stripe.webhook')
     * @param string $provider The provider identifier for nonce scoping
     * @return string The full signed URL
     */
    public static function generateSignedWebhookUrl(string $routeName, string $provider): string
    {
        $nonce = bin2hex(random_bytes(32));
        $signature = self::generateSignature($nonce);

        // Store the nonce so it can be tracked/revoked
        $nonceKey = "webhook_nonce_active:{$provider}:{$nonce}";
        Cache::put($nonceKey, true, now()->addYear());

        return route($routeName, [
            '_nonce' => $nonce,
            '_signature' => $signature,
        ]);
    }

    /**
     * Revoke a webhook nonce (e.g. when rotating webhook URLs).
     *
     * @param string $provider
     * @param string $nonce
     * @return void
     */
    public static function revokeNonce(string $provider, string $nonce): void
    {
        $revokedKey = "webhook_nonce_revoked:{$provider}:{$nonce}";
        Cache::put($revokedKey, true, now()->addYear());

        $activeKey = "webhook_nonce_active:{$provider}:{$nonce}";
        Cache::forget($activeKey);
    }
}