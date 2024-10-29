<?php

namespace Modules\Audio\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Audio\Microweber\AudioModule;
use Tests\TestCase;

class AudioModuleFrontendTest extends TestCase
{
    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-audio-id' . uniqid(),
            'data-audio-source' => 'url',
            'data-audio-url' => 'https://www.example.com/audio.mp3',
        ];
        $moduleId = $params['id'];
        $moduleType = 'audio';

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

        $audioModule = new AudioModule($params);
        $viewData = $audioModule->getViewData();

        $viewOutput = $audioModule->render();

        $this->assertTrue(View::exists('modules.audio::templates.default'));
        $this->assertStringContainsString('audio.mp3', $viewOutput);
        $this->assertStringContainsString('audio', $viewOutput);

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
