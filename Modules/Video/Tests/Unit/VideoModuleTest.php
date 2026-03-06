<?php

namespace Modules\Video\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Modules\Video\Microweber\VideoModule;
use Modules\Video\Filament\VideoModuleSettings;

class VideoModuleTest extends TestCase
{
    #[Test]
    public function it_module_initialization(): void {
        $module = new VideoModule();

        $this->assertEquals('Video', VideoModule::$name);
        $this->assertEquals('video', VideoModule::$module);
        $this->assertEquals(VideoModuleSettings::class, VideoModule::$settingsComponent);
    }


}
