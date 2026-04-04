<?php

namespace Modules\Backup\Tests\Unit\Filament;

use Livewire\Livewire;
use Modules\Backup\Filament\Resources\BackupScheduleResource;
use Modules\Backup\Filament\Resources\BackupScheduleResource\Pages\ListBackupSchedules;
use Modules\Backup\Filament\Resources\BackupScheduleResource\Pages\CreateBackupSchedule;
use Modules\Backup\Filament\Resources\BackupScheduleResource\Pages\EditBackupSchedule;
use Modules\Backup\Models\BackupSchedule;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class BackupScheduleResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListBackupSchedules::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $schedules = BackupSchedule::factory()->count(3)->create();
        Livewire::test(ListBackupSchedules::class)->assertCanSeeTableRecords($schedules);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        Livewire::test(CreateBackupSchedule::class)
            ->fillForm([
                'name' => 'Test Schedule Global Search',
                'type' => 'contentBackup',
                'frequency' => 'daily',
                'time' => '02:00',
                'retention_days' => 7,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('backup_schedules', ['name' => 'Test Schedule Global Search']);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $schedule = BackupSchedule::factory()->create(['name' => 'Original Name']);
        
        Livewire::test(EditBackupSchedule::class, ['record' => $schedule->id])
            ->fillForm(['name' => 'Updated Name'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('backup_schedules', ['id' => $schedule->id, 'name' => 'Updated Name']);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $schedule = BackupSchedule::factory()->create();
        Livewire::test(ListBackupSchedules::class)->callTableAction('delete', $schedule);
        $this->assertDatabaseMissing('backup_schedules', ['id' => $schedule->id]);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListBackupSchedules::class)
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('type')
            ->assertTableColumnExists('frequency')
            ->assertTableColumnExists('is_active');
    }

    #[Test]
    public function it_global_search_returns_results(): void
    {
        $schedule = BackupSchedule::factory()->create([
            'name' => 'Daily Backup Schedule',
        ]);

        $results = BackupScheduleResource::getGlobalSearchResults('Daily Backup');
        $this->assertNotEmpty($results);
    }
}
