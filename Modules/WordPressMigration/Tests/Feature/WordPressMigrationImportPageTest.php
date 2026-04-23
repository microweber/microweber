<?php

namespace Modules\WordPressMigration\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Modules\WordPressMigration\Filament\Pages\WordPressMigrationImportPage;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use Modules\WordPressMigration\Services\Http\HttpProbeFetcher;
use Modules\WordPressMigration\Tests\Support\FakeHttpProbeFetcher;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

/**
 * Livewire/Filament coverage for the "Import from WordPress" page.
 *
 * The page is the full operator surface for Phase 2 — URL input,
 * probe, persisted summary, Start-import CTA. Tests swap the real
 * HttpProbeFetcher for a scripted one so probe outcomes are
 * deterministic without real network calls.
 */
class WordPressMigrationImportPageTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('wp_migration_jobs')) {
            $this->artisan('module:migrate', ['module' => 'WordPressMigration']);
        }
        DB::table('wp_migration_jobs')->delete();

        $this->setUpFilamentPanel();
    }

    private function bindFakeFetcher(FakeHttpProbeFetcher $fetcher): void
    {
        $this->app->instance(HttpProbeFetcher::class, $fetcher);
    }

    #[Test]
    public function the_page_renders_for_authenticated_admins(): void
    {
        Livewire::test(WordPressMigrationImportPage::class)
            ->assertSuccessful()
            ->assertSee('WordPress site URL');
    }

    #[Test]
    public function check_action_runs_probe_and_persists_a_job(): void
    {
        $this->bindFakeFetcher(FakeHttpProbeFetcher::rest('https://wp.example', posts: 42, pages: 7));

        Livewire::test(WordPressMigrationImportPage::class)
            ->set('data.source_url', 'https://wp.example')
            ->call('check')
            ->assertSuccessful();

        $this->assertDatabaseHas('wp_migration_jobs', [
            'source_url' => 'https://wp.example',
            'source_host' => 'wp.example',
            'mode' => 'rest',
            'status' => WordPressMigrationJob::STATUS_READY,
        ]);
    }

    #[Test]
    public function check_action_exposes_the_probe_summary_for_the_view(): void
    {
        $this->bindFakeFetcher(FakeHttpProbeFetcher::rest('https://wp.example', posts: 100, pages: 5));

        $component = Livewire::test(WordPressMigrationImportPage::class)
            ->set('data.source_url', 'https://wp.example')
            ->call('check');

        $jobId = $component->get('jobId');
        $this->assertNotNull($jobId);

        $summary = $component->instance()->getProbeSummary();
        $this->assertSame('rest', $summary['mode']);
        $this->assertSame(100, $summary['estimated_posts']);
        $this->assertSame(5, $summary['estimated_pages']);
        $this->assertSame(WordPressMigrationJob::STATUS_READY, $summary['status']);
        $this->assertTrue($summary['is_usable']);
        $this->assertContains('rest', $summary['capabilities']);
    }

    #[Test]
    public function check_action_with_empty_url_does_not_run_the_probe(): void
    {
        $fetcher = FakeHttpProbeFetcher::rest('https://wp.example', posts: 1, pages: 0);
        $this->bindFakeFetcher($fetcher);

        Livewire::test(WordPressMigrationImportPage::class)
            ->set('data.source_url', '')
            ->call('check');

        $this->assertSame([], $fetcher->fetched);
        $this->assertSame(0, DB::table('wp_migration_jobs')->count());
    }

    #[Test]
    public function check_action_marks_unreachable_sources_as_unreachable(): void
    {
        $this->bindFakeFetcher(FakeHttpProbeFetcher::unreachable('https://offline.example'));

        Livewire::test(WordPressMigrationImportPage::class)
            ->set('data.source_url', 'https://offline.example')
            ->call('check');

        $this->assertDatabaseHas('wp_migration_jobs', [
            'source_host' => 'offline.example',
            'status' => WordPressMigrationJob::STATUS_UNREACHABLE,
        ]);
    }

    #[Test]
    public function re_checking_the_same_url_upserts_onto_the_same_row(): void
    {
        $this->bindFakeFetcher(FakeHttpProbeFetcher::rest('https://wp.example', posts: 10, pages: 0));

        $component = Livewire::test(WordPressMigrationImportPage::class)
            ->set('data.source_url', 'https://wp.example')
            ->call('check');

        $firstId = $component->get('jobId');

        $this->bindFakeFetcher(FakeHttpProbeFetcher::rest('https://wp.example', posts: 25, pages: 3));

        $component
            ->set('data.source_url', 'https://wp.example')
            ->call('check');

        $secondId = $component->get('jobId');

        $this->assertSame($firstId, $secondId);
        $this->assertSame(1, DB::table('wp_migration_jobs')->count());

        $summary = $component->instance()->getProbeSummary();
        $this->assertSame(25, $summary['estimated_posts']);
        $this->assertSame(3, $summary['estimated_pages']);
    }

    #[Test]
    public function application_password_flows_into_the_persisted_job(): void
    {
        $this->bindFakeFetcher(FakeHttpProbeFetcher::rest('https://wp.example', posts: 1, pages: 0));

        Livewire::test(WordPressMigrationImportPage::class)
            ->set('data.source_url', 'https://wp.example')
            ->set('data.wp_application_password', 'abcd efgh ijkl mnop')
            ->call('check');

        $job = WordPressMigrationJob::query()->first();
        $this->assertSame('abcd efgh ijkl mnop', $job->encrypted_credentials);
        $this->assertTrue($job->hasValidCredentials());
    }

    #[Test]
    public function start_import_runs_rss_pipeline_and_finishes_when_feed_is_available(): void
    {
        // Wire both the probe and the importer through the same
        // scripted fetcher — app(RssFeedImporter::class) picks up
        // the bound HttpProbeFetcher via constructor injection.
        $rssBody = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title>WP Example</title>
    <item>
      <title>Feature test post</title>
      <link>https://wp.example/feature-test-post</link>
      <guid>https://wp.example/?p=777</guid>
      <content:encoded><![CDATA[<p>Feature body.</p>]]></content:encoded>
    </item>
  </channel>
</rss>
XML;
        $this->bindFakeFetcher(new FakeHttpProbeFetcher([
            'https://wp.example/wp-json' => ['body' => '', 'http_code' => 404, 'error' => ''],
            'https://wp.example/wp-json/wp/v2/posts?per_page=1' => ['body' => '', 'http_code' => 404, 'error' => ''],
            'https://wp.example/wp-json/wp/v2/pages?per_page=1' => ['body' => '', 'http_code' => 404, 'error' => ''],
            'https://wp.example/feed' => ['body' => $rssBody, 'http_code' => 200, 'error' => ''],
            // Walker keeps paging until an empty page or 404.
            'https://wp.example/feed/' => ['body' => $rssBody, 'http_code' => 200, 'error' => ''],
            'https://wp.example/feed/?paged=2' => ['body' => '', 'http_code' => 404, 'error' => ''],
            'https://wp.example/sitemap.xml' => ['body' => '', 'http_code' => 404, 'error' => ''],
            'https://wp.example/sitemap_index.xml' => ['body' => '', 'http_code' => 404, 'error' => ''],
            'https://wp.example/robots.txt' => ['body' => '', 'http_code' => 404, 'error' => ''],
        ]));

        $this->cleanupImportedContent();

        Livewire::test(WordPressMigrationImportPage::class)
            ->set('data.source_url', 'https://wp.example')
            ->call('check')
            ->call('startImport');

        $job = WordPressMigrationJob::query()->first();
        $this->assertSame(WordPressMigrationJob::STATUS_FINISHED, $job->status);
        $this->assertNotNull($job->started_at);
        $this->assertNotNull($job->finished_at);
        $this->assertSame(1, (int)($job->progress['imported'] ?? 0));

        $this->assertDatabaseHas('content', [
            'title' => 'Feature test post',
            'content_type' => 'post',
        ]);
        $this->assertDatabaseHas('content_data', [
            'field_name' => 'source_guid',
            'field_value' => 'https://wp.example/?p=777',
        ]);

        $this->cleanupImportedContent();
    }

    #[Test]
    public function start_import_runs_rest_pipeline_when_rest_is_advertised(): void
    {
        // REST-only source: probe advertises the `rest` mode, /feed
        // and /sitemap are absent. The page's mode-selection picks
        // REST over RSS/sitemap and the WpRestImporter walks
        // /wp-json/wp/v2/* end-to-end. We script every list endpoint
        // the walker touches so no transport errors leak into the
        // pipeline and the test asserts the REST-specific progress
        // shape (media_count, warnings) that RSS never produces.
        $root = json_encode(['name' => 'Test WP Site', 'namespaces' => ['wp/v2']]);
        $postBody = json_encode([
            [
                'id' => 1001,
                'date_gmt' => '2026-04-10T12:00:00',
                'guid' => ['rendered' => 'https://wp.example/?p=1001'],
                'slug' => 'hello-rest',
                'status' => 'publish',
                'type' => 'post',
                'link' => 'https://wp.example/2026/04/hello-rest/',
                'title' => ['rendered' => 'Hello REST'],
                'content' => ['rendered' => '<p>Body from the REST importer.</p>'],
                'excerpt' => ['rendered' => ''],
                'author' => 5,
                'featured_media' => 0,
                'categories' => [],
                'tags' => [],
            ],
        ]);
        $this->bindFakeFetcher(new FakeHttpProbeFetcher([
            // Probe
            'https://wp.example/wp-json' => ['body' => $root, 'http_code' => 200, 'error' => ''],
            'https://wp.example/wp-json/wp/v2/posts?per_page=1' => [
                'body' => '[]', 'http_code' => 200, 'error' => '', 'headers' => ['x-wp-total' => '1', 'x-wp-totalpages' => '1'],
            ],
            'https://wp.example/wp-json/wp/v2/pages?per_page=1' => [
                'body' => '[]', 'http_code' => 200, 'error' => '', 'headers' => ['x-wp-total' => '0', 'x-wp-totalpages' => '0'],
            ],
            'https://wp.example/feed' => ['body' => '', 'http_code' => 404, 'error' => ''],
            'https://wp.example/sitemap.xml' => ['body' => '', 'http_code' => 404, 'error' => ''],
            'https://wp.example/sitemap_index.xml' => ['body' => '', 'http_code' => 404, 'error' => ''],
            'https://wp.example/robots.txt' => ['body' => '', 'http_code' => 404, 'error' => ''],
            // Walker enrichers — all empty; the point here is that
            // the page wires REST, not that it carries taxonomy data.
            'https://wp.example/wp-json/wp/v2/categories?per_page=100&page=1' => ['body' => '[]', 'http_code' => 200, 'error' => ''],
            'https://wp.example/wp-json/wp/v2/tags?per_page=100&page=1' => ['body' => '[]', 'http_code' => 200, 'error' => ''],
            'https://wp.example/wp-json/wp/v2/users?per_page=100&page=1' => ['body' => '[]', 'http_code' => 200, 'error' => ''],
            'https://wp.example/wp-json/wp/v2/media?per_page=100&page=1' => ['body' => '[]', 'http_code' => 200, 'error' => ''],
            'https://wp.example/wp-json/wp/v2/comments?per_page=100&page=1' => ['body' => '[]', 'http_code' => 200, 'error' => ''],
            'https://wp.example/wp-json/wp/v2/menus?per_page=100' => ['body' => '[]', 'http_code' => 200, 'error' => ''],
            // Walker content
            'https://wp.example/wp-json/wp/v2/posts?per_page=100&page=1' => ['body' => $postBody, 'http_code' => 200, 'error' => ''],
            'https://wp.example/wp-json/wp/v2/pages?per_page=100&page=1' => ['body' => '[]', 'http_code' => 200, 'error' => ''],
        ]));

        $this->cleanupImportedContent();

        Livewire::test(WordPressMigrationImportPage::class)
            ->set('data.source_url', 'https://wp.example')
            ->call('check')
            ->call('startImport');

        $job = WordPressMigrationJob::query()->first();
        $this->assertSame(WordPressMigrationJob::STATUS_FINISHED, $job->status,
            'REST-advertising source should finish cleanly via the REST importer'
        );
        $this->assertSame(1, (int)($job->progress['imported'] ?? 0));
        // media_count is a REST-only progress key — its presence
        // proves mode selection actually chose runRestImport().
        $this->assertArrayHasKey('media_count', $job->progress);

        $this->assertDatabaseHas('content_data', [
            'field_name' => 'import_source',
            'field_value' => 'wordpress_rest',
        ]);
        $this->assertDatabaseHas('content_data', [
            'field_name' => 'source_guid',
            'field_value' => 'wp:1001',
        ]);

        $this->cleanupImportedContent();
    }

    /**
     * Remove any content rows that previous test runs (or the happy
     * paths above) left behind, so assertions against a clean slate
     * don't depend on test ordering. Covers both RSS- and REST-imported
     * rows since either importer can run in a given test.
     */
    private function cleanupImportedContent(): void
    {
        $contentIds = DB::table('content_data')
            ->where('field_name', 'import_source')
            ->whereIn('field_value', ['wordpress_rss', 'wordpress_rest', 'wordpress_sitemap'])
            ->pluck('rel_id');

        if ($contentIds->isNotEmpty()) {
            DB::table('content_data')->whereIn('rel_id', $contentIds)->delete();
            DB::table('content')->whereIn('id', $contentIds)->delete();
        }
    }

    #[Test]
    public function start_import_refuses_when_no_probe_has_run_yet(): void
    {
        Livewire::test(WordPressMigrationImportPage::class)
            ->call('startImport');

        $this->assertSame(0, DB::table('wp_migration_jobs')->count());
    }

    #[Test]
    public function start_import_refuses_when_source_is_unreachable(): void
    {
        $this->bindFakeFetcher(FakeHttpProbeFetcher::unreachable('https://offline.example'));

        Livewire::test(WordPressMigrationImportPage::class)
            ->set('data.source_url', 'https://offline.example')
            ->call('check')
            ->call('startImport');

        $job = WordPressMigrationJob::query()->first();
        $this->assertSame(WordPressMigrationJob::STATUS_UNREACHABLE, $job->status);
    }
}
