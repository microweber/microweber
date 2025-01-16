<?php

namespace Modules\Marquee\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Marquee\Filament\MarqueeModuleSettings;
use Tests\TestCase;

class MarqueeModuleSettingsFilamentTest extends TestCase
{
    public function testMarqueeModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'marquee';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];

        Livewire::test(MarqueeModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.text')
            ->assertFormFieldExists('options.fontSize')
            ->assertFormFieldExists('options.animationSpeed')
            ->assertFormFieldExists('options.textWeight')
            ->assertFormFieldExists('options.textStyle')
            ->assertFormFieldExists('options.textColor');

        $data = [
            'options.text' => 'Updated marquee text!',
            'options.fontSize' => '50',
            'options.animationSpeed' => '10',
            'options.textWeight' => 'bold',
            'options.textStyle' => 'italic',
            'options.textColor' => '#FF0000',
        ];

        Livewire::test(MarqueeModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet($data)
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        foreach ($data as $key => $value) {
            $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => str_replace('options.', '', $key), 'option_value' => $value]);
        }

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
