<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\TemplateFonts\Models\TemplateFont;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;
use MicroweberPackages\TemplateFonts\Tests\TestCase;

class TemplateFontsManagerTest extends TestCase
{
    public function test_service_resolves(): void
    {
        $manager = app(TemplateFontsManager::class);
        $this->assertInstanceOf(TemplateFontsManager::class, $manager);
    }

    public function test_system_fonts_are_returned(): void
    {
        $manager = app(TemplateFontsManager::class);
        $fonts = $manager->getFonts();
        $this->assertIsArray($fonts);
        $this->assertNotEmpty($fonts);
        $this->assertTrue(
            collect($fonts)->contains(fn ($f) => is_string($f) && str_contains($f, 'Arial'))
        );
    }

    public function test_enable_and_list_font(): void
    {
        $manager = app(TemplateFontsManager::class);
        if (!$manager->tableReady()) {
            $this->markTestSkipped('template_fonts table not ready');
        }

        $ok = $manager->enableFont('Roboto', 'google', 'sans-serif');
        $this->assertTrue($ok);

        $enabled = $manager->getEnabledFonts();
        $this->assertContains('Roboto', $enabled);

        $this->assertDatabaseHas('template_fonts', [
            'family' => 'Roboto',
            'provider' => 'google',
            'is_enabled' => 1,
        ]);
    }

    public function test_remove_font(): void
    {
        $manager = app(TemplateFontsManager::class);
        if (!$manager->tableReady()) {
            $this->markTestSkipped('template_fonts table not ready');
        }

        $manager->enableFont('Open Sans');
        $this->assertContains('Open Sans', $manager->getEnabledFonts());

        $manager->removeFont('Open Sans');
        $this->assertNotContains('Open Sans', $manager->getEnabledFonts());
    }

    public function test_available_fonts_catalog_not_empty(): void
    {
        $manager = app(TemplateFontsManager::class);
        $available = $manager->getAvailableFonts();
        $this->assertIsArray($available);
        $this->assertNotEmpty($available);
        $this->assertArrayHasKey('family', $available[0]);
    }

    public function test_stylesheet_css_for_enabled_google_font(): void
    {
        $manager = app(TemplateFontsManager::class);
        if (!$manager->tableReady()) {
            $this->markTestSkipped('template_fonts table not ready');
        }

        $manager->enableFont('Cairo');
        try {
            $css = $manager->getFontsStylesheetCss();
            $this->assertIsString($css);
            // Cairo is a real Google font. When a local copy already exists the
            // manager (correctly) emits the local path using the lowercase slug
            // ("…/cairo/font.css") instead of a Google @import ("Cairo"), so
            // match case-insensitively rather than pinning the remote form.
            $this->assertStringContainsStringIgnoringCase('Cairo', $css);
        } finally {
            // Don't leave Cairo enabled in the shared CMS DB for later tests.
            $manager->removeFont('Cairo');
        }
    }

    public function test_custom_font_upload(): void
    {
        $manager = app(TemplateFontsManager::class);
        if (!$manager->tableReady()) {
            $this->markTestSkipped('template_fonts table not ready');
        }

        $source = $this->findSampleTtf();
        if ($source === null) {
            $this->markTestSkipped('No sample TTF available');
        }

        $tmp = sys_get_temp_dir() . '/test-font-' . uniqid() . '.ttf';
        copy($source, $tmp);

        $upload = new UploadedFile($tmp, 'CaptchaFont.ttf', 'font/ttf', null, true);
        $result = $manager->uploadCustomFont($upload, 'Captcha Test Font');

        $this->assertTrue($result['success'] ?? false);
        $this->assertContains('Captcha Test Font', $manager->getEnabledFonts());

        $css = $manager->getFontsStylesheetCss();
        $this->assertStringContainsString('captcha-test-font', $css);
        $this->assertStringContainsString('@import', $css);

        $font = \MicroweberPackages\TemplateFonts\Models\TemplateFont::query()
            ->where('family', 'Captcha Test Font')
            ->first();
        $this->assertNotNull($font);
        $this->assertNotEmpty($font->css_path);
        $this->assertFileExists((string) $font->css_path);
        $this->assertStringContainsString('Captcha Test Font', (string) file_get_contents((string) $font->css_path));

        @unlink($tmp);
    }

    public function test_google_domain_proxy_config(): void
    {
        $manager = new TemplateFontsManager([
            'use_google_fonts_proxy' => true,
            'google_fonts_proxy_domain' => 'google-fonts.microweberapi.com',
            'google_fonts_domain' => 'fonts.googleapis.com',
            'download_google_fonts_locally' => false,
            'migrate_legacy_options' => false,
        ]);

        $this->assertSame('google-fonts.microweberapi.com', $manager->resolveGoogleDomain());
    }

    public function test_legacy_option_folds_into_enabled_fonts_via_migration(): void
    {
        $manager = app(TemplateFontsManager::class);
        if (!$manager->tableReady()) {
            $this->markTestSkipped('template_fonts table not ready');
        }
        if (!Schema::hasTable('options')) {
            $this->markTestSkipped('options table not available');
        }

        TemplateFont::query()->delete();

        // Real, existing Google fonts so migrated rows are valid (not dead 404s).
        $legacyFonts = ['Roboto', 'Open Sans'];

        \Illuminate\Support\Facades\DB::table('options')->where([
            'option_key' => 'enabled_custom_fonts',
            'option_group' => 'template',
        ])->delete();

        \Illuminate\Support\Facades\DB::table('options')->insert([
            'option_key' => 'enabled_custom_fonts',
            'option_group' => 'template',
            'option_value' => json_encode($legacyFonts),
        ]);

        try {
            // The one-time fold is the migration's job (no runtime migration on
            // every load), so run it explicitly and assert the manager then reads
            // the migrated rows.
            $dataMigration = require __DIR__ . '/../../database/migrations/2026_07_29_000001_migrate_legacy_enabled_custom_fonts.php';
            $dataMigration->up();

            $enabled = $manager->getEnabledFonts();
            $this->assertContains('Roboto', $enabled);
            $this->assertContains('Open Sans', $enabled);
        } finally {
            // Seeds the shared CMS options/template_fonts tables — clean up so the
            // migrated fonts don't leak into other tests' generated CSS.
            foreach ($legacyFonts as $family) {
                $manager->removeFont($family);
            }
            \Illuminate\Support\Facades\DB::table('options')->where([
                'option_key' => 'enabled_custom_fonts',
                'option_group' => 'template',
            ])->delete();
        }
    }

    protected function findSampleTtf(): ?string
    {
        $candidates = [
            base_path('Modules/Captcha/resources/fonts/font1.ttf'),
            dirname(__DIR__, 4) . '/Modules/Captcha/resources/fonts/font1.ttf',
            __DIR__ . '/../../../../Modules/Captcha/resources/fonts/font1.ttf',
        ];
        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }
}
