<?php

namespace Modules\Profile\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorRateLimit
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next, $maxAttempts = 5, $decayMinutes = 15): Response
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'message' => 'Too many attempts. Please try again in '.$this->limiter->availableIn($key).' seconds.'
            ], 429);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        $response = $next($request);

        if ($response->isSuccessful()) {
            $this->limiter->clear($key);
        }

        return $response;
    }

    protected function resolveRequestSignature(Request $request): string
    {
        return sha1(
            $request->method().
            '|'.$request->ip().
            '|'.$request->path()
        );
    }
}