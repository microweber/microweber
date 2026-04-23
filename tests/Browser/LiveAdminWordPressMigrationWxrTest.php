<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Modules\Content\Models\Content;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * End-to-end WXR-import test: loads the Import-from-WordPress page,
 * uploads the canonical `tests/fixtures/wp/wxr-sample.xml` file
 * through the Livewire temp-upload channel, clicks **Import WXR**,
 * and verifies that:
 *
 *   1. The three publishable items from the fixture (wp:1001,
 *      wp:1002, wp:2001) all land as Microweber content rows with
 *      `import_source=wordpress_wxr`.
 *   2. The Gutenberg HTML in `content:encoded` survives unmangled —
 *      the `<!-- wp:paragraph -->` block comments on wp:1001 are
 *      preserved so a later block-editor round-trip doesn't lose
 *      the WP block boundaries.
 *   3. The taxonomy-first pass attaches `categories_items` +
 *      `tagging_tagged` rows for wp:1001 (News/Tech categories,
 *      Launch/Featured tags) via the channel index the WXR carried,
 *      not a second HTTP probe.
 *   4. The public frontend still renders after the import writes.
 *
 * Unlike the REST/RSS/sitemap tests this doesn't spin up a PHP
 * built-in fixture server — WXR is entirely offline, the operator
 * hands us a file and we import it in-process.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user admin@admin.com / admin
 *   - chromedriver reachable on :9515 (DuskTestCase starts it)
 */
class LiveAdminWordPressMigrationWxrTest extends DuskTestCase
{
    use AdminLoginTrait;

    /**
     * Guids from the fixture WXR that MUST land in content. The
     * fixture also contains a draft (wp:9001), a revision (wp:8001),
     * a nav_menu_item (wp:7001), and an attachment (wp:3001) — all
     * expected to be filtered out by WxrImporter::classifyItem().
     */
    private const FIXTURE_WP_GUIDS = ['wp:1001', 'wp:1002', 'wp:2001'];

    /** Category slugs the WXR channel block declares. */
    private const FIXTURE_CATEGORY_SLUGS = ['news', 'tech'];

    /** Tag slugs the WXR channel block declares. */
    private const FIXTURE_TAG_SLUGS = ['launch', 'featured'];

