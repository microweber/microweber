<?php

declare(strict_types=1);

namespace MicroweberPackages\DisposableEmailChecker\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use MicroweberPackages\DisposableEmailChecker\Contracts\DisposableEmailCheckerContract;
use Symfony\Component\HttpFoundation\Response;

class BlockDisposableEmail
{
    /**
     * The request field that contains the email address. Defaults to "email".
     */
    private string $field;

    public function __construct(
        private readonly DisposableEmailCheckerContract $checker,
    ) {
        $this->field = 'email';
    }

    /**
     * Handle an incoming request.
     *
     * Usage in routes: ->middleware('block_disposable_email')          — checks "email" field
     *                  ->middleware('block_disposable_email:user_email') — checks "user_email" field
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string $field = 'email'): Response
    {
        $this->field = $field;

        $enabled = config('disposable-email-checker.enabled', true);

        if (!$enabled) {
            return $next($request);
        }

        $email = $request->input($this->field);

        if (is_string($email) && $this->checker->isDisposable($email)) {
            abort(422, 'Disposable email addresses are not allowed.');
        }

        return $next($request);
    }
}