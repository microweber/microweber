<?php

namespace Modules\Tabs\Tests\Unit;

use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Tabs\Filament\TabsModuleSettings;
use Modules\Tabs\Filament\TabsTableList;
use Modules\Tabs\Models\Tab;
use Tests\TestCase;

class TabsTableListFilamentTest extends TestCase
{

    public function testTabsModuleSettingsForm()
    {
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
