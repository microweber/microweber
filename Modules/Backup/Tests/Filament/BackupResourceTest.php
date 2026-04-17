<?php

namespace Modules\Backup\Tests\Filament;

use Livewire\Livewire;
use Modules\Backup\Filament\Resources\BackupResource;
use Modules\Backup\Filament\Resources\BackupResource\Pages\ListBackups;
use Modules\Backup\Filament\Resources\BackupHistoryResource;
use Modules\Backup\Filament\Resources\BackupHistoryResource\Pages\ListBackupHistory;
use Modules\Backup\Filament\Resources\BackupScheduleResource;
use Modules\Backup\Filament\Resources\BackupScheduleResource\Pages\ListBackupSchedules;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class BackupResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_can_render_backups_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListBackups::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_backup_history_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListBackupHistory::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_backup_schedules_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListBackupSchedules::class)->assertSuccessful();
    }

    #[Test]
    public function it_backup_resource_has_model(): void
    {
        $this->assertNotNull(BackupResource::getModel());
    }
}
