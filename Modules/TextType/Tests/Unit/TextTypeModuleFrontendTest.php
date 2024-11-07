<?php

namespace Modules\TextType\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\TextType\Microweber\TextTypeModule;
use Tests\TestCase;

class TextTypeModuleFrontendTest extends TestCase
{
    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-text_type-id' . uniqid(),
            'text' => 'Your cool text here!',
        ];
        $moduleId = $params['id'];
        $moduleType = 'text_type';

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

        $textTypeModule = new TextTypeModule($params);
        $viewOutput = $textTypeModule->render();

        $this->assertTrue(View::exists('modules.text_type::templates.default'));
        $this->assertStringContainsString('Your cool text here!', $viewOutput);

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
