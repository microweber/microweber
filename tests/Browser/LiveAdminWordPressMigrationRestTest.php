<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Modules\Content\Models\Content;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Process\Process;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * End-to-end REST-import test: boots a PHP built-in fixture server
 * in **rest-authed mode** (RSS + sitemap endpoints 404; every
 * /wp-json/wp/v2/* call is gated behind Basic auth), pastes the URL
 * and an application password into the Import-from-WordPress page,
 * runs the probe, clicks **Start import**, and verifies that:
 *
 *   1. The recorded posts (wp:1001, wp:1002) and page (wp:2001) all
 *      land as Microweber content rows with `import_source=wordpress_rest`.
 *   2. The Gutenberg HTML in `content.rendered` survives the
 *      round-trip unmangled — `<!-- wp:paragraph -->` comments and
 *      inline `<img>` tags come through as-is.
 *   3. The taxonomy-first pass attaches `categories_items` +
 *      `tagging_tagged` rows pointing at the pre-seeded local
 *      categories and tags (News/Tech for categories, Launch/Featured
 *      for tags).
 *   4. The public frontend still renders after the import writes.
 *
 * This is the sibling of {@see LiveAdminWordPressMigrationRssTest}
 * and {@see LiveAdminWordPressMigrationSitemapTest}, distinguished
 * by three choices:
 *
 *   - Fixture mode `rest-authed` forces REST as the only capability
 *     and requires the Basic header on every REST request.
 *   - `WP_FIXTURE_REST_BASE` rewrites the `https://wp.example` URLs
 *     baked into the recorded JSON so imported content links resolve
 *     back at the fixture host.
 *   - The page form carries a non-empty `wp_application_password`,
 *     so the assertion surface includes "authed requests actually
 *     got through" via the presence of post/page rows (anon requests
 *     would have been 401'd by the router and no content would land).
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user admin@admin.com / admin
 *   - chromedriver reachable on :9515 (DuskTestCase starts it)
 */
class LiveAdminWordPressMigrationRestTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const FIXTURE_PORT = 18902;
    private const FIXTURE_HOST = '127.0.0.1';

    /** Username expected by the rest-authed router. */
    private const FIXTURE_REST_USER = 'editor';

    /** Password the fixture expects (matches router default). */
    private const FIXTURE_REST_PASS = 'abcdefghijklmnopqrstuvwx';

    /**
     * Stable WP post ids the fixture router serves under
     * `/wp-json/wp/v2/{posts,pages}`. Tracked here so the teardown
     * can drop imported rows without depending on test order.
     */
    private const FIXTURE_WP_GUIDS = ['wp:1001', 'wp:1002', 'wp:2001'];

    /**
     * Category slugs in the fixture's `/wp-json/wp/v2/categories`
     * payload that wp:1001 references (11 → news, 12 → tech).
     * TaxonomyIndex auto-upserts these as local category rows during
     * the walk — we assert post-run that they exist and are attached.
     */
    private const FIXTURE_CATEGORY_SLUGS = ['news', 'tech'];

    /** Tag slugs in the fixture's `/wp-json/wp/v2/tags` payload. */
    private const FIXTURE_TAG_SLUGS = ['launch', 'featured'];

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
        $this->purgeTaxonomyAttachments();
        $this->purgeFixtureTaxonomies();
        $this->startFixtureServer();
    }

    protected function tearDown(): void
    {
        $this->stopFixtureServer();
        $this->purgeTaxonomyAttachments();
        $this->purgeImportedContent();
        $this->purgeFixtureTaxonomies();
        $this->purgeJobRows();
        parent::tearDown();
    }

    #[Test]
    public function rest_import_round_trip_imports_posts_pages_and_attaches_taxonomies(): void
    {
        $fixtureUrl = 'http://' . self::FIXTURE_HOST . ':' . self::FIXTURE_PORT;
        // The username is fixed by the router; the UI accepts a
        // `user:pass` combined string so the credential object can
        // split it back out — see WpAppPasswordCredential::fromString.
        $appPassword = self::FIXTURE_REST_USER . ':' . self::FIXTURE_REST_PASS;

        $this->browse(function (Browser $browser) use ($fixtureUrl, $appPassword) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/word-press-migration-import-page')->pause(5000);
            $this->ensureLoggedIn($browser);

            $browser->waitFor('input[wire\\:model="data.source_url"]', 15)
                ->clear('input[wire\\:model="data.source_url"]')
                ->type('input[wire\\:model="data.source_url"]', $fixtureUrl)
                ->waitFor('input[wire\\:model="data.wp_application_password"]', 5)
                ->clear('input[wire\\:model="data.wp_application_password"]')
                ->type('input[wire\\:model="data.wp_application_password"]', $appPassword);

            $this->clickCheck($browser);
            $browser->pause(8000);

            $bodyText = $browser->script('return document.body.innerText;')[0] ?? '';
            $this->assertStringContainsString('rest', strtolower($bodyText),
                'Probe must surface REST capability before we can click Start import');

            // wire:confirm pops a native confirm dialog; stub it so
            // the click doesn't race the Livewire request.
            $browser->script('window.confirm = function () { return true; };');

            $this->clickStartImport($browser);

            // REST walk is bigger than RSS (enrichers + posts + pages)
            // so the synchronous pipeline wants a bit more headroom.
            $browser->waitUsing(30, 500, function () use ($browser) {
                $text = $browser->script('return document.body.innerText;')[0] ?? '';
                return str_contains($text, 'Import finished')
                    || str_contains($text, 'Import failed');
            }, 'Expected an Import finished / failed notification within 30s');

            $text = $browser->script('return document.body.innerText;')[0] ?? '';
            $this->assertStringContainsString('Import finished', $text,
                'REST pipeline should finish cleanly against the authed fixture'
            );
        });

        // --- Every recorded guid should have landed as a content row
        // with `import_source=wordpress_rest` on its content_data.
        foreach (self::FIXTURE_WP_GUIDS as $guid) {
            $row = DB::table('content_data')
                ->where('field_name', 'source_guid')
                ->where('field_value', $guid)
                ->first();
            $this->assertNotNull($row,
                "Missing content_data source_guid row for {$guid} — the authed REST walk did not land it"
            );

            $content = DB::table('content')->where('id', $row->rel_id)->first();
            $this->assertNotNull($content, "Content row for {$guid} was not persisted");
            $this->assertSame(1, (int)$content->is_active);
            $this->assertSame(0, (int)$content->is_deleted);

            $markerExists = DB::table('content_data')
                ->where('rel_id', $row->rel_id)
                ->where('field_name', 'import_source')
                ->where('field_value', 'wordpress_rest')
                ->exists();
            $this->assertTrue($markerExists,
                "Expected import_source=wordpress_rest content_data marker for {$guid}"
            );
        }

        // --- Gutenberg body passthrough: the `<!-- wp:paragraph -->`
        // comment and the post title are byte-stable from fixture to
        // stored content row. If either gets sanitized away, a later
        // block-editor round-trip would lose the WP block boundary.
        $launch = $this->findContentByGuid('wp:1001');
        $this->assertSame('Launch day: what shipped', $launch->title);
        $this->assertStringContainsString('<!-- wp:paragraph -->', (string)$launch->content,
            'Gutenberg block-comment markers must survive the REST import untouched'
        );

        // The second post carries a different shape (bare <p> with an
        // inline <img>) — proof the importer doesn't require Gutenberg
        // markers to accept a body.
        $chart = $this->findContentByGuid('wp:1002');
        $this->assertSame('Reading the chart', $chart->title);
        $this->assertStringContainsString('<img', (string)$chart->content);

        // The page row (WP type=page → wp:2001) lands with its title
        // intact. MigrationItemDTO doesn't carry WP's `type` today,
        // so everything maps to the mapper's default content_type —
        // per-type mapping is a follow-on once the DTO grows a kind.
        $about = $this->findContentByGuid('wp:2001');
        $this->assertSame('About', $about->title);

        // --- Taxonomy-first pass: fixture post 1001 carries
        // categories=[11,12] (→ news, tech) and tags=[31,32] (→ launch,
        // featured). Assert the mapper resolved those REST ids back to
        // the pre-seeded local ids and wrote categories_items +
        // tagging_tagged rows on the same save as the content row.
        $launchId = (int)$launch->id;

        // TaxonomyIndex upserts missing categories as rows keyed by
        // (data_type=category, rel_type=Content, url=slug) during the
        // walk. We look up the ids it created to compare against the
        // categories_items attachments the mapper wrote.
        $localCategoryIds = DB::table('categories')
            ->where('data_type', 'category')
            ->where('rel_type', Content::class)
            ->whereIn('url', self::FIXTURE_CATEGORY_SLUGS)
            ->pluck('id')
            ->map(fn ($v) => (int)$v)
            ->sort()
            ->values()
            ->all();
        $this->assertCount(count(self::FIXTURE_CATEGORY_SLUGS), $localCategoryIds,
            'TaxonomyIndex should have upserted News and Tech categories from the REST walk'
        );

        $attachedCategoryIds = DB::table('categories_items')
            ->where('rel_id', $launchId)
            ->pluck('parent_id')
            ->map(fn ($v) => (int)$v)
            ->sort()
            ->values()
            ->all();
        $this->assertSame($localCategoryIds, $attachedCategoryIds,
            'Categories News+Tech should be attached to wp:1001 via the taxonomy-first pass'
        );

        $attachedTagSlugs = DB::table('tagging_tagged')
            ->where('taggable_id', $launchId)
            ->pluck('tag_slug')
            ->map(fn ($v) => (string)$v)
            ->sort()
            ->values()
            ->all();
        $this->assertSame(
            collect(self::FIXTURE_TAG_SLUGS)->sort()->values()->all(),
            $attachedTagSlugs,
            'Tags Launch+Featured should be attached to wp:1001 via the taxonomy-first pass'
        );

        // --- Public frontend sanity: after writing three new content
        // rows (and a handful of taxonomy attachments), site root must
        // still render. A 500 here would mean REST import corrupted
        // shared state — categories cache, sitemap, etc.
        $this->browse(function (Browser $browser) {
            $browser->visit('/')->pause(2000);
            $source = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Whoops, looks like something went wrong', $source,
                'Public frontend must not 500 after a REST import wrote into content/'
            );
            $this->assertStringNotContainsString('Internal Server Error', $source);
        });

        // --- Job bookkeeping: REST progress exposes media_count and
        // warnings in addition to imported+pages_fetched. Presence of
        // media_count proves mode selection went through runRestImport
        // rather than falling back to RSS/sitemap.
        $job = DB::table('wp_migration_jobs')
            ->where('source_url', $fixtureUrl)
            ->first();
        $this->assertNotNull($job, 'Migration job row should exist after a Start-import click');
        $this->assertSame('finished', $job->status);
        $this->assertSame('rest', $job->mode,
            'Probe should pick REST over RSS in rest-authed fixture mode');
        $progress = json_decode((string)$job->progress, true);
        $this->assertIsArray($progress);
        $this->assertSame(count(self::FIXTURE_WP_GUIDS), (int)($progress['imported'] ?? 0),
            'Progress must record all three fixture items (2 posts + 1 page) as imported'
        );
        $this->assertArrayHasKey('media_count', $progress,
            'REST runs record media_count; its absence would mean mode selection fell through'
        );
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
            ->whereIn('field_value', ['wordpress_rest', 'wordpress_rss', 'wordpress_sitemap'])
            ->pluck('rel_id');

        if ($contentIds->isNotEmpty()) {
            DB::table('content_data')->whereIn('rel_id', $contentIds)->delete();
            DB::table('content')->whereIn('id', $contentIds)->delete();
            DB::table('categories_items')->whereIn('rel_id', $contentIds)->delete();
            DB::table('tagging_tagged')->whereIn('taggable_id', $contentIds)->delete();
        }
    }

    private function purgeJobRows(): void
    {
        DB::table('wp_migration_jobs')
            ->where('source_host', self::FIXTURE_HOST)
            ->delete();
    }

    /**
     * Clean up any prior attachment rows on content we're about to
     * re-import so the exact-match assertion on attached ids isn't
     * polluted by a stale previous run.
     */
    private function purgeTaxonomyAttachments(): void
    {
        $contentIds = DB::table('content_data')
            ->where('field_name', 'source_guid')
            ->whereIn('field_value', self::FIXTURE_WP_GUIDS)
            ->pluck('rel_id');

        if ($contentIds->isNotEmpty()) {
            DB::table('categories_items')->whereIn('rel_id', $contentIds)->delete();
            DB::table('tagging_tagged')->whereIn('taggable_id', $contentIds)->delete();
        }
    }

    /**
     * Drop the auto-upserted category rows TaxonomyIndex created for
     * the fixture's `news` / `tech` slugs, and the tag rows tagging
     * created for `launch` / `featured`, so reruns start clean. We
     * match on the stable (data_type, rel_type, url) tuple the index
     * writes — regular CMS categories with matching slugs would need
     * to use a different URL so this won't touch them.
     */
    private function purgeFixtureTaxonomies(): void
    {
        DB::table('categories')
            ->where('data_type', 'category')
            ->where('rel_type', Content::class)
            ->whereIn('url', self::FIXTURE_CATEGORY_SLUGS)
            ->delete();
        DB::table('tagging_tags')
            ->whereIn('slug', self::FIXTURE_TAG_SLUGS)
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
            [
                // rest-authed mode: /feed and /sitemap 404 so the
                // probe's only capability is REST, AND /wp/v2/*
                // requires Basic auth — the test would fail if the
                // page forgot to attach the app-password header.
                'WP_FIXTURE_MODE' => 'rest-authed',
                // The recorded fixtures under tests/fixtures/wp/wp-json/*.json
                // carry canonical `https://wp.example` URLs; rewriting
                // those to the fixture host keeps imported links
                // resolvable back at the running server.
                'WP_FIXTURE_REST_BASE' => 'http://' . self::FIXTURE_HOST . ':' . self::FIXTURE_PORT,
                'WP_FIXTURE_REST_USER' => self::FIXTURE_REST_USER,
                'WP_FIXTURE_REST_PASS' => self::FIXTURE_REST_PASS,
            ],
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
