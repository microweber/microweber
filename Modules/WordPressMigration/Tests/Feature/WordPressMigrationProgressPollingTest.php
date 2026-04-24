<?php

namespace Modules\WordPressMigration\Tests\Feature;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages\ViewWordPressMigration;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

/**
 * Phase 9 real-time progress polling coverage.
 *
 * The import worker writes `processed / total / failed` onto the
 * job row as it ticks; the View page polls every
 * {@see ViewWordPressMigration::POLL_INTERVAL_SECONDS}s while the
 * job is running so the operator can watch without refreshing.
 *
 * Tests here exercise:
 *   - the computed-stats property (aliases + ETA math),
 *   - polling enable/disable based on status,
 *   - the refresh method re-reading fresh DB state.
 */
class WordPressMigrationProgressPollingTest extends TestCase
{
    use InteractsWithFilamentPanel;

    private const SOURCE_URL = 'https://progress-test.example.invalid';

    private const SOURCE_HOST = 'progress-test.example.invalid';

    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('wp_migration_jobs')) {
            $this->artisan('module:migrate', ['module' => 'WordPressMigration']);
        }
        DB::table('wp_migration_jobs')->delete();
        DB::table('wp_migration_staging_content')->delete();
        DB::table('wp_migration_staging_media')->delete();

        $this->setUpFilamentPanel();
    }

    private function seedJob(array $overrides = []): WordPressMigrationJob
    {
        return WordPressMigrationJob::create(array_merge([
            'source_url' => self::SOURCE_URL,
            'source_url_hash' => hash('sha256', self::SOURCE_URL),
            'source_host' => self::SOURCE_HOST,
            'status' => WordPressMigrationJob::STATUS_RUNNING,
            'mode' => 'rest',
            'started_at' => Carbon::now()->subMinutes(2),
            'progress' => ['processed' => 10, 'total' => 100, 'failed' => 1],
        ], $overrides));
    }

    #[Test]
    public function progress_stats_read_processed_total_failed_directly(): void
    {
        $job = $this->seedJob([
            'progress' => ['processed' => 42, 'total' => 200, 'failed' => 3],
        ]);

        $stats = Livewire::test(ViewWordPressMigration::class, ['record' => $job->id])
            ->get('progressStats');

        $this->assertSame(42, $stats['processed']);
        $this->assertSame(200, $stats['total']);
        $this->assertSame(3, $stats['failed']);
    }

    #[Test]
    public function progress_stats_alias_imported_and_done_to_processed(): void
    {
        $job = $this->seedJob([
            'progress' => ['imported' => 7, 'total' => 50],
        ]);

        $stats = Livewire::test(ViewWordPressMigration::class, ['record' => $job->id])
            ->get('progressStats');
        $this->assertSame(7, $stats['processed']);

        $job->progress = ['done' => 9];
        $job->save();

        $stats2 = Livewire::test(ViewWordPressMigration::class, ['record' => $job->id])
            ->get('progressStats');
        $this->assertSame(9, $stats2['processed']);
    }

    #[Test]
    public function progress_stats_fall_back_to_probe_estimates_for_total(): void
    {
        $job = $this->seedJob([
            'progress' => ['processed' => 5],
            'probe_result' => [
                'mode' => 'rest',
                'estimated_posts' => 80,
                'estimated_pages' => 20,
            ],
        ]);

        $stats = Livewire::test(ViewWordPressMigration::class, ['record' => $job->id])
            ->get('progressStats');

        $this->assertSame(100, $stats['total']);
    }

    #[Test]
    public function progress_stats_expose_percentage_when_total_is_known(): void
    {
        $job = $this->seedJob([
            'progress' => ['processed' => 25, 'total' => 100],
        ]);

        $stats = Livewire::test(ViewWordPressMigration::class, ['record' => $job->id])
            ->get('progressStats');

        $this->assertSame(25, $stats['percentage']);
    }

    #[Test]
    public function progress_stats_percentage_is_null_without_total(): void
    {
        $job = $this->seedJob([
            'progress' => ['processed' => 5],
            'probe_result' => null,
        ]);

        $stats = Livewire::test(ViewWordPressMigration::class, ['record' => $job->id])
            ->get('progressStats');

        $this->assertNull($stats['total']);
        $this->assertNull($stats['percentage']);
    }

    #[Test]
    public function progress_stats_compute_eta_from_rate_and_elapsed_time(): void
    {
        Carbon::setTestNow('2026-04-24 12:00:00');
        $job = $this->seedJob([
            'status' => WordPressMigrationJob::STATUS_RUNNING,
            'started_at' => '2026-04-24 11:59:00',
            'progress' => ['processed' => 10, 'total' => 50],
        ]);

        $stats = Livewire::test(ViewWordPressMigration::class, ['record' => $job->id])
            ->get('progressStats');
        Carbon::setTestNow();

        $this->assertSame(60, $stats['elapsed_seconds']);
        $this->assertSame(240, $stats['eta_seconds']);
        $this->assertSame('4m 0s', $stats['eta_human']);
    }

    #[Test]
    public function progress_stats_eta_is_zero_when_processed_equals_total(): void
    {
        Carbon::setTestNow('2026-04-24 12:00:00');
        $job = $this->seedJob([
            'status' => WordPressMigrationJob::STATUS_RUNNING,
            'started_at' => '2026-04-24 11:59:00',
            'progress' => ['processed' => 50, 'total' => 50],
        ]);

        $stats = Livewire::test(ViewWordPressMigration::class, ['record' => $job->id])
            ->get('progressStats');
        Carbon::setTestNow();

        $this->assertSame(0, $stats['eta_seconds']);
    }

    #[Test]
    public function progress_stats_eta_is_null_when_insufficient_data(): void
    {
        $job = $this->seedJob([
            'started_at' => null,
            'progress' => ['processed' => 0, 'total' => 100],
        ]);

        $stats = Livewire::test(ViewWordPressMigration::class, ['record' => $job->id])
            ->get('progressStats');

        $this->assertNull($stats['eta_seconds']);
    }

    #[Test]
    public function should_poll_is_true_for_running_and_probing(): void
    {
        foreach ([WordPressMigrationJob::STATUS_RUNNING, WordPressMigrationJob::STATUS_PROBING] as $status) {
            DB::table('wp_migration_jobs')->delete();
            $job = $this->seedJob(['status' => $status]);
            $stats = Livewire::test(ViewWordPressMigration::class, ['record' => $job->id])
                ->get('progressStats');
            $this->assertTrue($stats['should_poll'], "should_poll should be true for status {$status}");
        }
    }

    #[Test]
    public function should_poll_is_false_for_terminal_statuses(): void
    {
        foreach ([
            WordPressMigrationJob::STATUS_FINISHED,
            WordPressMigrationJob::STATUS_FAILED,
            WordPressMigrationJob::STATUS_CANCELED,
            WordPressMigrationJob::STATUS_UNREACHABLE,
            WordPressMigrationJob::STATUS_READY,
        ] as $status) {
            DB::table('wp_migration_jobs')->delete();
            $job = $this->seedJob(['status' => $status]);
            $stats = Livewire::test(ViewWordPressMigration::class, ['record' => $job->id])
                ->get('progressStats');
            $this->assertFalse($stats['should_poll'], "should_poll should be false for status {$status}");
        }
    }

    #[Test]
    public function refresh_progress_picks_up_the_workers_latest_tick(): void
    {
        $job = $this->seedJob([
            'progress' => ['processed' => 3, 'total' => 100],
        ]);

        $component = Livewire::test(ViewWordPressMigration::class, ['record' => $job->id]);

        $this->assertSame(3, $component->get('progressStats')['processed']);

        // Simulate the worker writing a later tick.
        DB::table('wp_migration_jobs')
            ->where('id', $job->id)
            ->update(['progress' => json_encode(['processed' => 17, 'total' => 100, 'failed' => 1])]);

        $component->call('refreshProgress');

        $stats = $component->get('progressStats');
        $this->assertSame(17, $stats['processed']);
        $this->assertSame(1, $stats['failed']);
    }

    #[Test]
    public function view_page_renders_the_progress_panel_with_counters(): void
    {
        $job = $this->seedJob([
            'progress' => ['processed' => 12, 'total' => 80, 'failed' => 0],
        ]);

        Livewire::test(ViewWordPressMigration::class, ['record' => $job->id])
            ->assertSuccessful()
            ->assertSee('Live progress')
            ->assertSee('Processed')
            ->assertSee('Total')
            ->assertSee('Failed')
            ->assertSee('ETA')
            ->assertSee('12')
            ->assertSee('80');
    }
}
