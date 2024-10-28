<?php

namespace Modules\Btn\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use MicroweberPackages\Option\Models\Option;
use Modules\Btn\Microweber\BtnModule;
use Tests\TestCase;

class BtnModuleFrontendTest extends TestCase
{


    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-btn-id' . uniqid(),
            'style' => 'btn-primary',
            'size' => 'btn-lg',
            'text' => 'Test Button',
            'url' => 'https://example.com',
            'blank' => true,
            'action' => 'default',
        ];
        $moduleId = $params['id'];
        $moduleType = 'btn';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
        // Save options to the database
        foreach ($params as $key => $value) {

            ModuleOption::create([
                'option_group' => $params['id'],
                'module' => 'btn',
                'option_key' => $key,
                'option_value' => $value,
            ]);
        }
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType]);
        $btnModule = new BtnModule('btn', $params);
        $viewData = $btnModule->getViewData();

        $viewOutput = $btnModule->render();

        $this->assertTrue(View::exists('modules.btn::templates.default'));
        $this->assertStringContainsString('Test Button', $viewOutput);
        $this->assertStringContainsString('https://example.com', $viewOutput);
        $this->assertStringContainsString('btn-primary', $viewOutput);
        $this->assertStringContainsString('btn-lg', $viewOutput);

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

    }

    public function testPopupViewRendering()
    {

        $params = [
            'id' => 'test-btn-id',
            'style' => 'btn-secondary',
            'size' => 'btn-sm',
            'text' => 'Popup Button',
            'popupContent' => '<p>This is a popup content</p>',
            'action' => 'popup',
        ];
        $moduleId = $params['id'];
        $moduleType = 'btn';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
        // Save options to the database
        foreach ($params as $key => $value) {
            ModuleOption::create([
                'option_group' => $params['id'],
                'module' => 'btn',
                'option_key' => $key,
                'option_value' => $value,
            ]);
        }

        $btnModule = new BtnModule('btn', $params);
        $viewData = $btnModule->getViewData();

        $view = $btnModule->render();

        $this->assertTrue(View::exists('modules.btn::templates.default'));
        $this->assertStringContainsString('Popup Button', $view->render());
        $this->assertStringContainsString('btn-secondary', $view->render());
        $this->assertStringContainsString('btn-sm', $view->render());
        $this->assertStringContainsString('This is a popup content', $view->render());


        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

    }
}
