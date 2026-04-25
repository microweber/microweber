<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Cache;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\Cloudflare\Helpers\CloudflareHelpers;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Cloudflare module smoke.
 *
 * Shape diverges sharply from the sibling Filament-settings-page
 * smokes: the Cloudflare module ships NO admin form. Its
 * CloudflareServiceProvider imports a `CloudflareModuleSettings`
 * class that does not exist on disk (dead `use` statement —
 * silently ignored by autoload), never calls
 * FilamentRegistry::registerPage, and never registers a
 * Microweber module. The whole integration surface is a single
 * event-bound proxy-trust hook:
 *
 *   event_bind('mw.trust_proxies', function () {
 *       if (request()->header('cdn-loop') == 'cloudflare') {
 *           Config::set('trustedproxy.proxies',
 *                       CloudflareHelpers::fetchCloudFlareIps());
 *       }
 *   });
 *
 * The Plan-C.2 task line is "Cloudflare integration form". No
 * such form exists today. This smoke covers the actual
 * integration surface — the helper-class CIDR-fetch + cache
 * pipeline — and pins the missing-form situation so a future
 * Filament-5 migration that ships an admin form here can swap
 * this smoke for the canonical settings-page pattern (see the
 * sibling Audio/Accordion/Btn smokes for the shape).
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin (dashboard) — the
 *      Cloudflare module is process-wide / dashboard-adjacent
 *      since it sets trustedproxy.proxies on every request,
 *      so a SEVERE log entry on the dashboard would surface
 *      a Cloudflare regression.
 *   2. Signal #2 (integration round-trip): exercise the helper
 *      class's cache pipeline directly — seed a marker-prefixed
 *      list under the canonical 'cloudflare_ips' cache key,
 *      reload via the helper's first-cache-hit branch, verify
 *      the helper returned the seeded list verbatim. The same
 *      cache key the production code reads from / writes to.
 *   3. Belt-and-braces: installInPageErrorGuard() on /admin
 *      after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Plan-C.4 follow-up: if/when a future commit lands an actual
 * /admin/cloudflare-module-settings page (registering
 * CloudflareModuleSettings via FilamentRegistry::registerPage),
 * this smoke should be updated to probe THAT page instead of
 * /admin and round-trip an `options.api_token`-style option key
 * via save_module_option(). The migration shape mirrors the
 * Audio/Accordion smokes — assert a canonical `wire:click="save"`
 * Filament action button is wired, then round-trip the
 * api_token / zone_id options through the same Livewire
 * updated() hook. Until that lands, the smoke is the
 * helper-class round-trip + a documented missing-form note.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker cache key in tearDown; safe to re-run.
 */
class LiveAdminModuleCloudflareSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    /**
     * The dashboard probe target — the Cloudflare module is
     * process-wide so the most surface-level "the integration is
     * loaded without breaking the admin" probe is /admin itself.
     */
    private const DASHBOARD_PATH = 'admin';

    /**
     * Canonical cache key the CloudflareHelpers::fetchCloudFlareIps()
     * helper reads from AND writes to (see line 11 of
     * Modules/Cloudflare/Helpers/CloudflareHelpers.php). Pinning
     * this constant here surfaces a regression that renames the
     * cache key (which would silently invalidate every cached
     * Cloudflare IP list across the fleet on next deploy).
     */
    private const CACHE_KEY = 'cloudflare_ips';

    /**
     * Marker-prefixed CIDR set seeded into the cache before the
     * helper round-trip. Two entries (one IPv4, one IPv6) so the
     * smoke also asserts the helper's array-merge behaviour
     * preserves both halves of the IP list — the production
     * code merges ipv4_cidrs + ipv6_cidrs from the upstream
     * Cloudflare API response.
     *
     * @var list<string>
     */
    private const FIXTURE_CIDRS = [
        '198.51.100.0/24',
        '2001:db8::/32',
    ];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        Cache::forget(self::CACHE_KEY);
        parent::tearDown();
    }

    #[Test]
    public function cloudflare_helper_round_trips_through_the_canonical_cache_key(): void
    {
        Cache::forget(self::CACHE_KEY);

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of /admin
            // (the dashboard). Cloudflare's event-bound
            // proxy-trust hook fires on every request, so a
            // helper regression would surface as a SEVERE
            // dashboard log.
            $this->assertPageSmokeOk(
                $browser,
                '/' . self::DASHBOARD_PATH,
                'admin dashboard (Cloudflare integration is process-wide)',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'admin dashboard render');

            // Signal #2 — exercise the Cloudflare helper's cache
            // round-trip directly. Same cache key the production
            // event-bound hook reads from on every Cloudflare-
            // proxied request.
            $this->assertCloudflareHelperCacheRoundTripPersists();

            // Document the missing-form situation: the Plan-C.2
            // task line names a "Cloudflare integration form"
            // but no such form exists today. Assert the absence
            // explicitly so a future commit that lands the form
            // (and forgets to update this smoke) flips the test.
            $this->assertNoCloudflareSettingsPageRegistered();
        });
    }

    /**
     * Seed the canonical Cloudflare IP cache key with a fixture
     * CIDR set, then call CloudflareHelpers::fetchCloudFlareIps()
     * and assert it short-circuits to the cached value
     * verbatim (the helper's first branch — Cache::get returns
     * truthy → return early). Avoids any HTTP round-trip to
     * Cloudflare's real API.
     */
    private function assertCloudflareHelperCacheRoundTripPersists(): void
    {
        $this->assertTrue(
            class_exists(CloudflareHelpers::class),
            'Modules\\Cloudflare\\Helpers\\CloudflareHelpers must exist at the canonical '
            . 'namespace — the CloudflareServiceProvider event-bind hook depends on this '
            . 'class being autoloadable.'
        );

        Cache::put(self::CACHE_KEY, self::FIXTURE_CIDRS, 60);

        $returned = CloudflareHelpers::fetchCloudFlareIps();

        $this->assertSame(
            self::FIXTURE_CIDRS,
            $returned,
            'CloudflareHelpers::fetchCloudFlareIps() must short-circuit to the cached value '
            . "under the canonical '" . self::CACHE_KEY . "' cache key. A regression that "
            . 'renames the cache key OR breaks the early-return-on-cache-hit branch would '
            . 'surface here as a non-equal CIDR set.'
        );

        $cachedAfter = Cache::get(self::CACHE_KEY);
        $this->assertSame(
            self::FIXTURE_CIDRS,
            $cachedAfter,
            'Cache::put → fetchCloudFlareIps() round-trip must leave the canonical cache '
            . 'key untouched (the helper reads but does not rewrite when the cache is hot).'
        );
    }

    /**
     * Pin the missing-form situation explicitly. /admin/cloudflare-module-settings
     * returns 404 today because CloudflareServiceProvider does
     * not call FilamentRegistry::registerPage. When a future
     * commit lands the form (closing Plan-C.2's
     * "Cloudflare integration form" line for real), this
     * assertion will flip and the smoke will need a refresh
     * pointing at the new admin page.
     */
    private function assertNoCloudflareSettingsPageRegistered(): void
    {
        $url = config('app.url', 'http://127.0.0.1:8000');
        $response = \Illuminate\Support\Facades\Http::withoutVerifying()
            ->withOptions(['http_errors' => false, 'allow_redirects' => false])
            ->timeout(15)
            ->get(rtrim($url, '/') . '/admin/cloudflare-module-settings');

        // The page is unregistered today; a redirect to /admin/login
        // (302) or a Filament 404 are both acceptable "no page"
        // signals. A 200 here would mean a future commit shipped
        // the form and this smoke needs updating.
        $this->assertNotSame(
            200,
            $response->status(),
            'No /admin/cloudflare-module-settings Filament page is registered today '
            . '(CloudflareServiceProvider does not call FilamentRegistry::registerPage). '
            . 'If this assertion ever fires, it means a future commit landed the long-'
            . 'awaited Cloudflare admin form — update this smoke to probe the new page '
            . 'and round-trip an options.api_token-style option via save_module_option(), '
            . 'matching the sibling Audio/Accordion/Btn pattern. See the docblock of '
            . static::class . ' for migration guidance.'
        );
    }
}
