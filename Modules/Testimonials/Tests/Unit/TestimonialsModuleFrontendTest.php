<?php

namespace Modules\Testimonials\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Testimonials\Filament\TestimonialsModuleSettings;
use Modules\Testimonials\Microweber\TestimonialsModule;
use Modules\Testimonials\Models\Testimonial;
use Tests\TestCase;

class TestimonialsModuleFrontendTest extends TestCase
{
    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-rel-id',
            'module' => 'testimonials',
        ];

        $testimonialItem = Testimonial::create([
            'name' => 'Test Testimonial Item',
            'content' => 'This is a test content for the testimonial.',
            'rel_id' => 'test-rel-id',
            'rel_type' => 'module',
        ]);

        $module = new TestimonialsModule($params);
        $viewData = $module->getViewData();

        $viewOutput = $module->render();

        $this->assertStringContainsString($testimonialItem->name, $viewOutput);

        // cleanup
        $testimonialItem->delete();
    }
}
