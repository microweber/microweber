<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Process\Process;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * End-to-end RSS-import test: boots the same PHP built-in fixture
 * server as {@see LiveAdminWordPressMigrationProbeTest}, pastes its
 * URL into the Import-from-WordPress page, runs the probe, clicks
 * **Start import**, and verifies that the imported posts land in
 * Microweber's `content` table with their RSS bodies intact AND
 * that the public frontend still renders after the import writes.
 *
 * The fixture's `/feed` payload is deliberately WordPress-flavored
 * (content:encoded, wp:post_id, dc:creator, post_tag categories)
 * so this test exercises the `RssFeedImporter` → `WordPressContentMapper`
 * chain against the branches that only fire on a WordPress-shaped
 * feed — not just the generic RSS 2.0 minimum.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user admin@admin.com / admin
 *   - chromedriver reachable on :9515 (DuskTestCase starts it)
 */
class LiveAdminWordPressMigrationRssTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const FIXTURE_PORT = 18900;
    private const FIXTURE_HOST = '127.0.0.1';

    /**
     * Stable post_ids served by `tests/fixtures/wp/router.php` on
     * `/feed`. Kept in code so the cleanup teardown knows which
     * `content_data.field_value` rows to drop without depending on
     * the order tests run in.
     */
    private const FIXTURE_WP_POST_IDS = ['wp:1001', 'wp:1002'];

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
    public function rss_import_round_trip_writes_content_rows_and_leaves_frontend_healthy(): void
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
            $this->assertStringContainsString('RSS', $bodyText,
                'Probe must surface RSS capability before we can click Start import');

            // wire:confirm pops a native browser confirm dialog; if
            // we answer it through Selenium's alert API we race the
            // Livewire request, so disable it up-front by stubbing
            // window.confirm to always accept.
            $browser->script('window.confirm = function () { return true; };');

            $this->clickStartImport($browser);

            // Synchronous pipeline — walk feed, map DTOs, save rows,
            // mark job finished. In the fixture's 2-item case this
            // completes in <1s; allow headroom for CI.
            $browser->waitUsing(20, 500, function () use ($browser) {
                $text = $browser->script('return document.body.innerText;')[0] ?? '';
                return str_contains($text, 'Import finished')
                    || str_contains($text, 'Import failed');
            }, 'Expected an Import finished / failed notification within 20s');

            $text = $browser->script('return document.body.innerText;')[0] ?? '';
            $this->assertStringContainsString('Import finished', $text,
                'RSS pipeline should finish cleanly against the fixture feed');
        });

        // --- DB-level verification: the two fixture items landed as
        // content rows with their wp:post_id identities and their
        // content:encoded bodies intact.
        foreach (self::FIXTURE_WP_POST_IDS as $guid) {
            $row = DB::table('content_data')
                ->where('field_name', 'source_guid')
                ->where('field_value', $guid)
                ->first();
            $this->assertNotNull($row, "Missing content_data row for {$guid}");

            $content = DB::table('content')->where('id', $row->rel_id)->first();
            $this->assertNotNull($content, "Content row for {$guid} was not persisted");
            $this->assertSame('post', $content->content_type);
            $this->assertSame(1, (int)$content->is_active);
            $this->assertSame(0, (int)$content->is_deleted);
        }

        $hello = $this->findContentByGuid('wp:1001');
        $this->assertSame('Dusk fixture hello', $hello->title);
        $this->assertStringContainsString('<strong>hello</strong>', (string)$hello->content,
            'content:encoded body should be the source of truth for the imported HTML'
        );
        $this->assertStringContainsString('<a href="http://127.0.0.1/related">', (string)$hello->content,
            'Inline anchor from the feed body must survive the import untouched'
        );

        $world = $this->findContentByGuid('wp:1002');
        $this->assertSame('Dusk fixture world', $world->title);
        $this->assertStringContainsString('<em>emphasis</em>', (string)$world->content);

        // Source-host provenance is recorded so a later "which
        // origin did this post come from?" audit can answer cheaply.
        $this->assertDatabaseHasContentData('wp:1001', 'source_host', (string)parse_url($fixtureUrl, PHP_URL_HOST));

        // --- Public frontend sanity: after writing two new content
        // rows, the site root must still render. A 500 here would
        // mean we corrupted shared state (sitemap cache, content
        // indexes) on import.
        $this->browse(function (Browser $browser) {
            $browser->visit('/')->pause(2000);
            $source = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Whoops, looks like something went wrong', $source,
                'Public frontend must not 500 after an RSS import wrote into content/');
            $this->assertStringNotContainsString('Internal Server Error', $source);
        });

        // --- Job bookkeeping reflects a finished run with the
        // correct imported-count so the Phase 9 progress UI can
        // trust it.
        $job = DB::table('wp_migration_jobs')
            ->where('source_url', 'http://' . self::FIXTURE_HOST . ':' . self::FIXTURE_PORT)
            ->first();
        $this->assertNotNull($job, 'Migration job row should exist after a Start-import click');
        $this->assertSame('finished', $job->status);
        $progress = json_decode((string)$job->progress, true);
        $this->assertIsArray($progress);
        $this->assertSame(2, (int)($progress['imported'] ?? 0),
            'Progress must record both fixture items as imported'
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

    private function findContentByGuid(string $guid): object
    {
        $row = DB::table('content_data')
            ->where('field_name', 'source_guid')
            ->where('field_value', $guid)
            ->first();
        $this->assertNotNull($row, "No content_data row for guid {$guid}");

        $content = DB::table('content')->where('id', $row->rel_id)->first();
        $this->assertNotNull($content);
        return $content;
    }

    private function assertDatabaseHasContentData(string $guid, string $fieldName, string $fieldValue): void
    {
        $row = DB::table('content_data')
            ->where('field_name', 'source_guid')
            ->where('field_value', $guid)
            ->first();
        $this->assertNotNull($row);

        $match = DB::table('content_data')
            ->where('rel_id', $row->rel_id)
            ->where('field_name', $fieldName)
            ->where('field_value', $fieldValue)
            ->exists();
        $this->assertTrue($match, "Expected content_data[{$fieldName}]={$fieldValue} for {$guid}");
    }

    private function purgeImportedContent(): void
    {
        $contentIds = DB::table('content_data')
            ->where('field_name', 'import_source')
            ->where('field_value', 'wordpress_rss')
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

        $this->fixtureServer = new Process([
            PHP_BINARY,
            '-S', self::FIXTURE_HOST . ':' . self::FIXTURE_PORT,
            '-t', $docroot,
            $router,
        ], $docroot);
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
