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


}
