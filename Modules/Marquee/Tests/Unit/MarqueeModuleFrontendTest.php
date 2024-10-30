<?php

namespace Modules\Marquee\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Marquee\Microweber\MarqueeModule;
use Tests\TestCase;

class MarqueeModuleFrontendTest extends TestCase
{
    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-marquee-id' . uniqid(),
            'text' => 'Your cool text here!',
            'fontSize' => '46',
            'animationSpeed' => 'normal',
            'textWeight' => 'normal',
            'textStyle' => 'normal',
            'textColor' => '#000000',
        ];
        $moduleId = $params['id'];
        $moduleType = 'marquee';

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

        $marqueeModule = new MarqueeModule($params);
        $viewData = $marqueeModule->getViewData();

        $viewOutput = $marqueeModule->render();

        $this->assertTrue(View::exists('modules.marquee::templates.default'));
        $this->assertStringContainsString('Your cool text here!', $viewOutput);

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
