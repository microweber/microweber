<?php

namespace Modules\Backup\Tests\Unit\Filament;

use Livewire\Livewire;
use Modules\Backup\Filament\Resources\BackupHistoryResource;
use Modules\Backup\Filament\Resources\BackupHistoryResource\Pages\ListBackupHistory;
use Modules\Backup\Filament\Resources\BackupHistoryResource\Pages\ViewBackupHistory;
use Modules\Backup\Models\BackupHistory;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class BackupHistoryResourceTest extends TestCase
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
        Livewire::test(ListBackupHistory::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $histories = BackupHistory::factory()->count(3)->create();
        Livewire::test(ListBackupHistory::class)->assertCanSeeTableRecords($histories);
    }

    #[Test]
    public function it_view_page_renders(): void
    {
        $history = BackupHistory::factory()->create(['status' => 'failed']);
        Livewire::test(ViewBackupHistory::class, ['record' => $history->id])->assertSuccessful();
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $history = BackupHistory::factory()->create();
        Livewire::test(ListBackupHistory::class)->callTableAction('delete', $history);
        $this->assertDatabaseMissing('backup_history', ['id' => $history->id]);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListBackupHistory::class)
            ->assertTableColumnExists('filename')
            ->assertTableColumnExists('type')
            ->assertTableColumnExists('status')
            ->assertTableColumnExists('size');
    }

    #[Test]
    public function it_download_action_exists_for_completed_backups(): void
    {
        $history = BackupHistory::factory()->create([
            'status' => 'completed',
            'filepath' => '/path/to/backup.zip',
        ]);
        Livewire::test(ListBackupHistory::class)->assertTableActionExists('download');
    }

    #[Test]
    public function it_global_search_returns_results(): void
    {
        $history = BackupHistory::factory()->create([
            'filename' => 'backup-history-global-search.zip',
            'status' => 'completed',
        ]);

        $results = BackupHistoryResource::getGlobalSearchResults('global-search');
        $this->assertNotEmpty($results);
    }
}
