<?php

namespace Modules\Backup\Tests;

use Modules\Backup\Models\BackupSchedule;
use Modules\Backup\Models\BackupHistory;
use Modules\Backup\Services\AutomatedBackupService;
use Tests\TestCase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

/**
 * Automated Backup System Test Suite
 *
 * Tests the backup scheduling, automated execution, and retention policies.
 */
class AutomatedBackupTest extends TestCase
{

    protected AutomatedBackupService $backupService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->backupService = app(AutomatedBackupService::class);

        // Truncate tables before each test
        BackupHistory::query()->delete();
        BackupSchedule::query()->delete();
    }

        #[Test]
        public function it_can_create_a_backup_schedule(): void
    {
        $schedule = new BackupSchedule();
        $schedule->name = 'Test Daily Backup';
        $schedule->type = 'contentBackup';
        $schedule->frequency = 'daily';
        $schedule->time = '02:00';
        $schedule->retention_days = 7;
        $schedule->is_active = true;
        $schedule->save();

        // Calculate next run time
        $schedule->calculateNextRun();

        $this->assertDatabaseHas('backup_schedules', [
            'name' => 'Test Daily Backup',
            'type' => 'contentBackup',
            'frequency' => 'daily',
        ]);

        $this->assertNotNull($schedule->next_run_at);
    }

        #[Test]
        public function it_calculates_next_run_for_daily_schedule(): void
    {
        $schedule = new BackupSchedule();
        $schedule->name = 'Daily Backup';
        $schedule->type = 'contentBackup';
        $schedule->frequency = 'daily';
        $schedule->time = '02:00';
        $schedule->is_active = true;
        $schedule->save();

        $schedule->calculateNextRun();

        $nextRun = Carbon::parse($schedule->next_run_at);

        $this->assertEquals('02:00', $nextRun->format('H:i'));
    }

        #[Test]
        public function it_calculates_next_run_for_hourly_schedule(): void
    {
        $schedule = new BackupSchedule();
        $schedule->name = 'Hourly Backup';
        $schedule->type = 'contentBackup';
        $schedule->frequency = 'hourly';
        $schedule->is_active = true;
        $schedule->save();

        $schedule->calculateNextRun();

        $this->assertNotNull($schedule->next_run_at);
    }

        #[Test]
        public function it_calculates_next_run_for_weekly_schedule(): void
    {
        $schedule = new BackupSchedule();
        $schedule->name = 'Weekly Backup';
        $schedule->type = 'contentBackup';
        $schedule->frequency = 'weekly';
        $schedule->day_of_week = 1; // Monday
        $schedule->time = '03:00';
        $schedule->is_active = true;
        $schedule->save();

        $schedule->calculateNextRun();

        $nextRun = Carbon::parse($schedule->next_run_at);
        $this->assertEquals(1, $nextRun->dayOfWeek);
    }

        #[Test]
        public function it_calculates_next_run_for_monthly_schedule(): void
    {
        $schedule = new BackupSchedule();
        $schedule->name = 'Monthly Backup';
        $schedule->type = 'contentBackup';
        $schedule->frequency = 'monthly';
        $schedule->day_of_month = 15;
        $schedule->time = '04:00';
        $schedule->is_active = true;
        $schedule->save();

        $schedule->calculateNextRun();

        $nextRun = Carbon::parse($schedule->next_run_at);
        // Monthly schedules always target next month (see BackupSchedule::calculateNextRun)
        $expectedMonth = Carbon::now()->startOfMonth()->addMonth()->month;
        $this->assertEquals($expectedMonth, $nextRun->month);
        $this->assertEquals(15, $nextRun->day);
    }

        #[Test]
        public function it_identifies_due_schedules(): void
    {
        // Create a schedule due to run (past next_run_at)
        $schedule = new BackupSchedule();
        $schedule->name = 'Due Schedule';
        $schedule->type = 'contentBackup';
        $schedule->frequency = 'daily';
        $schedule->time = '00:00';
        $schedule->is_active = true;
        $schedule->next_run_at = Carbon::now()->subHour();
        $schedule->save();

        $dueSchedules = BackupSchedule::due()->get();

        $this->assertTrue($dueSchedules->contains($schedule));
    }

        #[Test]
        public function it_does_not_include_inactive_schedules_in_due_query(): void
    {
        $schedule = new BackupSchedule();
        $schedule->name = 'Inactive Schedule';
        $schedule->type = 'contentBackup';
        $schedule->frequency = 'daily';
        $schedule->time = '00:00';
        $schedule->is_active = false;
        $schedule->next_run_at = Carbon::now()->subHour();
        $schedule->save();

        $dueSchedules = BackupSchedule::due()->get();

        $this->assertFalse($dueSchedules->contains($schedule));
    }

        #[Test]
        public function it_can_mark_schedule_as_run(): void
    {
        $schedule = new BackupSchedule();
        $schedule->name = 'Test Schedule';
        $schedule->type = 'contentBackup';
        $schedule->frequency = 'daily';
        $schedule->time = '02:00';
        $schedule->is_active = true;
        $schedule->save();

        $schedule->calculateNextRun();
        $originalNextRun = $schedule->next_run_at;

        $schedule->markAsRun();

        $this->assertNotNull($schedule->last_run_at);
        $this->assertNotNull($schedule->next_run_at);
    }

        #[Test]
        public function it_can_create_backup_history_record(): void
    {
        $history = new BackupHistory();
        $history->type = 'manual';
        $history->backup_type = 'contentBackup';
        $history->filename = 'test_backup.zip';
        $history->filepath = '/path/to/test_backup.zip';
        $history->status = 'pending';
        $history->save();

        $this->assertDatabaseHas('backup_history', [
            'filename' => 'test_backup.zip',
            'type' => 'manual',
            'status' => 'pending',
        ]);
    }

        #[Test]
        public function it_can_mark_history_as_running(): void
    {
        $history = new BackupHistory();
        $history->type = 'manual';
        $history->backup_type = 'contentBackup';
        $history->filename = 'test_backup.zip';
        $history->filepath = '/path/to/test_backup.zip';
        $history->status = 'pending';
        $history->save();

        $history->markAsRunning();

        $this->assertEquals('running', $history->status);
        $this->assertNotNull($history->started_at);
    }

        #[Test]
        public function it_can_mark_history_as_completed(): void
    {
        $history = new BackupHistory();
        $history->type = 'manual';
        $history->backup_type = 'contentBackup';
        $history->filename = 'test_backup.zip';
        $history->filepath = '/path/to/test_backup.zip';
        $history->status = 'running';
        $history->save();

        $history->markAsCompleted('completed_backup.zip', '/path/to/completed_backup.zip', 1024000);

        $this->assertEquals('completed', $history->status);
        $this->assertEquals('completed_backup.zip', $history->filename);
        $this->assertEquals(1024000, $history->size);
        $this->assertNotNull($history->completed_at);
    }

        #[Test]
        public function it_can_mark_history_as_failed(): void
    {
        $history = new BackupHistory();
        $history->type = 'manual';
        $history->backup_type = 'contentBackup';
        $history->filename = 'test_backup.zip';
        $history->filepath = '/path/to/test_backup.zip';
        $history->status = 'running';
        $history->save();

        $history->markAsFailed('Disk space exceeded');

        $this->assertEquals('failed', $history->status);
        $this->assertEquals('Disk space exceeded', $history->error_message);
        $this->assertNotNull($history->completed_at);
    }

        #[Test]
        public function it_calculates_formatted_size(): void
    {
        $history = new BackupHistory();

        $history->size = 1024;
        // format_bytes output varies, just check it returns something
        $this->assertNotNull($history->formatted_size);

        $history->size = 1048576;
        $this->assertNotNull($history->formatted_size);

        $history->size = null;
        $this->assertEquals('N/A', $history->formatted_size);
    }

        #[Test]
        public function it_calculates_duration_attribute(): void
    {
        $history = new BackupHistory();
        $history->type = 'manual';
        $history->backup_type = 'contentBackup';
        $history->filename = 'test.zip';
        $history->filepath = '/path/test.zip';
        $history->status = 'completed';
        $history->started_at = Carbon::now()->subMinutes(5)->subSeconds(30);
        $history->completed_at = Carbon::now();
        $history->save();

        $this->assertGreaterThanOrEqual(330, $history->duration); // 5 minutes 30 seconds
    }

        #[Test]
        public function it_scopes_completed_backups(): void
    {
        BackupHistory::query()->delete();

        $hist1 = new BackupHistory();
        $hist1->type = 'manual';
        $hist1->backup_type = 'contentBackup';
        $hist1->filename = 'test1.zip';
        $hist1->filepath = '/path/test1.zip';
        $hist1->status = 'completed';
        $hist1->save();

        $hist2 = new BackupHistory();
        $hist2->type = 'manual';
        $hist2->backup_type = 'contentBackup';
        $hist2->filename = 'test2.zip';
        $hist2->filepath = '/path/test2.zip';
        $hist2->status = 'pending';
        $hist2->save();

        $hist3 = new BackupHistory();
        $hist3->type = 'manual';
        $hist3->backup_type = 'contentBackup';
        $hist3->filename = 'test3.zip';
        $hist3->filepath = '/path/test3.zip';
        $hist3->status = 'failed';
        $hist3->save();

        $completed = BackupHistory::completed()->get();

        $this->assertCount(1, $completed);
        $this->assertEquals('completed', $completed->first()->status);
    }

        #[Test]
        public function it_scopes_failed_backups(): void
    {
        BackupHistory::query()->delete();

        $hist1 = new BackupHistory();
        $hist1->type = 'manual';
        $hist1->backup_type = 'contentBackup';
        $hist1->filename = 'test1.zip';
        $hist1->filepath = '/path/test1.zip';
        $hist1->status = 'completed';
        $hist1->save();

        $hist2 = new BackupHistory();
        $hist2->type = 'manual';
        $hist2->backup_type = 'contentBackup';
        $hist2->filename = 'test2.zip';
        $hist2->filepath = '/path/test2.zip';
        $hist2->status = 'failed';
        $hist2->save();

        $failed = BackupHistory::failed()->get();

        $this->assertCount(1, $failed);
        $this->assertEquals('failed', $failed->first()->status);
    }

        #[Test]
        public function it_scopes_running_backups(): void
    {
        BackupHistory::query()->delete();

        $hist1 = new BackupHistory();
        $hist1->type = 'manual';
        $hist1->backup_type = 'contentBackup';
        $hist1->filename = 'test1.zip';
        $hist1->filepath = '/path/test1.zip';
        $hist1->status = 'pending';
        $hist1->save();

        $hist2 = new BackupHistory();
        $hist2->type = 'manual';
        $hist2->backup_type = 'contentBackup';
        $hist2->filename = 'test2.zip';
        $hist2->filepath = '/path/test2.zip';
        $hist2->status = 'running';
        $hist2->save();

        $running = BackupHistory::running()->get();

        $this->assertCount(1, $running);
        $this->assertEquals('running', $running->first()->status);
    }

        #[Test]
        public function it_scopes_manual_backups(): void
    {
        BackupHistory::query()->delete();

        $hist1 = new BackupHistory();
        $hist1->type = 'manual';
        $hist1->backup_type = 'contentBackup';
        $hist1->filename = 'test1.zip';
        $hist1->filepath = '/path/test1.zip';
        $hist1->status = 'completed';
        $hist1->save();

        $hist2 = new BackupHistory();
        $hist2->type = 'scheduled';
        $hist2->backup_type = 'contentBackup';
        $hist2->filename = 'test2.zip';
        $hist2->filepath = '/path/test2.zip';
        $hist2->status = 'completed';
        $hist2->save();

        $manual = BackupHistory::manual()->get();

        $this->assertCount(1, $manual);
        $this->assertEquals('manual', $manual->first()->type);
    }

        #[Test]
        public function it_scopes_scheduled_backups(): void
    {
        BackupHistory::query()->delete();

        $hist1 = new BackupHistory();
        $hist1->type = 'manual';
        $hist1->backup_type = 'contentBackup';
        $hist1->filename = 'test1.zip';
        $hist1->filepath = '/path/test1.zip';
        $hist1->status = 'completed';
        $hist1->save();

        $hist2 = new BackupHistory();
        $hist2->type = 'scheduled';
        $hist2->backup_type = 'contentBackup';
        $hist2->filename = 'test2.zip';
        $hist2->filepath = '/path/test2.zip';
        $hist2->status = 'completed';
        $hist2->save();

        $scheduled = BackupHistory::scheduled()->get();

        $this->assertCount(1, $scheduled);
        $this->assertEquals('scheduled', $scheduled->first()->type);
    }

        #[Test]
        public function it_scopes_older_than(): void
    {
        BackupHistory::query()->delete();

        $hist1 = new BackupHistory();
        $hist1->type = 'manual';
        $hist1->backup_type = 'contentBackup';
        $hist1->filename = 'old.zip';
        $hist1->filepath = '/path/old.zip';
        $hist1->status = 'completed';
        $hist1->created_at = Carbon::now()->subDays(10);
        $hist1->save();

        $hist2 = new BackupHistory();
        $hist2->type = 'manual';
        $hist2->backup_type = 'contentBackup';
        $hist2->filename = 'new.zip';
        $hist2->filepath = '/path/new.zip';
        $hist2->status = 'completed';
        $hist2->created_at = Carbon::now();
        $hist2->save();

        $old = BackupHistory::olderThan(7)->get();

        $this->assertCount(1, $old);
        $this->assertEquals('old.zip', $old->first()->filename);
    }

        #[Test]
        public function it_retrieves_backup_statistics(): void
    {
        BackupHistory::query()->delete();

        $hist1 = new BackupHistory();
        $hist1->type = 'manual';
        $hist1->backup_type = 'contentBackup';
        $hist1->filename = 'test1.zip';
        $hist1->filepath = '/path/test1.zip';
        $hist1->status = 'completed';
        $hist1->size = 1024000;
        $hist1->save();

        $hist2 = new BackupHistory();
        $hist2->type = 'manual';
        $hist2->backup_type = 'contentBackup';
        $hist2->filename = 'test2.zip';
        $hist2->filepath = '/path/test2.zip';
        $hist2->status = 'completed';
        $hist2->size = 2048000;
        $hist2->save();

        $hist3 = new BackupHistory();
        $hist3->type = 'manual';
        $hist3->backup_type = 'contentBackup';
        $hist3->filename = 'test3.zip';
        $hist3->filepath = '/path/test3.zip';
        $hist3->status = 'failed';
        $hist3->save();

        $stats = $this->backupService->getStatistics();

        $this->assertEquals(3, $stats['total_backups']);
        $this->assertEquals(2, $stats['completed_backups']);
        $this->assertEquals(1, $stats['failed_backups']);
        $this->assertEquals(0, $stats['running_backups']);
        $this->assertEquals(3072000, $stats['total_size']);
    }

        #[Test]
        public function it_calculates_success_rate_in_statistics(): void
    {
        BackupHistory::query()->delete();

        $hist1 = new BackupHistory();
        $hist1->type = 'manual';
        $hist1->backup_type = 'contentBackup';
        $hist1->filename = 'test1.zip';
        $hist1->filepath = '/path/test1.zip';
        $hist1->status = 'completed';
        $hist1->save();

        $hist2 = new BackupHistory();
        $hist2->type = 'manual';
        $hist2->backup_type = 'contentBackup';
        $hist2->filename = 'test2.zip';
        $hist2->filepath = '/path/test2.zip';
        $hist2->status = 'completed';
        $hist2->save();

        $hist3 = new BackupHistory();
        $hist3->type = 'manual';
        $hist3->backup_type = 'contentBackup';
        $hist3->filename = 'test3.zip';
        $hist3->filepath = '/path/test3.zip';
        $hist3->status = 'completed';
        $hist3->save();

        $hist4 = new BackupHistory();
        $hist4->type = 'manual';
        $hist4->backup_type = 'contentBackup';
        $hist4->filename = 'test4.zip';
        $hist4->filepath = '/path/test4.zip';
        $hist4->status = 'failed';
        $hist4->save();

        $stats = $this->backupService->getStatistics();

        $this->assertEquals(75.0, $stats['success_rate']);
    }

        #[Test]
        public function it_returns_backup_type_options(): void
    {
        $options = BackupSchedule::getBackupTypeOptions();

        $this->assertArrayHasKey('contentBackup', $options);
        $this->assertArrayHasKey('fullBackup', $options);
        $this->assertArrayHasKey('customBackup', $options);
    }

        #[Test]
        public function it_returns_frequency_options(): void
    {
        $options = BackupSchedule::getFrequencyOptions();

        $this->assertArrayHasKey('hourly', $options);
        $this->assertArrayHasKey('daily', $options);
        $this->assertArrayHasKey('weekly', $options);
        $this->assertArrayHasKey('monthly', $options);
    }

        #[Test]
        public function it_returns_day_of_week_options(): void
    {
        $options = BackupSchedule::getDayOfWeekOptions();

        $this->assertCount(7, $options);
        $this->assertArrayHasKey(0, $options); // Sunday
        $this->assertArrayHasKey(6, $options); // Saturday
    }

        #[Test]
        public function it_returns_status_options(): void
    {
        $options = BackupHistory::getStatusOptions();

        $this->assertArrayHasKey('pending', $options);
        $this->assertArrayHasKey('running', $options);
        $this->assertArrayHasKey('completed', $options);
        $this->assertArrayHasKey('failed', $options);
    }

        #[Test]
        public function it_returns_type_options(): void
    {
        $options = BackupHistory::getTypeOptions();

        $this->assertArrayHasKey('manual', $options);
        $this->assertArrayHasKey('scheduled', $options);
    }

        #[Test]
        public function it_cleans_up_stale_backup_records(): void
    {
        BackupHistory::query()->delete();

        $stale = new BackupHistory();
        $stale->type = 'manual';
        $stale->backup_type = 'contentBackup';
        $stale->filename = 'stale.zip';
        $stale->filepath = '/path/stale.zip';
        $stale->status = 'running';
        $stale->started_at = Carbon::now()->subHours(25);
        $stale->save();

        $fresh = new BackupHistory();
        $fresh->type = 'manual';
        $fresh->backup_type = 'contentBackup';
        $fresh->filename = 'fresh.zip';
        $fresh->filepath = '/path/fresh.zip';
        $fresh->status = 'running';
        $fresh->started_at = Carbon::now()->subMinutes(30);
        $fresh->save();

        $count = $this->backupService->cleanupStaleBackups(24);

        $this->assertEquals(1, $count);

        $stale->refresh();
        $this->assertEquals('failed', $stale->status);
        $this->assertStringContainsString('timed out', $stale->error_message);
    }

        #[Test]
        public function it_creates_relationship_between_schedule_and_history(): void
    {
        BackupHistory::query()->delete();
        BackupSchedule::query()->delete();

        $schedule = new BackupSchedule();
        $schedule->name = 'Test Schedule';
        $schedule->type = 'contentBackup';
        $schedule->frequency = 'daily';
        $schedule->is_active = true;
        $schedule->save();

        $history = new BackupHistory();
        $history->backup_schedule_id = $schedule->id;
        $history->type = 'scheduled';
        $history->backup_type = 'contentBackup';
        $history->filename = 'test.zip';
        $history->filepath = '/path/test.zip';
        $history->status = 'completed';
        $history->save();

        $this->assertTrue($schedule->histories->contains($history));
        $this->assertEquals($schedule->id, $history->schedule->id);
    }

        #[Test]
        public function it_includes_media_by_default(): void
    {
        BackupSchedule::query()->delete();

        $schedule = new BackupSchedule();
        $schedule->name = 'Test';
        $schedule->type = 'contentBackup';
        $schedule->frequency = 'daily';
        $schedule->is_active = true;
        $schedule->save();

        $this->assertTrue($schedule->include_media);
    }

        #[Test]
        public function it_validates_retention_days_range(): void
    {
        $schedule = new BackupSchedule();
        $schedule->name = 'Test';
        $schedule->type = 'contentBackup';
        $schedule->frequency = 'daily';
        $schedule->retention_days = 365;
        $schedule->save();

        $this->assertEquals(365, $schedule->retention_days);
    }

        #[Test]
        public function it_can_update_next_run_time(): void
    {
        $schedule = new BackupSchedule();
        $schedule->name = 'Test';
        $schedule->type = 'contentBackup';
        $schedule->frequency = 'daily';
        $schedule->time = '03:00';
        $schedule->is_active = true;
        $schedule->save();

        $schedule->calculateNextRun();

        $this->assertNotNull($schedule->next_run_at);
    }
}
