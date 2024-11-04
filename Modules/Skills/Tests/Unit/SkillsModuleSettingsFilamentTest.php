<?php

namespace Modules\Skills\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Skills\Filament\SkillsModuleSettings;
use Tests\TestCase;

class SkillsModuleSettingsFilamentTest extends TestCase
{

    public function testSkillsModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'skills';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];


        $data = [
            'skills' => [
                [
                    'skill' => 'CSS',
                    'percent' => 80,
                    'style' => 'success',
                ],
            ],
        ];

        Livewire::test(SkillsModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet($data)
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'skills',
            'option_value' => json_encode($data['skills'])
        ]);
        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
