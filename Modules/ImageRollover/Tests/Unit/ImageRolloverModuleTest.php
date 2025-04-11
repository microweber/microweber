<?php

namespace Modules\ImageRollover\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Modules\ImageRollover\Microweber\ImageRolloverModule;

class ImageRolloverModuleTest extends TestCase
{
    #[Test]
    public function test_module_properties()
    {
        $this->assertEquals('Image Rollover', ImageRolloverModule::$name);
        $this->assertEquals('image_rollover', ImageRolloverModule::$module);
        $this->assertEquals('media', ImageRolloverModule::$categories);
        $this->assertEquals(3, ImageRolloverModule::$position);
        $this->assertEquals('modules.image_rollover-icon', ImageRolloverModule::$icon);
    }

    #[Test] 
    public function test_module_initialization()
    {
        $module = new ImageRolloverModule();
        $this->assertInstanceOf(ImageRolloverModule::class, $module);
        $this->assertEquals(
            'modules.image_rollover::templates',
            ImageRolloverModule::$templatesNamespace
        );
    }
}