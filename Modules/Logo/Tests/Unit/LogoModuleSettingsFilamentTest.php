<?php

namespace Modules\Logo\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Logo\Filament\LogoModuleSettings;
use Tests\TestCase;

class LogoModuleSettingsFilamentTest extends TestCase
{

    public function testLogoModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'logo';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];

        Livewire::test(LogoModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.logoimage')
            ->assertFormFieldExists('options.size')
            ->assertFormFieldExists('options.text')
            ->assertFormFieldExists('options.text_color')
            ->assertFormFieldExists('options.font_size');

        $data = [
            'options.logoimage' => 'https://www.example.com/logo.png',
            'options.size' => 200,
            'options.text' => 'My Logo',
            'options.text_color' => '#000000',
            'options.font_size' => 30,
        ];

        Livewire::test(LogoModuleSettings::class)
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
            'option_key' => 'logoimage',
            'option_value' => 'https://www.example.com/logo.png'
        ]);
        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'size',
            'option_value' => '200'
        ]);
        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'text',
            'option_value' => 'My Logo'
        ]);
        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'text_color',
            'option_value' => '#000000'
        ]);
        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'font_size',
            'option_value' => '30'
        ]);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
