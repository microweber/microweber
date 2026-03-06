<?php

namespace Modules\ImageRollover\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Modules\ImageRollover\Filament\ImageRolloverModuleSettings;
use Filament\Forms\Form;

class ImageRolloverModuleSettingsFilamentTest extends TestCase
{
    #[Test]
    public function it_settings_component_exists(): void {
        $settings = new ImageRolloverModuleSettings();
        $this->assertInstanceOf(ImageRolloverModuleSettings::class, $settings);
    }

    #[Test]
    public function it_settings_has_correct_module_identifier(): void {
        $settings = new ImageRolloverModuleSettings();
        $this->assertEquals('image_rollover', $settings->module);
    }
}