<?php

namespace Modules\Slider\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Slider\Models\Slider;
use Modules\Slider\Filament\SliderModuleSettings;
use Tests\TestCase;

class SliderSettingsFilamentTest extends TestCase
{
    use RefreshDatabase;

    public function testSliderModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();

        $params = [
            'params' => [
                'id' => $moduleId,
                'rel_id' => $moduleId,
                'rel_type' => 'module'
            ]
        ];

        Livewire::test(SliderModuleSettings::class)
            ->set($params);

        $data = [
            'slides' => [
                [
                    'name' => 'Slide 1',
                    'description' => 'Description for slide 1',
                    'media' => 'image-url-1',
                    'button_text' => 'Button 1',
                    'link' => 'https://example.com/1',
                    'settings' => [
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
                        'showButton' => '1'
                    ],
                    'position' => 0,
                    'rel_id' => $moduleId,
                    'rel_type' => 'module'
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

        // Verify the slider was created in the database
        $this->assertDatabaseHas('sliders', [
            'name' => 'Slide 1',
            'description' => 'Description for slide 1',
            'media' => 'image-url-1',
            'button_text' => 'Button 1',
            'link' => 'https://example.com/1',
            'rel_id' => $moduleId,
            'rel_type' => 'module',
            'position' => 0
        ]);

        // Clean up
        Slider::where('rel_id', $moduleId)->delete();
        $this->assertDatabaseMissing('sliders', ['rel_id' => $moduleId]);
    }
}
