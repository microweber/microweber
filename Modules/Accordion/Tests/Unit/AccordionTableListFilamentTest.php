<?php

namespace Modules\Accordion\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Livewire\Livewire;
use Modules\Accordion\Filament\AccordionModuleSettings;
use Modules\Accordion\Filament\AccordionTableList;
use Modules\Accordion\Models\Accordion;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class AccordionTableListFilamentTest extends TestCase
{

    #[Test]

    public function it_accordion_module_settings_form(): void {
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
