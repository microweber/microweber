<?php

namespace Modules\ImageRollover\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Modules\ImageRollover\Microweber\ImageRolloverModule;

class ImageRolloverModuleTest extends TestCase
{
    #[Test]
    public function it_module_properties(): void {
        $this->assertEquals('Image Rollover', ImageRolloverModule::$name);
        $this->assertEquals('image_rollover', ImageRolloverModule::$module);
        $this->assertEquals('media', ImageRolloverModule::$categories);
        $this->assertEquals('modules.image_rollover-icon', ImageRolloverModule::$icon);
    }

    #[Test]
    public function it_module_initialization(): void {
        $module = new ImageRolloverModule();
        $this->assertInstanceOf(ImageRolloverModule::class, $module);
        $this->assertEquals(
            'modules.image_rollover::templates',
            ImageRolloverModule::$templatesNamespace
        );
    }
}
