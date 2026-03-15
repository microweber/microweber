<?php

namespace Modules\Btn\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use MicroweberPackages\Option\Models\Option;
use Modules\Btn\Filament\BtnModuleSettings;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class BtnModuleSettingsFilamentTest extends TestCase
{


    #[Test]


    public function it_btn_module_settings_form(): void {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'btn';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'module' => $moduleType,
            'params' => [
                'id' => $moduleId,
            ]
        ];

        Livewire::test(BtnModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.text')
            ->assertFormFieldExists('options.url')
            ->assertFormFieldExists('options.align')
            ->assertFormFieldExists('options.urlBlank');

        $data = [
            'options.text' => 'Click Me',
            'options.url' => 'https://www.example.com',
            'options.align' => 'center',
            'options.urlBlank' => true,
        ];

        Livewire::test(BtnModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet([
                'options.text' => 'Click Me',
                'options.url' => 'https://www.example.com',
                'options.align' => 'center',
                'options.urlBlank' => true,
            ])
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'text', 'option_value' => 'Click Me']);
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'url', 'option_value' => 'https://www.example.com']);
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'align', 'option_value' => 'center']);
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'urlBlank', 'option_value' => '1']);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
