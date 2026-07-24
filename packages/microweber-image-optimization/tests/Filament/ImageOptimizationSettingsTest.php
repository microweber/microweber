<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Tests\Filament;

use MicroweberPackages\ImageOptimization\Filament\ImageOptimizationPlugin;
use MicroweberPackages\ImageOptimization\Filament\Pages\ImageOptimizationSettings;
use MicroweberPackages\ImageOptimization\Tests\TestCase;

class ImageOptimizationSettingsTest extends TestCase
{
    public function test_plugin_has_correct_id(): void
    {
        $plugin = new ImageOptimizationPlugin();
        $this->assertEquals('image-optimization', $plugin->getId());
    }

    public function test_plugin_can_be_made(): void
    {
        $plugin = ImageOptimizationPlugin::make();
        $this->assertInstanceOf(ImageOptimizationPlugin::class, $plugin);
    }

    public function test_settings_page_class_exists(): void
    {
        $this->assertTrue(class_exists(ImageOptimizationSettings::class));
    }

    public function test_settings_page_has_correct_slug(): void
    {
        $slug = ImageOptimizationSettings::getSlug();
        $this->assertStringContainsString('image-optimization-settings', $slug);
    }

    public function test_settings_page_has_view(): void
    {
        $viewPath = __DIR__ . '/../../resources/views/filament/pages/image-optimization-settings.blade.php';
        $this->assertFileExists($viewPath);
    }

    public function test_settings_page_has_form_schema(): void
    {
        $page = new ImageOptimizationSettings();
        $schema = $page->getFormSchema();
        $this->assertIsArray($schema);
        $this->assertNotEmpty($schema);
    }

    public function test_settings_page_can_access_checks_admin_or_true(): void
    {
        $result = ImageOptimizationSettings::canAccess();
        $this->assertIsBool($result);
    }

    public function test_settings_page_data_property_exists(): void
    {
        $page = new ImageOptimizationSettings();
        $this->assertIsArray($page->data);
    }
}
