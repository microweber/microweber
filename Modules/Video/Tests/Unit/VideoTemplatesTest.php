<?php

namespace Modules\Video\Tests\Unit;

use Tests\TestCase;
use Modules\Video\Microweber\VideoModule;
use Illuminate\View\View;

class VideoTemplatesTest extends TestCase
{
    public function test_default_template_rendering()
    {
        $module = new VideoModule();
        $view = $module->render(['template' => 'default']);
        
        $this->assertInstanceOf(View::class, $view);
        $this->assertStringContainsString('video-player-container', $view->render());
    }

    public function test_dialog_template_rendering()
    {
        $module = new VideoModule();
        $view = $module->render(['template' => 'dialog']);
        
        $this->assertStringContainsString('video-dialog-container', $view->render());
        $this->assertStringContainsString('data-toggle="modal"', $view->render());
    }

    public function test_template_with_custom_options()
    {
        $module = new VideoModule();
        $view = $module->render([
            'template' => 'default',
            'width' => '100%',
            'height' => '500px'
        ]);
        
        $content = $view->render();
        $this->assertStringContainsString('width: 100%', $content);
        $this->assertStringContainsString('height: 500px', $content);
    }

    public function test_invalid_template_fallback()
    {
        $module = new VideoModule();
        $view = $module->render(['template' => 'invalid']);
        
        // Should fall back to default template
        $this->assertStringContainsString('video-player-container', $view->render());
    }
}