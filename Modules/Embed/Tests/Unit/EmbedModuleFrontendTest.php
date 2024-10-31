<?php

namespace Modules\Embed\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Embed\Microweber\EmbedModule;
use Tests\TestCase;

class EmbedModuleFrontendTest extends TestCase
{
    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-embed-id' . uniqid(),
            'source_code' => '<iframe src="https://www.example.com"></iframe>',
        ];
        $moduleId = $params['id'];
        $moduleType = 'embed';

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

        $embedModule = new EmbedModule($params);
        $viewData = $embedModule->getViewData();

        $viewOutput = $embedModule->render();

        $this->assertTrue(View::exists('modules.embed::templates.default'));
        $this->assertStringContainsString('<iframe src="https://www.example.com"></iframe>', $viewOutput);

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
