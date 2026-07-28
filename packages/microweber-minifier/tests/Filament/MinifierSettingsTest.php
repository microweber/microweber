<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Tests\Filament;

use MicroweberPackages\Minifier\Filament\MinifierPlugin;
use MicroweberPackages\Minifier\Filament\Pages\MinifierSettings;
use MicroweberPackages\Minifier\Tests\TestCase;

class MinifierSettingsTest extends TestCase
{
    public function test_plugin_id(): void
    {
        $plugin = MinifierPlugin::make();
        $this->assertSame('minifier', $plugin->getId());
    }

    public function test_settings_page_class_exists(): void
    {
        $this->assertTrue(class_exists(MinifierSettings::class));
    }

    public function test_can_access_method(): void
    {
        $this->assertIsBool(MinifierSettings::canAccess());
    }

    public function test_form_schema_structure(): void
    {
        $page = new MinifierSettings();
        $schema = $page->getFormSchema();
        $this->assertNotEmpty($schema);
    }
}
