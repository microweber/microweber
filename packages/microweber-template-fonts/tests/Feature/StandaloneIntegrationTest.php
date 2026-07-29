<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Tests\Feature;

use Illuminate\Http\UploadedFile;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;
use MicroweberPackages\TemplateFonts\Tests\TestCase;

/**
 * End-to-end integration of manager + routes as a standalone Laravel package.
 */
class StandaloneIntegrationTest extends TestCase
{
    public function test_generator_resolves_from_container(): void
    {
        $service = app(TemplateFontsManager::class);
        $this->assertInstanceOf(TemplateFontsManager::class, $service);
    }

    public function test_service_is_singleton(): void
    {
        $a = app(TemplateFontsManager::class);
        $b = app(TemplateFontsManager::class);
        $this->assertSame($a, $b);
    }

    public function test_alias_binding(): void
    {
        $fromAlias = app('template-fonts');
        $fromClass = app(TemplateFontsManager::class);
        $this->assertSame($fromAlias, $fromClass);
    }

    public function test_enable_font_then_css_route(): void
    {
        $manager = app(TemplateFontsManager::class);
        if (!$manager->tableReady()) {
            $this->markTestSkipped('table not ready');
        }

        $manager->enableFont('Poppins');
        try {
            $response = $this->get(route('print_custom_css_fonts'));
            $response->assertOk();
            // Local copies render as a lowercase-slug path ("…/poppins/font.css"),
            // so match case-insensitively rather than the remote @import form.
            $this->assertStringContainsStringIgnoringCase('Poppins', (string) $response->getContent());
        } finally {
            $manager->removeFont('Poppins');
        }
    }

    public function test_custom_font_upload_and_css(): void
    {
        $manager = app(TemplateFontsManager::class);
        if (!$manager->tableReady()) {
            $this->markTestSkipped('table not ready');
        }

        $source = base_path('Modules/Captcha/resources/fonts/font1.ttf');
        if (!is_file($source)) {
            $source = dirname(__DIR__, 4) . '/Modules/Captcha/resources/fonts/font1.ttf';
        }
        if (!is_file($source)) {
            $this->markTestSkipped('No captcha TTF fixture');
        }

        $tmp = sys_get_temp_dir() . '/standalone-font-' . uniqid() . '.ttf';
        copy($source, $tmp);
        $file = new UploadedFile($tmp, 'Standalone.ttf', 'font/ttf', null, true);

        $result = $manager->uploadCustomFont($file, 'Standalone Custom');
        $this->assertTrue($result['success'] ?? false);

        $css = $manager->getFontsStylesheetCss();
        $this->assertStringContainsString('standalone-custom', $css);
        $this->assertStringContainsString('@import', $css);

        $font = $result['font'] ?? null;
        $this->assertNotNull($font);
        $this->assertNotEmpty($font->css_path);
        $localCss = (string) file_get_contents((string) $font->css_path);
        $this->assertStringContainsString('@font-face', $localCss);
        $this->assertStringContainsString('Standalone Custom', $localCss);

        @unlink($tmp);
    }
}
