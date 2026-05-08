<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-68 / AI-54 / TICKET-F — end-to-end 2FA login feature test.
 *
 * Pins the full UserLoginController::login() → loginWithTwoFactory()
 * short-circuit:
 *
 *   (a) user has two_factor_secret + two_factor_confirmed_at set
 *   (b) submit POST /api/.../login with correct credentials
 *   (c) response routes to /two-factor-challenge (NOT logged in yet)
 *   (d) submit POST /two-factor-challenge with the live TOTP code
 *   (e) user is now authenticated
 *
 * Uses real google2fa (vendored) to compute the TOTP — no mock; that
 * way the test pins the actual production verification path.
 *
 * No RefreshDatabase trait per project memory `feedback_testing` —
 * the suite uses microweber_testing DB with persistent state, and
 * this test cleans up after itself by hard-deleting the seeded user
 * in tearDown(). Each run uses a fresh email so concurrent runs do
 * not collide.
 */
class TwoFactorLoginEndToEndTest extends TestCase
{
    private string $email;
    private string $password = 'fixture-password-2fa';
    private ?int $userId = null;
    private string $secret = '';

    protected function setUp(): void
    {
        parent::setUp();

        // The 2FA pipeline branch is gated by Features::twoFactorAuthentication()
        // — enable it at runtime (the default config has it commented out).
        config()->set('fortify.features', array_merge(
            (array) config('fortify.features', []),
            [Features::twoFactorAuthentication()]
        ));

        // Seed the 2FA-enabled fixture user. Unique email per run so
        // parallel runs and reruns do not collide.
        $this->email = 'twofactor_' . bin2hex(random_bytes(4)) . '@fixture.example';
        /** @var TwoFactorAuthenticationProvider $tfa */
        $tfa = app(TwoFactorAuthenticationProvider::class);
        $this->secret = $tfa->generateSecretKey();

        $user = new User();
        $user->email = $this->email;
        // Fortify::authenticateUsing() in FortifyServiceProvider hardcodes
        // `User::where('username', $request->username)` — set both columns
        // so the credential check resolves the same row whether the test
        // POSTs `username` or `email`.
        $user->username = $this->email;
        $user->password = bcrypt($this->password);
        $user->is_admin = 1;
        $user->is_active = 1;
        // Both columns must be set — RedirectIfTwoFactorAuthenticatable.php
        // line 56 gates on `! is_null($user->two_factor_confirmed_at)`.
        $user->two_factor_secret = Fortify::currentEncrypter()->encrypt($this->secret);
        $user->two_factor_confirmed_at = now();
        $user->save();
        $this->userId = (int) $user->id;
    }

    protected function tearDown(): void
    {
        if ($this->userId !== null) {
            User::where('id', $this->userId)->delete();
        }
        Auth::logout();
        parent::tearDown();
    }

    #[Test]
    public function login_with_correct_credentials_redirects_to_two_factor_challenge(): void
    {
        // (a) + (b) — user is seeded with a confirmed 2FA secret;
        // submit valid credentials.
        $response = $this->json('POST', route('api.user.login'), [
            'username' => $this->email,
            'password' => $this->password,
        ]);

        // (c) — response must route to the 2FA challenge AND the user
        // must NOT be authenticated yet.
        $body = $response->getData(true);
        $this->assertIsArray($body);

        $this->assertArrayHasKey(
            'redirect',
            $body,
            'Login with valid credentials + active 2FA must return a redirect — got body: ' . json_encode($body)
        );
        $this->assertSame(
            route('two-factor.login'),
            $body['redirect'],
            'Login redirect URL must point at the two-factor.login route, not the dashboard'
        );

        // CRITICAL: the user is NOT authenticated until the 2FA step
        // completes. If this assertion ever flips, the 2FA short-
        // circuit has been bypassed.
        $this->assertGuest(
            null,
            'User must NOT be authenticated yet — 2FA challenge is still pending'
        );
    }

    #[Test]
    public function valid_totp_code_completes_login_and_authenticates_user(): void
    {
        // (a) + (b) — login to set the challenge session state
        // (Fortify's RedirectIfTwoFactorAuthenticatable stores the
        // challenged user id in the session, which the
        // /two-factor-challenge endpoint reads back).
        $loginResponse = $this->json('POST', route('api.user.login'), [
            'username' => $this->email,
            'password' => $this->password,
        ]);
        $loginBody = $loginResponse->getData(true);
        $this->assertSame(
            route('two-factor.login'),
            $loginBody['redirect'] ?? null,
            'Pre-condition: login must have routed to 2FA challenge — body=' . json_encode($loginBody)
        );

        // (d) — generate a live TOTP code from the seeded secret and
        // submit it to the challenge endpoint. We don't mock the
        // verifier — we exercise the real production path.
        // Fortify's bound provider does not expose getCurrentOtp() so
        // we reach into the underlying pragmarx/google2fa library
        // directly (same engine the verifier uses internally).
        $google2fa = new \PragmaRX\Google2FA\Google2FA();
        $code = $google2fa->getCurrentOtp($this->secret);

        $challengeResponse = $this->post('/two-factor-challenge', [
            'code' => $code,
        ]);

        // (e) — Fortify's TwoFactorLoginResponse is a redirect to
        // the 'home' / fortify intended URL. Either way: the user
        // must now be authenticated AND the response must NOT be
        // 4xx/5xx.
        $this->assertLessThan(
            400,
            $challengeResponse->status(),
            'Two-factor challenge with valid TOTP must succeed; got status '
            . $challengeResponse->status()
            . ' body=' . substr((string) $challengeResponse->getContent(), 0, 200)
        );
        $this->assertAuthenticated(
            null,
            'User must be authenticated after submitting a valid TOTP code'
        );
        $this->assertSame(
            $this->userId,
            (int) Auth::id(),
            'The authenticated user must be the 2FA-fixture user we seeded'
        );
    }

    #[Test]
    public function invalid_totp_code_does_not_authenticate(): void
    {
        // Login → 2FA challenge (same priming as the previous test).
        $this->json('POST', route('api.user.login'), [
            'username' => $this->email,
            'password' => $this->password,
        ]);

        // Wrong TOTP — `000000` is overwhelmingly unlikely to be the
        // current OTP (1 in ~1M for any 30-second window). If a flaky
        // run ever hits the matching window, retry the test.
        $challengeResponse = $this->post('/two-factor-challenge', [
            'code' => '000000',
        ]);

        // The response should be a 4xx (validation/auth failure) AND
        // the user must NOT be authenticated.
        $this->assertGuest(
            null,
            'Invalid TOTP must not authenticate the user'
        );
    }
}
