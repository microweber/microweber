<?php

namespace Modules\Slider\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Modules\Slider\Models\Slider;
use Modules\Slider\Microweber\SliderModule;
use Tests\TestCase;

class SliderModuleFrontendTest extends TestCase
{

    public function testDefaultViewRendering()
    {
        $moduleId = 'test-slider-id-' . uniqid();

        // Create a slider in the database
        $slider = Slider::create([
            'name' => 'Test Slide',
            'description' => 'This is a test slide description.',
            'media' => 'https://example.com/image.jpg',
            'button_text' => 'Learn More',
            'link' => 'https://example.com',
            'settings' => [
                'alignItems' => 'center',
                'showButton' => '1',
                'titleColor' => '#000000',
                'descriptionColor' => '#666666',
                'buttonBackgroundColor' => '#007bff',
                'buttonTextColor' => '#ffffff'
            ],
            'rel_id' => $moduleId,
            'rel_type' => 'module',
            'position' => 0
        ]);

        $params = [
            'id' => $moduleId,
            'rel_id' => $moduleId,
            'rel_type' => 'module'
        ];

        $sliderModule = new SliderModule($params);
        $viewOutput = $sliderModule->render();

        $this->assertTrue(View::exists('modules.slider::templates.default'));
        $this->assertStringContainsString('Test Slide', $viewOutput);
        $this->assertStringContainsString('This is a test slide description.', $viewOutput);
        $this->assertStringContainsString('Learn More', $viewOutput);
        $this->assertStringContainsString('https://example.com', $viewOutput);

        // Clean up
        $slider->delete();
        $this->assertDatabaseMissing('sliders', ['id' => $slider->id]);
    }
}
