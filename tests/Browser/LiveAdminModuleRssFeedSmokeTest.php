<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — RssFeed module smoke (RSS feed routes).
 *
 * Non-Filament shape: the RssFeed module wires public-frontend
 * GET routes only (see Modules/RssFeed/routes/web.php — three
 * routes: `/rss` (channel index), `/rss/posts` (posts feed), and
 * `/rss/products` (products feed) all hit
 * Modules\RssFeed\Http\Controllers\RssController). There is NO
 * Filament settings page (RssFeedServiceProvider.php does not
 * call FilamentRegistry::registerPage). The Plan-C.2 task line
 * "RSS feed settings" therefore maps to the operator-visible
 * entry surface — the published RSS endpoints themselves, which
 * any admin / power-user can verify by visiting them.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /rss — the channel index
 *      and the canonical entry point feed readers subscribe
 *      against.
 *   2. Signal #2 (RSS envelope round-trip): GET /rss directly,
 *      assert HTTP 200 + a well-formed RSS 2.0 envelope (xml
 *      declaration, <rss version="2.0">, <channel>, <title>).
 *      Also probes /rss/posts and /rss/products to prove all
 *      three published feeds answer with the same envelope
 *      shape — a regression in any of the three controller
 *      methods would surface here.
 *   3. Belt-and-braces: installInPageErrorGuard() on the /rss
 *      page after settle, with a 1.5s window catching any
 *      deferred-script throws (the browser's RSS-rendering
 *      shell loads JS in some browsers — a regression in the
 *      controller's xml content-type would surface here).
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait — kept
 * aligned with the Plan-C.2 sibling shape so any future auth-
 * gating change is detected automatically).
 *
 * Read-only — exercises only GET routes, no fixture rows to
 * clean up. Safe to re-run.
 */
class LiveAdminModuleRssFeedSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const FEED_INDEX_PATH = '/rss';

    private const FEED_POSTS_PATH = '/rss/posts';

    private const FEED_PRODUCTS_PATH = '/rss/products';

    /**
     * Marker substrings that must appear in every well-formed RSS
     * 2.0 envelope. The RssController emits these inline at the
     * top of each response — a regression in the controller (or
     * a regression in the configured feed-formatter package the
     * controller delegates to) would silently change the content-
     * type or strip the marker.
     */
    private const RSS_XML_DECLARATION = '<?xml version="1.0"';

    private const RSS_VERSION_MARKER = '<rss version="2.0"';

    private const RSS_CHANNEL_OPEN = '<channel>';

    private const RSS_TITLE_OPEN = '<title>';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server.
    }

    #[Test]
    public function rss_feed_routes_load_and_round_trip_a_well_formed_rss_envelope(): void
    {
        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of /rss (the
            // channel index, canonical operator entry point).
            $this->assertPageSmokeOk(
                $browser,
                self::FEED_INDEX_PATH,
                'RSS feed channel index',
            );

            // Belt-and-braces console probe after a settle window
            // — the browser's RSS-rendering shell loads JS in
            // some browsers, so a regression in the controller's
            // xml content-type (which would force the browser to
            // render HTML instead) surfaces here.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'RSS feed channel index render');
        });

        // Signal #2 — round-trip the RSS envelope directly
        // through HTTP for all three published feeds. A
        // regression in any of the three controller methods
        // (RssController::index / posts / products) surfaces
        // here as either a non-200 response or a malformed
        // envelope.
        $this->assertRssEnvelopeIsWellFormed(self::FEED_INDEX_PATH);
        $this->assertRssEnvelopeIsWellFormed(self::FEED_POSTS_PATH);
        $this->assertRssEnvelopeIsWellFormed(self::FEED_PRODUCTS_PATH);
    }

    /**
     * GET an RSS endpoint over HTTP and assert the envelope is
     * well-formed: HTTP 200, XML declaration, RSS 2.0 version
     * marker, opening <channel> + <title> tags. Runs against the
     * live dev server at 127.0.0.1:8000 (same host the Dusk
     * browser uses) so it exercises the same controller pipeline
     * a real reader hits.
     */
    private function assertRssEnvelopeIsWellFormed(string $path): void
    {
        $url = 'http://127.0.0.1:8000' . $path;

        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'ignore_errors' => true,
                'header' => "Accept: application/rss+xml, application/xml, text/xml\r\n",
            ],
        ]);

        $body = @file_get_contents($url, false, $context);

        $this->assertNotFalse(
            $body,
            'GET ' . $path . ' must return a response body — a regression in the '
            . 'RssFeed route registration (RssFeedServiceProvider::register / '
            . 'routes/web.php) would surface here as a connect/timeout failure '
            . 'with no body to parse.'
        );

        $statusLine = $http_response_header[0] ?? '';
        $this->assertStringContainsString(
            ' 200 ',
            $statusLine,
            'GET ' . $path . ' must return HTTP 200 — anything else means either '
            . 'the route stopped registering or the controller method threw. '
            . 'Either regression would silently break every RSS reader / search '
            . 'engine that subscribes to the feed.'
        );

        $this->assertStringContainsString(
            self::RSS_XML_DECLARATION,
            $body,
            'RSS feed at ' . $path . ' must start with `' . self::RSS_XML_DECLARATION
            . '` — every RSS 2.0 envelope opens with this declaration. A regression '
            . 'that drops it would mean some readers reject the feed entirely.'
        );
        $this->assertStringContainsString(
            self::RSS_VERSION_MARKER,
            $body,
            'RSS feed at ' . $path . ' must include the `' . self::RSS_VERSION_MARKER
            . '"` version marker — RSS readers parse the version to pick the right '
            . 'schema. A regression that drops it (or flips it to a different '
            . 'version like Atom) would silently break every reader subscribed '
            . 'to the feed.'
        );
        $this->assertStringContainsString(
            self::RSS_CHANNEL_OPEN,
            $body,
            'RSS feed at ' . $path . ' must open a <channel> element — every RSS 2.0 '
            . 'envelope contains exactly one channel and readers parse the title / '
            . 'link / description from it. A regression here would mean the feed '
            . 'renders an empty subscription in every reader.'
        );
        $this->assertStringContainsString(
            self::RSS_TITLE_OPEN,
            $body,
            'RSS feed at ' . $path . ' must include a <title> element inside the '
            . 'channel — readers display this string as the subscription label, '
            . 'and a regression that strips it would mean every reader shows the '
            . 'feed as anonymous / "Untitled".'
        );
    }
}
