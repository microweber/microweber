<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Http;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * End-to-end coverage of the headless API token lifecycle, driven from
 * the same admin UI a developer would use in prod:
 *
 *   1. Log in to /admin
 *   2. Create a personal access token on /admin/api-applications
 *   3. Capture the one-time token value from the "Copy this token now"
 *      yellow banner
 *   4. Call /api/module/profile with the token — must succeed (200) and
 *      return the admin's own record
 *   5. Click "Revoke" on the token row
 *   6. Call /api/module/profile again with the same token — must now
 *      fail (401)
 *
 * Prerequisites:
 *   - Dev server running at http://127.0.0.1:8000
 *   - Admin seeded with admin@admin.com / admin (matches AdminLoginTrait)
 *   - Login captcha disabled in testing
 */
class AdminApiTokenEndToEndTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Rely on the already-running dev server's database; don't try to
        // reinstall from scratch. Matches the convention used across the
        // other AdminApi* Dusk tests.
    }

    #[Test]
    public function admin_can_create_use_and_revoke_a_personal_access_token(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            // ── Step 1: open the page and wait for the create form ──
            $browser->visit('/admin/api-applications')->pause(4000);
            $this->ensureLoggedIn($browser);

            $browser->waitFor('input[wire\\:model="newTokenName"]', 15);

            $tokenName = 'Dusk E2E ' . now()->format('H:i:s') . ' ' . substr(uniqid(), -4);

            // ── Step 2: create a token (no scope picks = wildcard '*') ──
            $browser->clear('input[wire\\:model="newTokenName"]')
                ->type('input[wire\\:model="newTokenName"]', $tokenName)
                ->click('button[wire\\:click="createPersonalToken"]')
                ->pause(3000);

            // ── Step 3: capture the one-time plaintext token. The view
            // renders it inside a <code class="...select-all"> after the
            // "Copy this token now" heading. ──
            $tokenValue = $browser->script("
                var codes = document.querySelectorAll('code.select-all');
                for (var i = 0; i < codes.length; i++) {
                    var t = (codes[i].innerText || '').trim();
                    if (t.length > 40) { return t; }
                }
                return null;
            ");
            $tokenValue = is_array($tokenValue) ? ($tokenValue[0] ?? null) : null;

            $this->assertNotEmpty($tokenValue, 'Expected the one-time token value to render after Create Token');
            $this->assertMatchesRegularExpression(
                '/^[A-Za-z0-9_\-\.]+$/',
                $tokenValue,
                'Token value should look like a Passport JWT personal access token'
            );

            // ── Step 4: authenticated call must succeed ──
            $apiUrl = rtrim($this->siteUrl, '/') . '/api/module/profile';

            $okResponse = Http::withoutVerifying()
                ->withOptions(['http_errors' => false])
                ->withToken($tokenValue)
                ->acceptJson()
                ->timeout(15)
                ->get($apiUrl);

            $this->assertSame(
                200,
                $okResponse->status(),
                "Freshly-issued token should authenticate /api/module/profile; got {$okResponse->status()} body=" . substr($okResponse->body(), 0, 200)
            );
            $this->assertSame(
                'admin@admin.com',
                $okResponse->json('data.email'),
                'Profile response should be the currently-authenticated admin'
            );

            // ── Step 5: revoke *this* token via the row's Revoke button.
            // loadPersonalTokens() orders by created_at DESC, so the row
            // we just added is always the first <tr> in the table body.
            // Targeting the first row keeps the test hermetic even when
            // prior Dusk runs have left other tokens around. ──
            $revoked = $browser->script("
                var row = document.querySelector('table tbody tr');
                if (!row) { return false; }
                var btn = row.querySelector('button[wire\\\\:click^=\"revokeToken\"]');
                if (!btn) { return false; }
                btn.click();
                return true;
            ");
            $this->assertTrue(
                is_array($revoked) ? (bool) ($revoked[0] ?? false) : (bool) $revoked,
                'Could not locate the Revoke button on the freshly-created token row'
            );

            $browser->pause(3000);

            // ── Step 6: the same token must now be rejected with 401 ──
            $rejectedResponse = Http::withoutVerifying()
                ->withOptions(['http_errors' => false])
                ->withToken($tokenValue)
                ->acceptJson()
                ->timeout(15)
                ->get($apiUrl);

            $this->assertSame(
                401,
                $rejectedResponse->status(),
                "Revoked token must be rejected by /api/module/profile; got {$rejectedResponse->status()} body=" . substr($rejectedResponse->body(), 0, 200)
            );
        });
    }
}
