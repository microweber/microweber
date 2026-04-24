<?php

namespace Tests\Browser;

use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use Modules\WordPressMigration\Services\StagingWriter;
use Modules\WordPressMigration\Services\WordPressContentMapper;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Captures a curated set of screenshots for docs/migration/wordpress.md.
 *
 * This is a documentation-capture utility, not a regression test. It
 * is tagged with the `docs` Dusk group so it is NOT part of the default
 * `composer test:browser` run — a contributor who wants to refresh
 * the docs images invokes it explicitly:
 *
 *   php artisan dusk --group=docs
 *
 * The PNGs are written next to docs/migration/wordpress.md under
 * `docs/migration/screenshots/` so the markdown's relative image
 * references work on GitHub, VitePress, and any local preview.
 */
class DocsWordPressImportScreenshotsTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const DOCS_JOB_SOURCE_URL = 'docs-screenshots://wordpress.example.invalid';

    private const DOCS_SOURCE_HOST = 'docs-screenshots.example.invalid';

    /** @var list<string> */
    private const DOCS_GUIDS = [
        'docs:hello-world',
        'docs:second-post',
        'docs:third-post',
    ];

    protected function assertPreConditions(): void
    {
        // Rely on an already-running dev server at :8000 and an already-running
        // fixture at :18877 (see docs/migration/wordpress.md for the commands).
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->purgeFixture();
    }

    protected function tearDown(): void
    {
        $this->purgeFixture();
        parent::tearDown();
    }

    /**
     * @group docs
     */
    #[Test]
    public function captures_screenshots_for_the_walkthrough(): void
    {
        $jobId = $this->seedRunningJobWithStaging();

        $this->browse(function (Browser $browser) use ($jobId) {
            $this->loginAsAdmin($browser);

            $dir = base_path('docs/migration/screenshots');
            if (! is_dir($dir)) {
                mkdir($dir, 0775, true);
            }

            // 1. Resource index (list of imports under Content → Import from WordPress)
            $browser->visit('/admin/word-press-migration-resource')->pause(3000);
            $this->ensureLoggedIn($browser);
            $this->snap($browser, $dir, '01-import-list');

            // 2. Standalone import page (URL probe entry point)
            $browser->visit('/admin/word-press-migration-import-page')->pause(3000);
            $this->ensureLoggedIn($browser);
            $this->snap($browser, $dir, '02-url-probe-empty');

            // 3. Job detail / Live progress panel (uses seeded running job)
            $browser->visit('/admin/word-press-migration-resource/' . $jobId)->pause(3000);
            $this->ensureLoggedIn($browser);
            $this->snap($browser, $dir, '03-job-view-progress');

            // 4. Preview page scoped to the seeded job
            $browser->visit('/admin/word-press-migration-preview-page?job=' . $jobId)->pause(3000);
            $this->ensureLoggedIn($browser);
            $browser->waitFor('[data-testid="preview-table"]', 15);
            $this->snap($browser, $dir, '04-preview-staging');

            // 5. Logs page
            $browser->visit('/admin/word-press-migration-resource/' . $jobId . '/logs')->pause(3000);
            $this->ensureLoggedIn($browser);
            $this->snap($browser, $dir, '05-job-logs');
        });

        // Every screenshot the walkthrough references has to exist on disk.
        foreach ([
            '01-import-list',
            '02-url-probe-empty',
            '03-job-view-progress',
            '04-preview-staging',
            '05-job-logs',
        ] as $name) {
            $path = base_path("docs/migration/screenshots/{$name}.png");
            $this->assertFileExists($path, "Expected screenshot missing: {$name}.png");
        }
    }

    private function snap(Browser $browser, string $dir, string $name): void
    {
        $tmp = $browser->screenshot($name);
        // Dusk writes into tests/Browser/screenshots by default; move to docs.
        $src = base_path('tests/Browser/screenshots/' . $name . '.png');
        if (is_file($src)) {
            @copy($src, $dir . '/' . $name . '.png');
        }
    }

    private function seedRunningJobWithStaging(): int
    {
        $job = WordPressMigrationJob::create([
            'source_url' => self::DOCS_JOB_SOURCE_URL,
            'source_url_hash' => hash('sha256', self::DOCS_JOB_SOURCE_URL),
            'source_host' => self::DOCS_SOURCE_HOST,
            'status' => WordPressMigrationJob::STATUS_RUNNING,
            'mode' => 'rest',
            'probe_result' => [
                'mode' => 'rest',
                'estimated_posts' => 42,
                'estimated_pages' => 5,
            ],
            'progress' => ['processed' => 18, 'total' => 47, 'failed' => 0],
            'started_at' => now()->subMinutes(3),
        ]);
        $jobId = (int) $job->id;

        $writer = new StagingWriter();
        foreach (self::DOCS_GUIDS as $guid) {
            $writer->stage($jobId, new MigrationItemDTO(
                guid: $guid,
                title: 'Docs sample: ' . ucwords(str_replace(['docs:', '-'], ['', ' '], $guid)),
                html: "<p>Docs body for {$guid}.</p>",
                excerpt: null,
                author: null,
                categories: [],
                tags: [],
                publishedAt: new DateTimeImmutable('2026-04-01T12:00:00+00:00'),
                canonicalUrl: 'https://' . self::DOCS_SOURCE_HOST . '/' . $guid,
                source: 'rest',
                sourceHost: self::DOCS_SOURCE_HOST,
            ));
        }

        return $jobId;
    }

    private function purgeFixture(): void
    {
        $jobIds = DB::table('wp_migration_jobs')
            ->where('source_url', self::DOCS_JOB_SOURCE_URL)
            ->pluck('id')
            ->all();

        if (! empty($jobIds)) {
            DB::table('wp_migration_staging_media')->whereIn('job_id', $jobIds)->delete();
            DB::table('wp_migration_staging_content')->whereIn('job_id', $jobIds)->delete();
            DB::table('wp_migration_jobs')->whereIn('id', $jobIds)->delete();
        }

        $contentIds = DB::table('content_data')
            ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
            ->whereIn('field_value', self::DOCS_GUIDS)
            ->pluck('rel_id')
            ->all();

        if (! empty($contentIds)) {
            DB::table('content_data')->whereIn('rel_id', $contentIds)->delete();
            DB::table('content')->whereIn('id', $contentIds)->delete();
        }
    }
}