    /** Must match WxrImporter's <wp:base_site_url> for the sample fixture. */
    private const FIXTURE_SOURCE_HOST = 'wxr-fixture.example';

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
    }

    protected function tearDown(): void
    {
        $this->purgeTaxonomyAttachments();
        $this->purgeImportedContent();
        $this->purgeFixtureTaxonomies();
        $this->purgeJobRows();
        parent::tearDown();
    }

    #[Test]
    public function wxr_upload_imports_posts_pages_and_attaches_taxonomies(): void
    {
        $fixturePath = base_path('tests/fixtures/wp/wxr-sample.xml');
        $this->assertFileExists($fixturePath, 'Canonical WXR fixture missing');

        $this->browse(function (Browser $browser) use ($fixturePath) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/word-press-migration-import-page')->pause(5000);
            $this->ensureLoggedIn($browser);

            $browser->waitFor('[data-testid="wxr-upload-section"]', 15)
                ->waitFor('[data-testid="wxr-upload-input"]', 5)
                ->attach('[data-testid="wxr-upload-input"]', $fixturePath);

            // The upload goes through Livewire's temp-file endpoint;
            // wait for the Import button to appear (gated by
            // `@if($wxrUpload)`) so we know the file has been received
            // and the component re-rendered.
            $browser->waitFor('[data-testid="wxr-import-button"]', 20);

            // wire:confirm pops a native confirm dialog; stub it so
            // the click doesn't race the Livewire request.
            $browser->script('window.confirm = function () { return true; };');

            $browser->click('[data-testid="wxr-import-button"]');

            $browser->waitUsing(30, 500, function () use ($browser) {
                $text = $browser->script('return document.body.innerText;')[0] ?? '';
                return str_contains($text, 'Import finished')
                    || str_contains($text, 'Import failed');
            }, 'Expected an Import finished / failed notification within 30s');

            $text = $browser->script('return document.body.innerText;')[0] ?? '';
            $this->assertStringContainsString('Import finished', $text,
                'WXR import pipeline should finish cleanly against the sample fixture'
            );
        });

        // --- Every publishable guid should have landed with
        //     import_source=wordpress_wxr on its content_data.
        foreach (self::FIXTURE_WP_GUIDS as $guid) {
            $row = DB::table('content_data')
                ->where('field_name', 'source_guid')
                ->where('field_value', $guid)
                ->first();
            $this->assertNotNull($row,
                "Missing content_data source_guid row for {$guid} — WXR import did not land it"
            );

            $content = DB::table('content')->where('id', $row->rel_id)->first();
            $this->assertNotNull($content, "Content row for {$guid} was not persisted");
            $this->assertSame(1, (int)$content->is_active);
            $this->assertSame(0, (int)$content->is_deleted);

            $markerExists = DB::table('content_data')
                ->where('rel_id', $row->rel_id)
                ->where('field_name', 'import_source')
                ->where('field_value', 'wordpress_wxr')
                ->exists();
            $this->assertTrue($markerExists,
                "Expected import_source=wordpress_wxr content_data marker for {$guid}"
            );
        }

        // --- Gutenberg passthrough check on wp:1001: the fixture's
        //     <content:encoded> wraps a paragraph in WP block comments;
        //     those markers must come through byte-stable so a later
        //     block-editor round-trip can re-parse them.
        $launch = $this->findContentByGuid('wp:1001');
        $this->assertSame('Launch day: what shipped', $launch->title);
        $this->assertStringContainsString('<!-- wp:paragraph -->', (string)$launch->content,
            'Gutenberg block-comment markers must survive the WXR import untouched'
        );

        // wp:1002 carries a bare <p> with an inline <img> — proof the
        // importer doesn't require Gutenberg markers to accept a body.
        $chart = $this->findContentByGuid('wp:1002');
        $this->assertSame('Reading the chart', $chart->title);
        $this->assertStringContainsString('<img', (string)$chart->content);

        // wp:2001 is the WP page (<wp:post_type>page</wp:post_type>);
        // MigrationItemDTO doesn't carry a kind today so it lands with
        // the mapper's default content_type, but title + guid must be
        // intact.
        $about = $this->findContentByGuid('wp:2001');
        $this->assertSame('About', $about->title);

        // --- Filtered items MUST NOT appear as content rows. Drafts,
        //     revisions, nav_menu_items, and attachments are routed
        //     elsewhere by WxrImporter::classifyItem().
        foreach (['wp:9001', 'wp:8001', 'wp:7001', 'wp:3001'] as $skippedGuid) {
            $unexpected = DB::table('content_data')
                ->where('field_name', 'source_guid')
                ->where('field_value', $skippedGuid)
                ->exists();
            $this->assertFalse($unexpected,
                "{$skippedGuid} should have been filtered out by the WXR importer"
            );
        }

        // --- Taxonomy-first pass: fixture post 1001 declares
        //     News+Tech categories and Launch+Featured tags through
        //     its channel-level <wp:category> / <wp:tag> blocks. The
        //     mapper should have attached them by local id on the
        //     same save as the content row.
        $launchId = (int)$launch->id;

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
            'TaxonomyIndex should have upserted News and Tech categories from the WXR channel index'
        );

        $attachedCategoryIds = DB::table('categories_items')
            ->where('rel_id', $launchId)
            ->pluck('parent_id')
            ->map(fn ($v) => (int)$v)
            ->sort()
            ->values()
            ->all();
        $this->assertSame($localCategoryIds, $attachedCategoryIds,
            'News+Tech categories should be attached to wp:1001 via the taxonomy-first pass'
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
            'Launch+Featured tags should be attached to wp:1001 via the taxonomy-first pass'
        );

        // --- Public frontend sanity: site root must still render
        //     after WXR writes new rows and attachment records.
        $this->browse(function (Browser $browser) {
            $browser->visit('/')->pause(2000);
            $source = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Whoops, looks like something went wrong', $source,
                'Public frontend must not 500 after a WXR import wrote into content/'
            );
            $this->assertStringNotContainsString('Internal Server Error', $source);
        });

        // --- Job bookkeeping: the WXR runner persists a
        //     wp_migration_jobs row keyed by the synthetic
        //     `wxr://{host}` URL with mode=wxr and imported counts
        //     matching the three publishable items.
        $job = DB::table('wp_migration_jobs')
            ->where('source_url', 'wxr://' . self::FIXTURE_SOURCE_HOST)
            ->first();
        $this->assertNotNull($job,
            'Migration job row should exist after a WXR import — keyed by wxr://{source-host}'
        );
        $this->assertSame('finished', $job->status);
        $this->assertSame('wxr', $job->mode);

        $progress = json_decode((string)$job->progress, true);
        $this->assertIsArray($progress);
        $this->assertSame(count(self::FIXTURE_WP_GUIDS), (int)($progress['imported'] ?? 0),
            'Progress must record all three publishable items (2 posts + 1 page)'
        );
        $this->assertGreaterThan(count(self::FIXTURE_WP_GUIDS), (int)($progress['items_seen'] ?? 0),
            'items_seen counts every <item> in the WXR (including skipped) and must exceed imported'
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

    private function purgeImportedContent(): void
    {
        $contentIds = DB::table('content_data')
            ->where('field_name', 'import_source')
            ->whereIn('field_value', ['wordpress_rest', 'wordpress_rss', 'wordpress_sitemap', 'wordpress_wxr'])
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
            ->where('source_url', 'like', 'wxr://%')
            ->delete();
    }

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
     * Drop the auto-upserted category/tag rows TaxonomyIndex created
     * for the fixture's slugs so reruns start clean. Matches on the
     * stable tuple the index writes — regular CMS rows with matching
     * slugs would need a different (data_type, rel_type) or a
     * non-fixture tag name to collide.
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
}
