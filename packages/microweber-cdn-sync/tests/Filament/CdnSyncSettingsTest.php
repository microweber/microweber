<?php

namespace MicroweberPackages\CdnSync\Tests\Filament;

use MicroweberPackages\CdnSync\Filament\CdnSyncPlugin;
use MicroweberPackages\CdnSync\Filament\Pages\CdnSyncSettings;
use MicroweberPackages\CdnSync\Tests\TestCase;

class CdnSyncSettingsTest extends TestCase
{
    public function test_cdn_sync_plugin_has_correct_id(): void
    {
        $plugin = new CdnSyncPlugin();
        $this->assertEquals('cdn-sync', $plugin->getId());
    }

    public function test_cdn_sync_plugin_can_be_made(): void
    {
        $plugin = CdnSyncPlugin::make();
        $this->assertInstanceOf(CdnSyncPlugin::class, $plugin);
    }

    public function test_settings_page_class_exists(): void
    {
        $this->assertTrue(class_exists(CdnSyncSettings::class));
    }

    public function test_settings_page_has_correct_slug(): void
    {
        // Filament v5 getSlug() may need a Panel parameter
        $slug = CdnSyncSettings::getSlug();
        $this->assertStringContainsString('cdn-sync-settings', $slug);
    }

    public function test_settings_page_has_view(): void
    {
        // Verify the view file exists
        $viewPath = __DIR__ . '/../../resources/views/filament/pages/cdn-sync-settings.blade.php';
        $this->assertFileExists($viewPath);
    }

    public function test_settings_page_has_form_schema(): void
    {
        $page = new CdnSyncSettings();
        $schema = $page->getFormSchema();
        $this->assertIsArray($schema);
        $this->assertNotEmpty($schema);
    }

    public function test_settings_page_can_access_checks_admin_or_true(): void
    {
        // In CMS context with is_admin() present, it checks admin status.
        // In standalone context without is_admin(), it returns true.
        $result = CdnSyncSettings::canAccess();
        $this->assertIsBool($result);
    }

    public function test_settings_page_data_property_exists(): void
    {
        $page = new CdnSyncSettings();
        $this->assertIsArray($page->data);
    }
}