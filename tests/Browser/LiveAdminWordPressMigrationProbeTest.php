<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Process\Process;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * End-to-end probe test: boots a PHP built-in server in front of
 * `tests/fixtures/wp/router.php`, pastes that URL into the
 * Import-from-WordPress page, clicks Check, and verifies the page
 * surfaces the capabilities and counts the router advertises (REST
 * root + X-WP-Total of 42 posts / 5 pages + RSS + sitemap).
 *
 * The fixture runs out-of-band on a fixed high port so it is a
 * separate process from the Dusk dev server at :8000 — the probe
 * chrome is driving fetches the fixture URL exactly the way an
 * operator would paste one in.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user admin@admin.com / admin
 *   - chromedriver reachable on :9515 (DuskTestCase starts it)
 */
class LiveAdminWordPressMigrationProbeTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const FIXTURE_PORT = 18899;
    private const FIXTURE_HOST = '127.0.0.1';

    private ?Process $fixtureServer = null;

    protected function assertPreConditions(): void
    {
        // Rely on the already-running dev server.
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->startFixtureServer();
    }

    protected function tearDown(): void
    {
        $this->stopFixtureServer();
        parent::tearDown();
    }

    #[Test]
    public function probe_page_surfaces_rest_rss_sitemap_and_counts_against_fixture(): void
    {
        $fixtureUrl = 'http://' . self::FIXTURE_HOST . ':' . self::FIXTURE_PORT;

        $this->browse(function (Browser $browser) use ($fixtureUrl) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/word-press-migration-import-page')->pause(5000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Import page should not 500 before probe');
            $this->assertStringNotContainsString('Whoops', $pageSource,
                'Import page should render cleanly before probe');

            $browser->waitFor('input[wire\\:model="data.source_url"]', 15)
                ->clear('input[wire\\:model="data.source_url"]')
                ->type('input[wire\\:model="data.source_url"]', $fixtureUrl);

            // Fire the Check header action. The selector picks whichever
            // element exposes wire:click="mountAction('check')" — Filament
            // renders it as a <button> today but that is an implementation
            // detail the test shouldn't be coupled to.
            $clicked = $browser->script(<<<'JS'
                var nodes = document.querySelectorAll('[wire\\:click*="check"]');
                for (var i = 0; i < nodes.length; i++) {
                    var wc = nodes[i].getAttribute('wire:click') || '';
                    if (wc.indexOf("'check'") !== -1 || wc.indexOf('"check"') !== -1) {
                        nodes[i].click();
                        return true;
                    }
                }
                // Fallback: any button whose visible label contains "Check".
                var btns = document.querySelectorAll('button, [role="button"]');
                for (var j = 0; j < btns.length; j++) {
                    if ((btns[j].innerText || '').trim().toLowerCase() === 'check') {
                        btns[j].click();
                        return true;
                    }
                }
                return false;
            JS);
            $this->assertTrue($clicked[0] ?? false,
                'Could not locate the "Check" header action on the import page');

            // Give the probe time to hit every endpoint on the fixture.
            // The fetcher timeout is 10s per URL; in practice the loopback
            // server responds in milliseconds, but allow headroom.
            $browser->pause(8000);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Probe click should not trigger a 500');

            $bodyText = $browser->script('return document.body.innerText;');
            $text = $bodyText[0] ?? '';

            $this->assertStringContainsString('REST', $text,
                'REST capability should be listed on the page');
            $this->assertStringContainsString('RSS', $text,
                'RSS capability should be listed on the page');
            $this->assertStringContainsString('SITEMAP', $text,
                'SITEMAP capability should be listed on the page');
            $this->assertStringContainsString('recommended', $text,
                'The primary mode (REST) should be labelled "recommended"');
            $this->assertStringContainsString('42', $text,
                'Estimated posts (42) from fixture X-WP-Total should appear');
            $this->assertStringContainsString('5', $text,
                'Estimated pages (5) from fixture X-WP-Total should appear');

            $summary = $browser->script(<<<'JS'
                return {
                    host: (document.body.innerText.match(/127\.0\.0\.1/g) || []).length,
                    hasReady: (document.body.innerText.toLowerCase()).indexOf('ready') !== -1,
                };
            JS);
            $this->assertTrue(($summary[0]['host'] ?? 0) >= 1,
                'Source host 127.0.0.1 should appear somewhere in the summary');
            $this->assertTrue($summary[0]['hasReady'] ?? false,
                'Probe outcome should have Ready status (usable source)');
        });
    }

    private function startFixtureServer(): void
    {
        $docroot = base_path('tests/fixtures/wp');
        $router = $docroot . '/router.php';
        $this->assertFileExists($router, "WP fixture router missing at {$router}");

        // Free the port in case a previous run left a stray process.
        @exec('fuser -k -n tcp ' . self::FIXTURE_PORT . ' 2>/dev/null');
        usleep(200_000);

        $this->fixtureServer = new Process([
            PHP_BINARY,
            '-S', self::FIXTURE_HOST . ':' . self::FIXTURE_PORT,
            '-t', $docroot,
            $router,
        ], $docroot);
        $this->fixtureServer->start();

        // Wait for the server to accept connections (≤5s).
        $deadline = microtime(true) + 5.0;
        while (microtime(true) < $deadline) {
            $fp = @fsockopen(self::FIXTURE_HOST, self::FIXTURE_PORT, $errno, $errstr, 0.25);
            if ($fp) {
                fclose($fp);
                return;
            }
            usleep(100_000);
        }

        $stderr = $this->fixtureServer->getErrorOutput();
        $this->fail("WP fixture server at " . self::FIXTURE_HOST . ':' . self::FIXTURE_PORT
            . " did not come up within 5s. stderr: " . $stderr);
    }

    private function stopFixtureServer(): void
    {
        if ($this->fixtureServer instanceof Process && $this->fixtureServer->isRunning()) {
            $this->fixtureServer->stop(2);
        }
        $this->fixtureServer = null;
    }
}
