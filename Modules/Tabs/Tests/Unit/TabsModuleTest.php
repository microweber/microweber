<?php

namespace Modules\Tabs\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Tabs\Filament\TabsModuleSettings;
use Modules\Tabs\Microweber\TabsModule;
use Modules\Tabs\Models\Tab;
use Tests\TestCase;

class TabsModuleTest extends TestCase
{

    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-rel-id',
            'module' => 'tabs',
        ];

        $tabItem = Tab::create([
            'title' => 'Test Tab Item',
            'content' => 'This is a test content for the tab.',
            'rel_id' => 'test-rel-id',
            'rel_type' => 'module',
        ]);

        $module = new TabsModule($params);
        $viewData = $module->getViewData();

        $viewOutput = $module->render();

        $this->assertStringContainsString($tabItem->title, $viewOutput);

        // cleanup
        $tabItem->delete();
    }
}
