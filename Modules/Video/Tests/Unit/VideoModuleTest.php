<?php

namespace Modules\Video\Tests\Unit;

use Tests\TestCase;
use Modules\Video\Microweber\VideoModule;
use Modules\Video\Filament\VideoModuleSettings;

class VideoModuleTest extends TestCase
{
    public function test_module_initialization()
    {
        $module = new VideoModule();
        
        $this->assertEquals('Video', VideoModule::$name);
        $this->assertEquals('video', VideoModule::$module);
        $this->assertEquals(VideoModuleSettings::class, VideoModule::$settingsComponent);
    }

    public function test_module_rendering()
    {
        $module = new VideoModule();
        $view = $module->render();
        
        $this->assertStringContainsString('video-module-container', $view->render());
    }

    public function test_module_default_options()
    {
        $module = new VideoModule();
        $options = $module->getViewData()['options'];
        
        $this->assertArrayHasKey('url', $options);
        $this->assertArrayHasKey('autoplay', $options);
        $this->assertFalse($options['autoplay']);
    }
}