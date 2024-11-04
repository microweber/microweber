<?php

namespace Modules\Skills\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Skills\Microweber\SkillsModule;
use Tests\TestCase;

class SkillsModuleTest extends TestCase
{

    public function testDefaultSkillsRendering()
    {
        $params = [
            'id' => 'test-skills-id' . uniqid(),
            'skills' => json_encode([
                [
                    'skill' => 'HTML',
                    'percent' => 90,
                    'style' => 'primary',
                ],
            ]),
        ];
        $moduleId = $params['id'];
        $moduleType = 'skills';

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

        $skillsModule = new SkillsModule($params);
        $viewData = $skillsModule->getViewData();

        $viewOutput = $skillsModule->render();

        $this->assertTrue(View::exists('modules.skills::templates.default'));
        $this->assertStringContainsString('HTML', $viewOutput);
        $this->assertStringContainsString('90%', $viewOutput);

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
