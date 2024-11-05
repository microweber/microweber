<?php

namespace Modules\Logo\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Logo\Microweber\LogoModule;
use Tests\TestCase;

class LogoModuleFrontendTest extends TestCase
{

    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-logo-id' . uniqid(),
            'logoimage' => 'https://www.example.com/logo.png',
            'text' => 'My Logo',
            'size' => 200,
        ];
        $moduleId = $params['id'];
        $moduleType = 'logo';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        // Save options to the database
        foreach ($params as $key => $value) {
            ModuleOption::create([
                'option_group' => $params['id'],
                'module' => $moduleType,
                'option_key' => $key,
                'option_value' => $value,
            ]);
        }
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $logoModule = new LogoModule($params);
        $viewData = $logoModule->getViewData();

        $viewOutput = $logoModule->render();

        $this->assertTrue(View::exists('modules.logo::templates.default'));
        $this->assertStringContainsString('My Logo', $viewOutput);
        $this->assertStringContainsString('https://www.example.com/logo.png', $viewOutput);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
