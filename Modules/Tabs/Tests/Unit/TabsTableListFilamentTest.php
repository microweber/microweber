<?php

namespace Modules\Tabs\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Livewire\Livewire;
use Modules\Tabs\Filament\TabsModuleSettings;
use Modules\Tabs\Filament\TabsTableList;
use Modules\Tabs\Models\Tab;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class TabsTableListFilamentTest extends TestCase
{

    #[Test]

    public function it_tabs_module_settings_form(): void {
        $moduleId = 'tabs-module-id-test-' . uniqid();
        $moduleType = 'tabs';

        $title = 'Test Tab Title';
        $record = new Tab();
        $record->rel_id = $moduleId;
        $record->rel_type = $moduleType;
        $record->title = $title;
        $record->icon = 'heroicon-o-tabs';
        $record->content = 'This is the content of the tab.';
        $record->save();

        Livewire::test(TabsTableList::class, ['rel_id' => $moduleId, 'rel_type' => $moduleType])
            ->assertSee($title)
            ->callTableAction('edit', $record)
            ->assertSee($title)
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors();

        // cleanup
        $record->delete();
    }
}
