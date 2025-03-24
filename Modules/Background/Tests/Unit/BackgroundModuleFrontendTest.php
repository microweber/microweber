<?php

namespace Modules\Background\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Background\Microweber\BackgroundModule;
use Tests\TestCase;

class BackgroundModuleFrontendTest extends TestCase
{

    public function testDefaultViewRendering()
    {
        $params = array(
            'id' => 'test-background-id-testDefaultViewRendering' . uniqid(),
            'data-background-image' => 'https://example.com/background.jpg',
            'data-background-video' => 'https://example.com/video.mp4',
            'data-background-color' => '#ffffff',
        );
        $moduleId = $params['id'];
        $moduleType = 'background';

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

        $backgroundModule = new BackgroundModule($params);
        $viewData = $backgroundModule->getViewData();

        $viewOutput = $backgroundModule->render();

        $this->assertTrue(View::exists('modules.background::templates.default'));
        $this->assertStringContainsString('background.jpg', $viewOutput);
        $this->assertStringContainsString('video.mp4', $viewOutput);
        $this->assertStringContainsString('#ffffff', $viewOutput);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }

    public function testDefaultViewRenderingInBlock()
    {
        $params = array(
            'id' => 'test-background-id-testDefaultViewRenderingInBlock' . uniqid(),
            'data-background-image' => 'https://example.com/background.jpg',
            'data-background-video' => 'https://example.com/video.mp4',
            'data-background-color' => '#ffffff',
        );
        $moduleId = $params['id'];
        $moduleType = 'background';

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

        $backgroundModule = new BackgroundModule($params);
        $viewData = $backgroundModule->getViewData();

        $viewOutput = $backgroundModule->render();

        $this->assertTrue(View::exists('modules.background::templates.in-block'));
        $this->assertStringContainsString('background.jpg', $viewOutput);
        $this->assertStringContainsString('video.mp4', $viewOutput);
        $this->assertStringContainsString('#ffffff', $viewOutput);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
