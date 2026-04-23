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
    public function start_import_refuses_when_source_has_no_rss_capability(): void
    {
        // REST-only source: the probe advertises the `rest` mode but
        // Phase 3 only wires the RSS importer. Clicking Start import
        // must surface a clear "coming soon" message rather than
        // silently flipping the job to running.
        $this->bindFakeFetcher(FakeHttpProbeFetcher::rest('https://wp.example', posts: 5, pages: 1));

        Livewire::test(WordPressMigrationImportPage::class)
            ->set('data.source_url', 'https://wp.example')
            ->call('check')
            ->call('startImport');

        $job = WordPressMigrationJob::query()->first();
        $this->assertSame(WordPressMigrationJob::STATUS_READY, $job->status,
            'REST-only sources stay ready until their importer ships in a later phase'
        );
    }

    /**
     * Remove any content rows that previous test runs (or the happy
     * path above) left behind, so assertions against a clean slate
     * don't depend on test ordering.
     */
    private function cleanupImportedContent(): void
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
