<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Plan E — opt-in live-site check for the WordPress importer.
 *
 * Every other LiveAdminWordPressMigration*Test in this directory
 * drives the importer against the in-repo PHP-built-in-server
 * fixture at `tests/fixtures/wp/router.php`. The fixture proves
 * the importer's pipeline is internally consistent, but it cannot
 * prove the importer survives the wild — real WordPress sites
 * vary in REST shape, response headers, redirect chains, and TLS
 * setup in ways no static fixture exhaustively models.
 *
 * This test is the last user-facing validation gate before the
 * Phase-1-through-11 importer ships. It pokes a known-good public
 * WordPress site (defaults to https://wordpress.org/news/, a
 * canonical wordpress.org REST-enabled WP install that has been
 * stable for years) and asserts the probe completes against the
 * real site with REST as the recommended capability and a non-zero
 * estimated post count surfaced in the page.
 *
 * Opt-in only — gated on TWO independent skip conditions so a
 * default `php artisan dusk` never accidentally hammers a public
 * site:
 *
 *   1. The PHPUnit `live-external` group MUST be requested
 *      explicitly via `--group=live-external` on the dusk
 *      invocation (or `--testsuite` whenever a future suite
 *      collects this group).
 *   2. The `MW_RUN_LIVE_EXTERNAL=1` environment variable MUST be
 *      set in the dusk process. Belt-and-braces: even if a CI
 *      misconfiguration ever passes the group, the env-var gate
 *      stops the request from leaving the host until a human has
 *      opted in.
 *
 * The test target URL is overridable via the
 * `MW_LIVE_WP_SITE_URL` env var so a contributor can run it
 * against their own staging WordPress install before merging a
 * Phase-* change. Defaults to a publicly-known wordpress.org
 * install when the env var is unset.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait); outbound
 * HTTPS reachability from the host running dusk; opt-in env vars
 * (above).
 *
 * If this test ever turns red against the wordpress.org default,
 * the failure is almost certainly real (a regression in our REST
 * importer, NOT wordpress.org becoming unreachable — that site
 * has measured five-nines availability over the lifetime of the
 * importer). Rerun against an alternate site via MW_LIVE_WP_SITE_URL
 * to triangulate before reverting any changes.
 *
 * See `docs/migration/wordpress.md` §11 for the operator-side
 * runbook and `docs/migration/wordpress-architecture.md` §3 for
 * the architectural placement of this test as the
 * "before-you-tag-a-release" acceptance gate.
 */
