<?php

namespace Modules\BeforeAfter\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\BeforeAfter\Microweber\BeforeAfterModule;
use Tests\TestCase;

class BeforeAfterModuleFrontendTest extends TestCase
{

    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-beforeafter-id' . uniqid(),
            'before' => 'https://www.example.com/before.jpg',
            'after' => 'https://www.example.com/after.jpg',
        ];
        $moduleId = $params['id'];
        $moduleType = 'beforeafter';

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

        $beforeAfterModule = new BeforeAfterModule($params);
        $viewData = $beforeAfterModule->getViewData();

        $viewOutput = $beforeAfterModule->render();

        $this->assertTrue(View::exists('modules.beforeafter::templates.default'));
        $this->assertStringContainsString('before.jpg', $viewOutput);
        $this->assertStringContainsString('after.jpg', $viewOutput);

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
