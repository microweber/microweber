<?php

namespace Modules\ImageRollover\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Modules\ImageRollover\Microweber\ImageRolloverModule;

class ImageRolloverFrontendTest extends TestCase
{
    #[Test]
    public function test_module_has_correct_template_namespace()
    {
        $this->assertEquals(
            'modules.image_rollover::templates',
            ImageRolloverModule::$templatesNamespace
        );
    }

    #[Test]
    public function test_module_accepts_parameters()
    {
        $module = new ImageRolloverModule();
        $module->params = [
            'default_image' => 'test.jpg',
            'rollover_image' => 'hover.jpg',
            'size' => '300'
        ];
        
        $this->assertEquals('test.jpg', $module->params['default_image']);
        $this->assertEquals('hover.jpg', $module->params['rollover_image']);
        $this->assertEquals('300', $module->params['size']);
    }
}