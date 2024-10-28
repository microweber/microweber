<?php

namespace Modules\Btn\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\Option;
use Modules\Btn\Filament\BtnModuleSettings;
use Tests\TestCase;

class BtnModuleSettingsFilamentTest extends TestCase
{


    public function testBtnModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'btn';

        Option::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
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
        Option::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
