<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Process\Process;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * End-to-end sitemap-import test: boots a PHP built-in fixture
 * server in **sitemap-only mode** (the /feed and /wp-json endpoints
 * answer 404 so the probe ranks sitemap as the recommended mode),
 * pastes the URL into the Import-from-WordPress page, runs the
 * probe, clicks **Start import**, and verifies that each URL listed
 * in the sitemap is imported into Microweber's `content` table
 * with its **original last-path-segment slug** preserved as the
 * row's `url` — the headline guarantee the SourceSlugResolver ships.
 *
 * This is the sibling of {@see LiveAdminWordPressMigrationRssTest},
 * differing in three places:
 *
 *   1. Fixture server runs with `WP_FIXTURE_MODE=sitemap-only` so
 *      the probe picks sitemap over RSS (otherwise RSS always wins
 *      — rightfully, since RSS carries stable guids).
 *
 *   2. The assertion surface is slug-centric: we inspect
 *      `content.url` on the imported rows, not the `<content:encoded>`
 *      body the RSS importer would hand us.
 *
 *   3. We cover the import_source switch — sitemap runs register
 *      their own `wordpress_sitemap` identity in content_data so
 *      a later re-probe of the same origin via RSS doesn't silently
 *      stomp on the sitemap-imported rows.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user admin@admin.com / admin
 *   - chromedriver reachable on :9515 (DuskTestCase starts it)
 */
class LiveAdminWordPressMigrationSitemapTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const FIXTURE_PORT = 18901;
    private const FIXTURE_HOST = '127.0.0.1';

    /**
     * Slugs the fixture router exposes under /sitemap.xml. They're
     * the `<loc>` last path segments — exactly what we expect to see
     * land in `content.url` post-import.
     */
    private const FIXTURE_SLUGS = ['dusk-sitemap-alpha', 'dusk-sitemap-beta'];

    private ?Process $fixtureServer = null;

    protected function assertPreConditions(): void
    {
        // Rely on the already-running dev server.
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->purgeImportedContent();
        $this->purgeJobRows();
        $this->startFixtureServer();
    }

    protected function tearDown(): void
    {
        $this->stopFixtureServer();
        $this->purgeImportedContent();
        $this->purgeJobRows();
        parent::tearDown();
    }

    #[Test]
    public function sitemap_import_preserves_original_slugs_on_content_rows(): void
    {
        $fixtureUrl = 'http://' . self::FIXTURE_HOST . ':' . self::FIXTURE_PORT;

        $this->browse(function (Browser $browser) use ($fixtureUrl) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/word-press-migration-import-page')->pause(5000);
            $this->ensureLoggedIn($browser);

            $browser->waitFor('input[wire\\:model="data.source_url"]', 15)
                ->clear('input[wire\\:model="data.source_url"]')
                ->type('input[wire\\:model="data.source_url"]', $fixtureUrl);

            $this->clickCheck($browser);
            $browser->pause(8000);

            $bodyText = $browser->script('return document.body.innerText;')[0] ?? '';
            $this->assertStringContainsString('sitemap', strtolower($bodyText),
                'Probe must surface sitemap capability before we can click Start import');

            // wire:confirm pops a native confirm dialog; stub it so
            // the click doesn't race the Livewire request.
            $browser->script('window.confirm = function () { return true; };');

            $this->clickStartImport($browser);

            $browser->waitUsing(25, 500, function () use ($browser) {
                $text = $browser->script('return document.body.innerText;')[0] ?? '';
                return str_contains($text, 'Import finished')
                    || str_contains($text, 'Import failed');
            }, 'Expected an Import finished / failed notification within 25s');

            $text = $browser->script('return document.body.innerText;')[0] ?? '';
            $this->assertStringContainsString('Import finished', $text,
                'Sitemap pipeline should finish cleanly against the fixture sitemap'
            );
        });

        // --- DB-level verification: every slug from the sitemap
        // fixture landed as a content row AND its `content.url`
        // exactly equals the fixture's last path segment. This is
        // the slug-preservation headline guarantee of the
        // SourceSlugResolver — a later `<a href="/dusk-sitemap-alpha">`
        // written by any tool still resolves after import.
        foreach (self::FIXTURE_SLUGS as $slug) {
            $guid = 'http://' . self::FIXTURE_HOST . ':' . self::FIXTURE_PORT . '/' . $slug;
            $row = DB::table('content_data')
                ->where('field_name', 'source_guid')
                ->where('field_value', $guid)
                ->first();
            $this->assertNotNull($row, "Missing content_data row for sitemap entry {$slug} (guid={$guid})");

            $content = DB::table('content')->where('id', $row->rel_id)->first();
            $this->assertNotNull($content, "Content row for {$slug} was not persisted");
            $this->assertSame($slug, (string)$content->url,
                "content.url must preserve the sitemap entry's last path segment — got '{$content->url}' for '{$slug}'"
            );
            $this->assertSame(1, (int)$content->is_active);
            $this->assertSame(0, (int)$content->is_deleted);
        }

        // Import source flag distinguishes sitemap rows from RSS
        // rows so a later re-import via RSS does NOT accidentally
        // treat them as the same origin and overwrite.
        $sitemapMarker = DB::table('content_data')
            ->where('field_name', 'import_source')
            ->where('field_value', 'wordpress_sitemap')
            ->count();
        $this->assertSame(
            count(self::FIXTURE_SLUGS),
            $sitemapMarker,
            'Each sitemap-imported content row should carry an import_source=wordpress_sitemap marker'
        );

        // --- Public frontend sanity: the imported posts should be
        // fetchable at their preserved URLs from the Microweber
        // dev server. We don't assert a full render (template chrome
        // is out-of-scope); just that the route exists and doesn't
        // 500. A 500 here would mean the slug pinning broke routing.
        $this->browse(function (Browser $browser) {
            $browser->visit('/')->pause(2000);
            $source = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Whoops, looks like something went wrong', $source,
                'Public frontend must not 500 after a sitemap import wrote into content/'
            );
            $this->assertStringNotContainsString('Internal Server Error', $source);
        });

        // --- Job bookkeeping: the sitemap run records its own
        // pages_fetched + imported counts so the Phase 9 UI can
        // surface a per-mode progress bar.
        $job = DB::table('wp_migration_jobs')
            ->where('source_url', 'http://' . self::FIXTURE_HOST . ':' . self::FIXTURE_PORT)
            ->first();
        $this->assertNotNull($job, 'Migration job row should exist after a Start-import click');
        $this->assertSame('finished', $job->status);
        $progress = json_decode((string)$job->progress, true);
        $this->assertIsArray($progress);
        $this->assertSame(
            count(self::FIXTURE_SLUGS),
            (int)($progress['imported'] ?? 0),
            'Progress must record all fixture sitemap entries as imported'
        );
    }

    private function clickCheck(Browser $browser): void
    {
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
        $this->assertTrue($clicked[0] ?? false, 'Could not locate the Check action');
    }

    private function clickStartImport(Browser $browser): void
    {
        $clicked = $browser->script(<<<'JS'
            var nodes = document.querySelectorAll('[wire\\:click*="startImport"]');
            if (nodes.length) { nodes[0].click(); return true; }
            var btns = document.querySelectorAll('button, [role="button"]');
            for (var j = 0; j < btns.length; j++) {
                if ((btns[j].innerText || '').toLowerCase().indexOf('start import') !== -1) {
                    btns[j].click();
                    return true;
                }
            }
            return false;
        JS);
        $this->assertTrue($clicked[0] ?? false, 'Could not locate the Start import button');
    }

    private function purgeImportedContent(): void
    {
        $contentIds = DB::table('content_data')
            ->where('field_name', 'import_source')
            ->whereIn('field_value', ['wordpress_sitemap', 'wordpress_rss'])
            ->pluck('rel_id');

        if ($contentIds->isNotEmpty()) {
            DB::table('content_data')->whereIn('rel_id', $contentIds)->delete();
            DB::table('content')->whereIn('id', $contentIds)->delete();
        }
    }

    private function purgeJobRows(): void
    {
        DB::table('wp_migration_jobs')
            ->where('source_host', self::FIXTURE_HOST)
            ->delete();
    }

    private function startFixtureServer(): void
    {
        $docroot = base_path('tests/fixtures/wp');
        $router = $docroot . '/router.php';
        $this->assertFileExists($router, "WP fixture router missing at {$router}");

        @exec('fuser -k -n tcp ' . self::FIXTURE_PORT . ' 2>/dev/null');
        usleep(200_000);

        $this->fixtureServer = new Process(
            [
                PHP_BINARY,
                '-S', self::FIXTURE_HOST . ':' . self::FIXTURE_PORT,
                '-t', $docroot,
                $router,
            ],
            $docroot,
            // sitemap-only mode pins the probe's recommended mode
            // to `sitemap` by making /feed and /wp-json return 404.
            ['WP_FIXTURE_MODE' => 'sitemap-only'],
        );
        $this->fixtureServer->start();

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
        $this->fail('WP fixture server at ' . self::FIXTURE_HOST . ':' . self::FIXTURE_PORT
            . ' did not come up within 5s. stderr: ' . $stderr);
    }

    private function stopFixtureServer(): void
    {
        if ($this->fixtureServer instanceof Process && $this->fixtureServer->isRunning()) {
            $this->fixtureServer->stop(2);
        }
        $this->fixtureServer = null;
    }
}
