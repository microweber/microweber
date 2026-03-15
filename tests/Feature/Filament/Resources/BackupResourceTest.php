<?php

namespace Tests\Feature\Filament\Resources;

use Livewire\Livewire;
use Modules\Backup\Filament\Resources\BackupResource;
use Modules\Backup\Filament\Resources\BackupResource\Pages\ListBackups;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

#[RunTestsInSeparateProcesses]
class BackupResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return BackupResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListBackups::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(BackupResource::getModel());
    }
}
