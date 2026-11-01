<?php

namespace Modules\WordPressMigration\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource;
use Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages\CreateWordPressMigration;
use Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages\ListWordPressMigrations;
use Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages\ViewWordPressMigration;
use Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages\WordPressMigrationLogsPage;
use Modules\WordPressMigration\Models\StagingContent;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use Modules\WordPressMigration\Services\WordPressContentMapper;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

/**
 * Phase 9 coverage for the WordPressMigrationResource and its four
 * pages (List / Create / View / Logs).
 *
 * Tests deliberately stop at the Livewire surface; the underlying
 * importer/probe plumbing has its own coverage and does not need
 * to be re-exercised here.
 */
class WordPressMigrationResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    private const SOURCE_URL = 'https://resource-test.wpmigrated.net';

    private const SOURCE_HOST = 'resource-test.wpmigrated.net';

    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('wp_migration_jobs')) {
            $this->artisan('module:migrate', ['module' => 'WordPressMigration']);
        }
        DB::table('wp_migration_jobs')->delete();
        DB::table('wp_migration_staging_content')->delete();
        DB::table('wp_migration_staging_media')->delete();

        // Drop any residual live-content markers carrying our test host
        // so the imported-rows stat can't leak in counts from stale runs.
        $residual = DB::table('content_data')
            ->where('rel_type', 'Modules\\Content\\Models\\Content')
            ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
            ->where('field_value', 'like', '%' . self::SOURCE_HOST . '%')
            ->pluck('rel_id')
            ->all();
        if (! empty($residual)) {
            DB::table('content_data')->whereIn('rel_id', $residual)->delete();
            DB::table('content')->whereIn('id', $residual)->delete();
        }

        $this->setUpFilamentPanel();
    }

    private function seedJob(array $overrides = []): WordPressMigrationJob
    {
        return WordPressMigrationJob::create(array_merge([
            'source_url' => self::SOURCE_URL,
            'source_url_hash' => hash('sha256', self::SOURCE_URL),
            'source_host' => self::SOURCE_HOST,
            'status' => WordPressMigrationJob::STATUS_FINISHED,
            'mode' => 'rest',
            'probe_result' => [
                'mode' => 'rest',
                'estimated_posts' => 12,
                'estimated_pages' => 3,
            ],
            'progress' => ['imported' => 10, 'failed' => 2, 'total' => 12],
            'started_at' => now()->subHour(),
            'finished_at' => now(),
        ], $overrides));
    }

    #[Test]
    public function resource_model_is_wired_to_the_job_model(): void
    {
        $this->assertSame(WordPressMigrationJob::class, WordPressMigrationResource::getModel());
    }

    #[Test]
    public function resource_exposes_all_four_pages(): void
    {
        $pages = WordPressMigrationResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('view', $pages);
        $this->assertArrayHasKey('logs', $pages);
    }

    #[Test]
    public function sidebar_entry_sits_in_the_system_settings_group_with_the_import_label(): void
    {
        // Nav was deliberately regrouped Content -> 'System Settings' (alongside
        // Backup) in 20942cb724; this pins the current, intended placement.
        $this->assertSame('System Settings', WordPressMigrationResource::getNavigationGroup());
        $this->assertSame('Import from WordPress', WordPressMigrationResource::getNavigationLabel());
    }

    #[Test]
    public function standalone_import_and_preview_pages_do_not_register_their_own_nav_entries(): void
    {
        // The resource is the single sidebar entry — the stateful
        // import and preview pages stay routable but hidden.
        $this->assertFalse(\Modules\WordPressMigration\Filament\Pages\WordPressMigrationImportPage::shouldRegisterNavigation());
        $this->assertFalse(\Modules\WordPressMigration\Filament\Pages\WordPressMigrationPreviewPage::shouldRegisterNavigation());
    }

    #[Test]
    public function list_page_renders_for_authenticated_admins(): void
    {
        $this->seedJob();

        Livewire::test(ListWordPressMigrations::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords([WordPressMigrationJob::query()->first()]);
    }

    #[Test]
    public function list_page_shows_source_host_column(): void
    {
        $this->seedJob();

        Livewire::test(ListWordPressMigrations::class)
            ->assertSuccessful()
            ->assertSee(self::SOURCE_HOST);
    }

    #[Test]
    public function list_page_filters_by_status(): void
    {
        $finished = $this->seedJob();
        $running = WordPressMigrationJob::create([
            'source_url' => 'https://other.wpmigrated.net',
            'source_url_hash' => hash('sha256', 'https://other.wpmigrated.net'),
            'source_host' => 'other.wpmigrated.net',
            'status' => WordPressMigrationJob::STATUS_RUNNING,
            'mode' => 'rss',
        ]);

        Livewire::test(ListWordPressMigrations::class)
            ->filterTable('status', WordPressMigrationJob::STATUS_FINISHED)
            ->assertCanSeeTableRecords([$finished])
            ->assertCanNotSeeTableRecords([$running]);
    }

    #[Test]
    public function create_page_redirects_to_the_import_page(): void
    {
        Livewire::test(CreateWordPressMigration::class)
            ->assertRedirect('/admin/word-press-migration-import-page');
    }

    #[Test]
    public function view_page_renders_the_job_detail(): void
    {
        $job = $this->seedJob();

        Livewire::test(ViewWordPressMigration::class, ['record' => $job->id])
            ->assertSuccessful()
            ->assertSee(self::SOURCE_HOST);
    }

    #[Test]
    public function logs_page_renders_staged_and_excluded_rows_for_the_job(): void
    {
        $job = $this->seedJob();

        StagingContent::create([
            'job_id' => $job->id,
            'source_guid' => 'guid-kept',
            'canonical_url' => 'https://' . self::SOURCE_HOST . '/guid-kept',
            'title' => 'Kept post',
            'content_html' => '<p>Body</p>',
            'import_source' => 'rest',
            'source_host' => self::SOURCE_HOST,
            'excluded' => false,
        ]);
        StagingContent::create([
            'job_id' => $job->id,
            'source_guid' => 'guid-dropped',
            'canonical_url' => 'https://' . self::SOURCE_HOST . '/guid-dropped',
            'title' => 'Dropped post',
            'content_html' => '<p>Body</p>',
            'import_source' => 'rest',
            'source_host' => self::SOURCE_HOST,
            'excluded' => true,
        ]);

        Livewire::test(WordPressMigrationLogsPage::class, ['record' => $job->id])
            ->assertSuccessful()
            ->assertSee('guid-kept')
            ->assertSee('guid-dropped')
            ->assertSee('Kept post')
            ->assertSee('Dropped post');
    }

    #[Test]
    public function logs_page_stats_count_staged_and_excluded_separately(): void
    {
        $job = $this->seedJob();

        StagingContent::create([
            'job_id' => $job->id,
            'source_guid' => 'g1',
            'canonical_url' => 'https://' . self::SOURCE_HOST . '/g1',
            'title' => 'A',
            'content_html' => '<p>A</p>',
            'import_source' => 'rest',
            'source_host' => self::SOURCE_HOST,
            'excluded' => false,
        ]);
        StagingContent::create([
            'job_id' => $job->id,
            'source_guid' => 'g2',
            'canonical_url' => 'https://' . self::SOURCE_HOST . '/g2',
            'title' => 'B',
            'content_html' => '<p>B</p>',
            'import_source' => 'rest',
            'source_host' => self::SOURCE_HOST,
            'excluded' => true,
        ]);
        StagingContent::create([
            'job_id' => $job->id,
            'source_guid' => 'g3',
            'canonical_url' => 'https://' . self::SOURCE_HOST . '/g3',
            'title' => 'C',
            'content_html' => '<p>C</p>',
            'import_source' => 'rest',
            'source_host' => self::SOURCE_HOST,
            'excluded' => false,
        ]);

        $stats = Livewire::test(WordPressMigrationLogsPage::class, ['record' => $job->id])
            ->get('stats');

        $this->assertSame(2, $stats['staged']);
        $this->assertSame(1, $stats['excluded']);
    }

    #[Test]
    public function logs_page_counts_imported_rows_matching_source_host(): void
    {
        $job = $this->seedJob();

        $guid = 'https://' . self::SOURCE_HOST . '/post-1';

        $contentId = DB::table('content')->insertGetId([
            'title' => 'Imported from WordPress',
            'content_type' => 'post',
            'subtype' => 'post',
            'url' => 'imported-from-wordpress',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('content_data')->insert([
            [
                'rel_id' => $contentId,
                'rel_type' => 'Modules\\Content\\Models\\Content',
                'field_name' => WordPressContentMapper::META_SOURCE_GUID,
                'field_value' => $guid,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rel_id' => $contentId,
                'rel_type' => 'Modules\\Content\\Models\\Content',
                'field_name' => WordPressContentMapper::META_IMPORT_SOURCE,
                'field_value' => 'wordpress',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $stats = Livewire::test(WordPressMigrationLogsPage::class, ['record' => $job->id])
            ->get('stats');

        $this->assertSame(1, $stats['imported']);

        DB::table('content_data')->where('rel_id', $contentId)->delete();
        DB::table('content')->where('id', $contentId)->delete();
    }
}
