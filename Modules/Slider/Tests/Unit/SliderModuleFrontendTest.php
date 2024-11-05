<?php

namespace Modules\Slider\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Slider\Microweber\SliderModule;
use Tests\TestCase;

class SliderModuleFrontendTest extends TestCase
{

    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-slider-id' . uniqid(),
            'slides' => json_encode([
                [
                    'title' => 'Test Slide',
                    'description' => 'This is a test slide description.',
                    'image' => 'https://example.com/image.jpg',
                    'buttonText' => 'Learn More',
                    'url' => 'https://example.com',
                    'alignItems' => 'center',
                    'showButton' => '1',
                ],
            ]),
        ];
        $moduleId = $params['id'];
        $moduleType = 'slider';

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

        $sliderModule = new SliderModule($params);
        $viewOutput = $sliderModule->render();

        $this->assertTrue(View::exists('modules.slider::templates.default'));
        $this->assertStringContainsString('Test Slide', $viewOutput);
        $this->assertStringContainsString('This is a test slide description.', $viewOutput);
        $this->assertStringContainsString('Learn More', $viewOutput);
        $this->assertStringContainsString('https://example.com', $viewOutput);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
