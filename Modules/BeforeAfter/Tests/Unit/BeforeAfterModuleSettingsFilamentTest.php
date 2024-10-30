<?php

namespace Modules\BeforeAfter\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\BeforeAfter\Filament\BeforeAfterModuleSettings;
use Tests\TestCase;

class BeforeAfterModuleSettingsFilamentTest extends TestCase
{
    public function testBeforeAfterModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'beforeafter';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];

        Livewire::test(BeforeAfterModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.before')
            ->assertFormFieldExists('options.after');

        $data = [
            'options.before' => 'https://www.example.com/before.jpg',
            'options.after' => 'https://www.example.com/after.jpg',
        ];

        Livewire::test(BeforeAfterModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet([
                'options.before' => 'https://www.example.com/before.jpg',
                'options.after' => 'https://www.example.com/after.jpg',
            ])
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'before', 'option_value' => 'https://www.example.com/before.jpg']);
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'after', 'option_value' => 'https://www.example.com/after.jpg']);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
