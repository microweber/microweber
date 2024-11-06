<?php

namespace Modules\Accordion\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Accordion\Filament\AccordionModuleSettings;
use Modules\Accordion\Microweber\AccordionModule;
use Modules\Accordion\Models\Accordion;
use Tests\TestCase;

class AccordionModuleFrontendTest extends TestCase
{

    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-rel-id',
            'module' => 'accordion',

        ];

        $accordionItem = Accordion::create([
            'title' => 'Test Accordion Item',
            'content' => 'This is a test content for the accordion.',
            'rel_id' => 'test-rel-id',
            'rel_type' => 'module',
        ]);

        $module = new AccordionModule( $params);
        $viewData = $module->getViewData();

        $viewOutput = $module->render();

        $this->assertStringContainsString($accordionItem->title, $viewOutput);

        // cleanup
        $accordionItem->delete();

    }


}
