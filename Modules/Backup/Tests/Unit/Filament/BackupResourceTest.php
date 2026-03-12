<?php

namespace Modules\Backup\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Backup\Filament\Resources\BackupResource;
use Modules\Backup\Filament\Resources\BackupResource\Pages\ListBackups;
use Modules\Backup\Models\Backup;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class BackupResourceTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();

        // Clean up backup files and Sushi cache to ensure test isolation
        // (GenerateBackupTest may have created backup files that pollute the Sushi table)
        $sushiCachePath = storage_path('framework/cache/sushi-modules-backup-models-backup.sqlite');
        if (file_exists($sushiCachePath)) {
            unlink($sushiCachePath);
        }

        // Clear actual backup files from disk
        $backupLocation = backup_location();
        if (is_dir($backupLocation)) {
            foreach (glob($backupLocation . '*.{sql,zip,json,xml,xlsx,csv,xls}', GLOB_BRACE) as $file) {
                @unlink($file);
            }
        }

        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListBackups::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $backups = Backup::factory()->count(3)->create();
        Livewire::test(ListBackups::class)->assertCanSeeTableRecords($backups);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListBackups::class)
            ->assertTableColumnExists('filename')
            ->assertTableColumnExists('date')
            ->assertTableColumnExists('size');
    }

    #[Test]
    public function it_restore_action_exists(): void
    {
        $backup = Backup::factory()->create();
        Livewire::test(ListBackups::class)->assertTableActionExists('restore');
    }

    #[Test]
    public function it_download_action_exists(): void
    {
        $backup = Backup::factory()->create();
        Livewire::test(ListBackups::class)->assertTableActionExists('download');
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $backup = Backup::factory()->create();
        Livewire::test(ListBackups::class)->callTableAction('delete', $backup);
        $this->assertNull(Backup::find($backup->id));
    }
}
