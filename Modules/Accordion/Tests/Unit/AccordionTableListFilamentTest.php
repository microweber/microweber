<?php

namespace Modules\Accordion\Tests\Unit;

use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Accordion\Filament\AccordionModuleSettings;
use Modules\Accordion\Filament\AccordionTableList;
use Modules\Accordion\Models\Accordion;
use Tests\TestCase;

class AccordionTableListFilamentTest extends TestCase
{

    public function testAccordionModuleSettingsForm()
    {
        $moduleId = 'accordion-module-id-test-' . uniqid();
        $moduleType = 'accordion';

        $title = 'Test Accordion Title';
        $record = new Accordion();
        $record->rel_id = $moduleId;
        $record->rel_type = $moduleType;
        $record->title =$title;
        $record->icon = 'heroicon-o-accordion';
        $record->content = 'This is the content of the accordion.';
        $record->save();


        Livewire::test(AccordionTableList::class,['rel_id' => $moduleId, 'rel_type' => $moduleType])
            ->assertSee($title)
            ->callTableAction('edit', $record)
            ->assertSee($title)
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors();


        // cleanup
        $record->delete();


    }
}
