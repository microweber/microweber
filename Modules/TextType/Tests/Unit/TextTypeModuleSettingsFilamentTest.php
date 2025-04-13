<?php

namespace Modules\TextType\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\TextType\Filament\TextTypeModuleSettings;
use Tests\TestCase;

class TextTypeModuleSettingsFilamentTest extends TestCase
{


    public function testTextTypeModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'text_type';

        // Clean any existing test data
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];

        // Test form fields exist
        Livewire::test(TextTypeModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.text')
            ->assertFormFieldExists('options.fontSize')
            ->assertFormFieldExists('options.animationSpeed');

        // Test form submission
        $testData = [
            'options.text' => 'Test Text Content',
            'options.fontSize' => 32,
            'options.animationSpeed' => 150,
        ];

        Livewire::test(TextTypeModuleSettings::class)
            ->set($params)
            ->fillForm($testData)
            ->assertFormSet($testData)
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        // Verify database entries
        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'text',
            'option_value' => 'Test Text Content'
        ]);
        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'fontSize',
            'option_value' => '32'
        ]);
        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'animationSpeed',
            'option_value' => '150'
        ]);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
