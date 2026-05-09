<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-122 / AI-129 / SEC-04 — auth hardening regression coverage.
 *
 * Pins:
 *   - Login throttle: 5 attempts per 15 minutes (Fortify config),
 *     keyed by `<email|username><ip>`.
 *   - Two-factor throttle: same 5/15min limit, session-bound.
 *   - `RequireDefaultPasswordChange` middleware exists at the
 *     canonical path with the documented signature.
 *   - The middleware is wired as an alias `require.password.change`
 *     in bootstrap/app.php (so admin-route groups can opt in).
 *   - The middleware uses `Hash::check()` (not raw string compare)
 *     to detect default-seeded passwords.
 *   - Test-environment bypass works (config('app.env') === 'testing').
 *
 * Style after the cycle-52..121 contract tests.
 */
class Sec04AuthHardeningContractTest extends TestCase
{
    private const FORTIFY = 'src/MicroweberPackages/Fortify/FortifyServiceProvider.php';
    private const MWARE   = 'src/MicroweberPackages/User/Http/Middleware/RequireDefaultPasswordChange.php';
    private const BOOT    = 'bootstrap/app.php';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function fortify_login_throttle_is_5_per_15_minutes(): void
    {
        $src = $this->read(self::FORTIFY);

        $this->assertStringContainsString(
            "AI-129 / SEC-04 (cycle-122",
            $src,
            'Fortify provider must carry the AI-129 audit-trail comment'
        );

        $this->assertMatchesRegularExpression(
            "/RateLimiter::for\\('login',[\\s\\S]{0,400}Limit::perMinutes\\(15,\\s*5\\)/",
            $src,
            'Fortify login throttle must be `Limit::perMinutes(15, 5)` (5 attempts per 15 minutes)'
        );

        $this->assertMatchesRegularExpression(
            "/RateLimiter::for\\('two-factor',[\\s\\S]{0,400}Limit::perMinutes\\(15,\\s*5\\)/",
            $src,
            'Fortify two-factor throttle must be `Limit::perMinutes(15, 5)`'
        );
    }

    #[Test]
    public function default_password_middleware_exists_with_canonical_signature(): void
    {
        $src = $this->read(self::MWARE);

        $this->assertStringContainsString(
            'class RequireDefaultPasswordChange',
            $src,
            'Middleware class must exist at the canonical path'
        );

        $this->assertStringContainsString(
            'AI-129 / SEC-04 (cycle-122',
            $src,
            'Middleware must carry the AI-129 audit-trail comment'
        );

        // Brief: detect the well-known default values.
        foreach (["'admin'", "'password'", "'admin123'", "'microweber'", "'123456'"] as $needle) {
            $this->assertStringContainsString(
                $needle,
                $src,
                "DEFAULT_PASSWORDS must include `{$needle}`"
            );
        }
    }

    #[Test]
    public function middleware_uses_hash_check_for_detection(): void
    {
        $src = $this->read(self::MWARE);

        $this->assertStringContainsString(
            'Hash::check($candidate, $passwordHash)',
            $src,
            'Middleware must use Hash::check() (not raw string compare) to detect default passwords'
        );
    }

    #[Test]
    public function middleware_bypasses_when_app_env_testing(): void
    {
        $src = $this->read(self::MWARE);

        $this->assertStringContainsString(
            "config('app.env') === 'testing'",
            $src,
            'Middleware must bypass when APP_ENV=testing so contract tests can authenticate freely'
        );
    }

    #[Test]
    public function middleware_alias_registered_in_bootstrap(): void
    {
        $src = $this->read(self::BOOT);

        $this->assertStringContainsString(
            "'require.password.change'",
            $src,
            'bootstrap/app.php must register the `require.password.change` middleware alias'
        );

        $this->assertStringContainsString(
            'RequireDefaultPasswordChange::class',
            $src,
            'bootstrap/app.php alias must point at RequireDefaultPasswordChange::class'
        );
    }

    #[Test]
    public function functional_default_password_detection(): void
    {
        // Functional sanity: encoding 'admin' via Hash::make() and
        // running it through Hash::check() correctly returns true.
        // Pins the framework contract the middleware relies on.
        $hashed = Hash::make('admin');
        $this->assertTrue(
            Hash::check('admin', $hashed),
            'Hash::check() must return true for the default `admin` password (drives the middleware)'
        );
        $this->assertFalse(
            Hash::check('admin', Hash::make('something-else-entirely')),
            'Hash::check() must return false for a non-matching password'
        );
    }
}
