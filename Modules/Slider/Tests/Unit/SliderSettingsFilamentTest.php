<?php

namespace Modules\Slider\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Slider\Models\Slider;
use Modules\Slider\Filament\SliderTableList;
use Tests\TestCase;

class SliderSettingsFilamentTest extends TestCase
{


    public function testSliderModuleSettingsForm()
    {
        Slider::truncate();

        $moduleId = 'module-id-test-' . uniqid();

        $data = [
            'rel_id' => $moduleId,
            'rel_type' => 'module',
            'name' => 'Slide 1',
            'description' => 'Description for slide 1',
            'media' => 'image-url-1',
            'button_text' => 'Button 1',
            'link' => 'https://example.com/1',
            'settings' => [
                'alignItems' => 'center',
                'titleColor' => '#000000',
                'descriptionColor' => '#000000',
                'buttonBackgroundColor' => '#ffffff',
                'buttonTextColor' => '#000000',
                'titleFontSize' => '24',
                'descriptionFontSize' => '16',
                'buttonFontSize' => '16',
                'imageBackgroundColor' => '#ffffff',
                'imageBackgroundOpacity' => '1',
                'showButton' => '1'
            ],
            'position' => 0
        ];

        Livewire::test(SliderTableList::class, [
            'rel_id' => $moduleId,
            'rel_type' => 'module',
            'module_id' => $moduleId
        ])
        ->mountTableAction('create')
        ->setTableActionData($data)
        ->callTableAction('create')
        ->assertHasNoTableActionErrors();

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
