<?php

namespace Modules\Backup\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Backup\Filament\Resources\BackupResource;
use Modules\Backup\Filament\Resources\BackupResource\Pages\ListBackups;
use Modules\Backup\Models\Backup;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class BackupResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsAdmin();
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    protected function actingAsAdmin(): User
    {
        $user = User::factory()->create(['is_admin' => 1]);
        $this->actingAs($user);
        return $user;
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
        $this->assertDatabaseMissing('backups', ['id' => $backup->id]);
    }
}
