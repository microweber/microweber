<?php

namespace Modules\WordPressMigration\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Modules\WordPressMigration\Filament\Pages\WordPressMigrationPreviewPage;
use Modules\WordPressMigration\Models\StagingContent;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

/**
 * Livewire coverage for the Phase 8 preview page.
 *
 * The page's job is read-write on the staging tables and strictly
 * read-only on live content/media — these tests exercise each
 * interaction (toggle exclusion, bulk exclude/include, open/close
 * preview modal) and assert both the staging-side outcome and that
 * nothing crosses over to the live side.
 */
class WordPressMigrationPreviewPageTest extends TestCase
{
    use InteractsWithFilamentPanel;

    private const JOB_ID = 81;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('wp_migration_staging_content')) {
            $this->artisan('module:migrate', ['module' => 'WordPressMigration']);
        }

        DB::table('wp_migration_staging_media')->where('job_id', self::JOB_ID)->delete();
        DB::table('wp_migration_staging_content')->where('job_id', self::JOB_ID)->delete();

        $this->setUpFilamentPanel();
    }

    #[Test]
    public function the_page_renders_with_empty_state_when_no_job_present(): void
    {
        DB::table('wp_migration_staging_content')->delete();
        DB::table('wp_migration_jobs')->delete();

        Livewire::test(WordPressMigrationPreviewPage::class)
            ->assertSuccessful()
            ->assertSee('No staged migration');
    }

    #[Test]
    public function the_page_renders_staged_rows_for_a_job(): void
    {
        $this->seedStagedRows(3);

        Livewire::test(WordPressMigrationPreviewPage::class, ['job' => self::JOB_ID])
            ->assertSuccessful()
            ->assertSee('Staged item 1')
            ->assertSee('Staged item 2')
            ->assertSee('Staged item 3');
    }

    #[Test]
    public function toggle_excluded_flips_the_row_flag_in_staging_only(): void
    {
        $rows = $this->seedStagedRows(1);
        $contentBefore = DB::table('content')->count();

        Livewire::test(WordPressMigrationPreviewPage::class, ['job' => self::JOB_ID])
            ->call('toggleExcluded', $rows[0]->id);

        $this->assertTrue((bool) StagingContent::find($rows[0]->id)->excluded);
        $this->assertSame($contentBefore, DB::table('content')->count(),
            'Preview page must never write to live content'
        );
    }

    #[Test]
    public function toggle_excluded_twice_returns_to_included(): void
    {
        $rows = $this->seedStagedRows(1);

        Livewire::test(WordPressMigrationPreviewPage::class, ['job' => self::JOB_ID])
            ->call('toggleExcluded', $rows[0]->id)
            ->call('toggleExcluded', $rows[0]->id);

        $this->assertFalse((bool) StagingContent::find($rows[0]->id)->excluded);
    }

    #[Test]
    public function bulk_exclude_marks_every_row_for_the_job(): void
    {
        $this->seedStagedRows(4);

        Livewire::test(WordPressMigrationPreviewPage::class, ['job' => self::JOB_ID])
            ->call('bulkExcludeAll');

        $this->assertSame(4, StagingContent::where('job_id', self::JOB_ID)->where('excluded', true)->count());
    }

    #[Test]
    public function bulk_exclude_is_scoped_to_the_active_job(): void
    {
        $this->seedStagedRows(2);
        $otherJob = self::JOB_ID + 1;
        $this->seedStagedRows(2, $otherJob);

        Livewire::test(WordPressMigrationPreviewPage::class, ['job' => self::JOB_ID])
            ->call('bulkExcludeAll');

        $this->assertSame(2, StagingContent::where('job_id', self::JOB_ID)->where('excluded', true)->count());
        $this->assertSame(0, StagingContent::where('job_id', $otherJob)->where('excluded', true)->count(),
            'bulk exclude must not spill across jobs'
        );

        DB::table('wp_migration_staging_content')->where('job_id', $otherJob)->delete();
    }

    #[Test]
    public function bulk_include_clears_exclusion_for_every_row(): void
    {
        $this->seedStagedRows(3);
        StagingContent::where('job_id', self::JOB_ID)->update(['excluded' => true]);

        Livewire::test(WordPressMigrationPreviewPage::class, ['job' => self::JOB_ID])
            ->call('bulkIncludeAll');

        $this->assertSame(0, StagingContent::where('job_id', self::JOB_ID)->where('excluded', true)->count());
    }

    #[Test]
    public function open_preview_loads_the_target_rows_html(): void
    {
        $rows = $this->seedStagedRows(1);

        $component = Livewire::test(WordPressMigrationPreviewPage::class, ['job' => self::JOB_ID])
            ->call('openPreview', $rows[0]->id);

        $this->assertSame($rows[0]->id, $component->get('previewStagingId'));
        $component->assertSee('Staged item 1 body');
    }

    #[Test]
    public function close_preview_clears_the_modal_target(): void
    {
        $rows = $this->seedStagedRows(1);

        $component = Livewire::test(WordPressMigrationPreviewPage::class, ['job' => self::JOB_ID])
            ->call('openPreview', $rows[0]->id)
            ->call('closePreview');

        $this->assertNull($component->get('previewStagingId'));
    }

    /**
     * @return list<StagingContent>
     */
    private function seedStagedRows(int $n, ?int $jobId = null): array
    {
        $jobId ??= self::JOB_ID;
        $rows = [];
        for ($i = 1; $i <= $n; $i++) {
            $rows[] = StagingContent::create([
                'job_id' => $jobId,
                'import_source' => 'wordpress_wxr',
                'source_guid' => "preview:{$jobId}:{$i}",
                'title' => "Staged item {$i}",
                'content_html' => "<p>Staged item {$i} body</p>",
                'canonical_url' => "https://example.com/staged-{$i}",
            ]);
        }
        return $rows;
    }
}
