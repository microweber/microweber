<?php

declare(strict_types=1);

namespace MicroweberPackages\View\Tests\Feature;

use MicroweberPackages\View\Contracts\ModuleProcessorInterface;
use MicroweberPackages\View\StringBlade;
use MicroweberPackages\View\Support\HtmlAttributes;
use MicroweberPackages\View\Support\NullModuleProcessor;
use MicroweberPackages\View\Tests\TestCase;
use MicroweberPackages\View\TwigView;
use MicroweberPackages\View\View;
use MicroweberPackages\View\ViewServiceProvider;

/**
 * Validates the package API surface that a standalone Laravel app would use.
 */
class StandaloneAppValidationTest extends TestCase
{
    public function test_package_classes_are_loadable(): void
    {
        $this->assertTrue(class_exists(View::class));
        $this->assertTrue(class_exists(StringBlade::class));
        $this->assertTrue(class_exists(TwigView::class));
        $this->assertTrue(class_exists(ViewServiceProvider::class));
        $this->assertTrue(class_exists(HtmlAttributes::class));
        $this->assertTrue(interface_exists(ModuleProcessorInterface::class));
    }

    public function test_usable_without_cms_parser(): void
    {
        // Ensure ModuleProcessor can work without CMS parser
        $processor = app(ModuleProcessorInterface::class);
        $this->assertInstanceOf(ModuleProcessorInterface::class, $processor);

        $result = $processor->process('<module type="x" />');
        $this->assertIsString($result);
    }

    public function test_full_api_surface_for_external_apps(): void
    {
        $file = sys_get_temp_dir() . '/mw_view_api_' . uniqid('', true) . '.php';
        file_put_contents($file, 'OK:<?php echo $value; ?>');

        try {
            $view = new View($file);
            $view->assign('value', '1');
            $this->assertStringContainsString('OK:1', (string) $view);

            $blade = app(StringBlade::class)->render('{{ $a }}', ['a' => 'b']);
            $this->assertSame('b', trim($blade));

            $twig = app(TwigView::class)->render('{{ a }}', ['a' => 'c']);
            $this->assertSame('c', $twig);

            $attrs = HtmlAttributes::toString(['type' => 'logo']);
            $this->assertSame('type="logo"', $attrs);
        } finally {
            @unlink($file);
        }
    }

    public function test_optional_format_package_when_present(): void
    {
        if (!class_exists(\MicroweberPackages\Format\FormatService::class)) {
            $this->markTestSkipped('format package not installed');
        }

        $this->assertTrue(class_exists(\MicroweberPackages\Format\FormatService::class));
    }
}