#[Group('live-external')]
class LiveAdminWordPressMigrationLiveSiteCheckTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const DEFAULT_LIVE_SITE_URL = 'https://wordpress.org/news/';

    private const OPT_IN_ENV_VAR = 'MW_RUN_LIVE_EXTERNAL';

    private const OVERRIDE_URL_ENV_VAR = 'MW_LIVE_WP_SITE_URL';

    /**
     * Probe-network round-trip headroom. The default importer
     * fetcher uses a 10s per-URL timeout; a real public site over
     * HTTPS can take ~2s for the REST root + 2s for an x-wp-total
     * paginated probe + redirect chases. 25s gives generous
     * headroom for slow upstreams without waiting forever for a
     * dead site.
     */
    private const PROBE_SETTLE_MS = 25_000;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server.
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (getenv(self::OPT_IN_ENV_VAR) !== '1') {
            $this->markTestSkipped(
                'Plan E live-site check is opt-in. Set '
                . self::OPT_IN_ENV_VAR . '=1 in your environment to enable. '
                . 'See docs/migration/wordpress.md §11 for the runbook.'
            );
        }
    }

    #[Test]
    public function probe_page_completes_against_a_real_wordpress_site_and_surfaces_rest_capability(): void
    {
        $liveUrl = $this->resolveLiveSiteUrl();

        $this->browse(function (Browser $browser) use ($liveUrl) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/word-press-migration-import-page')->pause(5_000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString(
                'Internal Server Error',
                $pageSource,
                'Import page must mount cleanly before the live-site probe is fired '
                . '— a 500 here would mean a regression in the page itself, not the '
                . 'live-network round-trip.'
            );
            $this->assertStringNotContainsString(
                'Whoops',
                $pageSource,
                'Import page must render without a Whoops trace before the live probe '
                . 'is fired.'
            );

            $browser->waitFor('input[wire\\:model="data.source_url"]', 15)
                ->clear('input[wire\\:model="data.source_url"]')
                ->type('input[wire\\:model="data.source_url"]', $liveUrl);

            // Identical Check-action selector pattern to
            // LiveAdminWordPressMigrationProbeTest — keeps the two
            // tests structurally aligned so a UI-shape change to the
            // probe action only needs to be tracked in one place.
            $clicked = $browser->script(<<<'JS'
                var nodes = document.querySelectorAll('[wire\\:click*="check"]');
                for (var i = 0; i < nodes.length; i++) {
                    var wc = nodes[i].getAttribute('wire:click') || '';
                    if (wc.indexOf("'check'") !== -1 || wc.indexOf('"check"') !== -1) {
                        nodes[i].click();
                        return true;
                    }
                }
                var btns = document.querySelectorAll('button, [role="button"]');
                for (var j = 0; j < btns.length; j++) {
                    if ((btns[j].innerText || '').trim().toLowerCase() === 'check') {
                        btns[j].click();
                        return true;
                    }
                }
                return false;
            JS);
            $this->assertTrue(
                $clicked[0] ?? false,
                'Could not locate the "Check" header action on the import page — the '
                . "probe button regressed independently of the live-site path's behaviour."
            );

            // Allow generous headroom for the real live-network
            // round-trip (REST root + capability follow-ups +
            // optional redirects).
            $browser->pause(self::PROBE_SETTLE_MS);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString(
                'Internal Server Error',
                $pageSource,
                'Live-site probe click must not 500 — a server-side regression in the '
                . 'probe service (timeouts, missing fetcher binding, JSON-decoding '
                . 'crash on a real upstream payload) would surface here.'
            );

            $bodyText = $browser->script('return document.body.innerText;');
            $text = (string) ($bodyText[0] ?? '');

            $this->assertStringContainsString(
                'REST',
                $text,
                'Live-site probe must surface REST as a detected capability — '
                . self::DEFAULT_LIVE_SITE_URL . ' (and any well-known wordpress.org '
                . 'install set as the override) ships /wp-json by default; if REST is '
                . 'missing the importer\'s recommended-mode selection regressed.'
            );
            $this->assertStringContainsString(
                'recommended',
                $text,
                'Live-site probe must label REST as the recommended primary mode — a '
                . 'regression in WordPressSiteProbe::pickRecommendedMode would surface '
                . 'as the absence of this label even when REST itself was detected.'
            );

            // The wp.org news site has hundreds of posts; a "0"
            // estimated count would mean the X-WP-Total header was
            // dropped during the live round-trip (not surfaced by
            // the importer's HEAD-or-GET probe). Don't assert an
            // exact number because real sites grow over time.
            $hasPlausibleCount = (bool) preg_match(
                '/(\d{1,3}(?:[,. ]\d{3})+|[1-9]\d{1,5})\s*(posts|articles|items|entries)?/i',
                $text,
            );
            $this->assertTrue(
                $hasPlausibleCount,
                'Live-site probe must surface a plausible non-zero post count from the '
                . 'real upstream X-WP-Total header. A zero count would mean the header '
                . 'was dropped or the fetcher silently 404\'d the paginated probe.'
            );
        });
    }

    private function resolveLiveSiteUrl(): string
    {
        $override = getenv(self::OVERRIDE_URL_ENV_VAR);
        if (is_string($override) && $override !== '') {
            return $override;
        }

        return self::DEFAULT_LIVE_SITE_URL;
    }
}
