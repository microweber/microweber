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
        $module->setParams([
            'id' => 'video12345',
            'template' => 'default'

        ]);
        $view = $module->render();

        $this->assertInstanceOf(View::class, $view);
        $this->assertStringContainsString('video-player-container', $view->render());
    }

    public function test_dialog_template_rendering()
    {
        $module = new VideoModule();
        $module->setParams([
            'id' => 'video12345',
            'template' => 'dialog'
        ]);
        $view = $module->render();

        $this->assertInstanceOf(View::class, $view);
        $this->assertStringContainsString('video-dialog-container', $view->render());
    }

    public function test_template_with_custom_options()
    {
        $module = new VideoModule();

        $module->setParams([
            'id' => 'video12345',
            'width' => '100%',
            'height' => '500px',
            'template' => 'default'
        ]);
        $view = $module->render();

        $this->assertInstanceOf(View::class, $view);
        $content = $view->render();

        $this->assertStringContainsString('width:100%', $content);
        $this->assertStringContainsString('height:500px', $content);
    }

    public function test_invalid_template_fallback()
    {
        $module = new VideoModule();
        $module->setParams([
            'id' => 'video12345',
            'width' => '100%',
            'height' => '500px',
            'template' => 'test_invalid_template_fallback'
        ]);
        $view = $module->render();

        $this->assertInstanceOf(View::class, $view);
        $content = $view->render();
        // Should fall back to default template
        $this->assertStringContainsString('video-player-container', $view->render());
    }
}
