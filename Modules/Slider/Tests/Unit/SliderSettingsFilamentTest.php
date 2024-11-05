<?php

namespace Modules\Slider\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Slider\Filament\SliderModuleSettings;
use Tests\TestCase;

class SliderSettingsFilamentTest extends TestCase
{

    public function testSliderModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'slider';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];

        Livewire::test(SliderModuleSettings::class)
            ->set($params);


        $data = [
            'slides' => [
                [
                    'title' => 'Slide 1',
                    'description' => 'Description for slide 1',
                    'image' => 'image-url-1',
                    'buttonText' => 'Button 1',
                    'url' => 'https://example.com/1',
                    'alignItems' => 'center',
                    'titleColor' => '#000000',
                    'descriptionColor' => '#000000',
                    'buttonColor' => '#ffffff',
                    'buttonTextColor' => '#000000',
                    'titleFontSize' => '24',
                    'descriptionFontSize' => '16',
                    'buttonFontSize' => '16',
                    'titleFontFamily' => 'Arial',
                    'descriptionFontFamily' => 'Arial',
                    'imageBackgroundColor' => '#ffffff',
                    'imageBackgroundOpacity' => '100',
                    'showButton' => '1',
                ],
            ],
        ];

        Livewire::test(SliderModuleSettings::class)
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
            'option_key' => 'slides',
            'option_value' => json_encode($data['slides'])
        ]);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
