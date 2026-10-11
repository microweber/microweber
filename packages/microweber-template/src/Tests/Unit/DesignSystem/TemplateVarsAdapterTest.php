<?php

namespace MicroweberPackages\Template\Tests\Unit\DesignSystem;

use Templates\Big\DesignSystem\BigTemplateVarsAdapter;
use Templates\Bootstrap\DesignSystem\BootstrapTemplateVarsAdapter;
use PHPUnit\Framework\TestCase;

class TemplateVarsAdapterTest extends TestCase
{
    // =============================================
    // BigTemplateVarsAdapter tests
    // =============================================

    public function test_big_adapter_template_name(): void
    {
        $adapter = new BigTemplateVarsAdapter();
        $this->assertSame('big', $adapter->templateName());
    }

    public function test_big_adapter_var_prefix(): void
    {
        $adapter = new BigTemplateVarsAdapter();
        $this->assertSame('--mw-', $adapter->varPrefix());
    }

    public function test_big_adapter_identity_property_map(): void
    {
        $adapter = new BigTemplateVarsAdapter();
        // Big template uses --mw-* natively, so map is empty (identity)
        $this->assertSame([], $adapter->propertyMap());
    }

    public function test_big_adapter_style_pack_passthrough(): void
    {
        $adapter = new BigTemplateVarsAdapter();
        $input = [
            '--mw-primary-color' => '#ff0000',
            '--mw-background-color' => '#ffffff',
        ];

        $result = $adapter->mapStylePackToVars($input);

        // Identity — same vars come out
        $this->assertSame('#ff0000', $result['--mw-primary-color']);
        $this->assertSame('#ffffff', $result['--mw-background-color']);
    }

    public function test_big_adapter_maps_palette_to_mw_vars(): void
    {
        $adapter = new BigTemplateVarsAdapter();
        $paletteProps = [
            '--primaryColor' => '#ff8370',
            '--links' => '#00b1b0',
            '--background' => '#fec84d',
            '--btnBackground' => '#e42256',
            '--footerBg' => '#ffffff',
            '--headingColor' => '#212529',
            '--paragraphColor' => '#212529',
        ];

        $result = $adapter->mapPaletteToVars($paletteProps);

        $this->assertSame('#ff8370', $result['--mw-primary-color']);
        $this->assertSame('#00b1b0', $result['--mw-link-color']);
        $this->assertSame('#fec84d', $result['--mw-background-color']);
        $this->assertSame('#e42256', $result['--mw-btn-background-color']);
        $this->assertSame('#ffffff', $result['--mw-footer-background-color']);
        $this->assertSame('#212529', $result['--mw-heading-color']);
        $this->assertSame('#212529', $result['--mw-paragraph-color']);
    }

    public function test_big_adapter_skips_unknown_palette_props(): void
    {
        $adapter = new BigTemplateVarsAdapter();
        $result = $adapter->mapPaletteToVars([
            '--unknownProp' => '#123',
            '--primaryColor' => '#456',
        ]);

        $this->assertArrayNotHasKey('--unknownProp', $result);
        $this->assertSame('#456', $result['--mw-primary-color']);
    }

    public function test_big_adapter_renders_palette_css(): void
    {
        $adapter = new BigTemplateVarsAdapter();
        $css = $adapter->renderPaletteCss([
            '--primaryColor' => '#ff0000',
            '--links' => '#00ff00',
        ]);

        $this->assertStringContainsString(':root {', $css);
        $this->assertStringContainsString('--mw-primary-color: #ff0000;', $css);
        $this->assertStringContainsString('--mw-link-color: #00ff00;', $css);
        $this->assertStringContainsString('}', $css);
    }

    public function test_big_adapter_render_css_empty(): void
    {
        $adapter = new BigTemplateVarsAdapter();
        $this->assertSame('', $adapter->renderCssVars([]));
    }

    // =============================================
    // BootstrapTemplateVarsAdapter tests
    // =============================================

    public function test_bootstrap_adapter_template_name(): void
    {
        $adapter = new BootstrapTemplateVarsAdapter();
        $this->assertSame('bootstrap', $adapter->templateName());
    }

    public function test_bootstrap_adapter_var_prefix(): void
    {
        $adapter = new BootstrapTemplateVarsAdapter();
        $this->assertSame('--bs-', $adapter->varPrefix());
    }

    public function test_bootstrap_adapter_has_property_map(): void
    {
        $adapter = new BootstrapTemplateVarsAdapter();
        $map = $adapter->propertyMap();

        $this->assertNotEmpty($map);
        $this->assertSame('--bs-primary', $map['--mw-primary-color']);
        $this->assertSame('--bs-body-bg', $map['--mw-background-color']);
        $this->assertSame('--bs-body-color', $map['--mw-body-color']);
        $this->assertSame('--bs-link-color', $map['--mw-link-color']);
    }

    public function test_bootstrap_adapter_emits_both_mw_and_bs_vars(): void
    {
        $adapter = new BootstrapTemplateVarsAdapter();
        $input = [
            '--mw-primary-color' => '#3498db',
            '--mw-background-color' => '#ffffff',
            '--mw-body-color' => '#333333',
            '--mw-footer-text-color' => '#555555', // no --bs-* equivalent
        ];

        $result = $adapter->mapStylePackToVars($input);

        // --mw-* kept
        $this->assertSame('#3498db', $result['--mw-primary-color']);
        $this->assertSame('#ffffff', $result['--mw-background-color']);
        $this->assertSame('#333333', $result['--mw-body-color']);
        $this->assertSame('#555555', $result['--mw-footer-text-color']);

        // --bs-* aliases added
        $this->assertSame('#3498db', $result['--bs-primary']);
        $this->assertSame('#ffffff', $result['--bs-body-bg']);
        $this->assertSame('#333333', $result['--bs-body-color']);

        // No --bs-* for footer
        $this->assertArrayNotHasKey('--bs-footer-text-color', $result);
    }

    public function test_bootstrap_adapter_maps_palette_to_both(): void
    {
        $adapter = new BootstrapTemplateVarsAdapter();
        $paletteProps = [
            '--primaryColor' => '#e42256',
            '--links' => '#00b1b0',
            '--background' => '#fec84d',
        ];

        $result = $adapter->mapPaletteToVars($paletteProps);

        // --mw-* vars
        $this->assertSame('#e42256', $result['--mw-primary-color']);
        $this->assertSame('#00b1b0', $result['--mw-link-color']);
        $this->assertSame('#fec84d', $result['--mw-background-color']);

        // --bs-* aliases
        $this->assertSame('#e42256', $result['--bs-primary']);
        $this->assertSame('#00b1b0', $result['--bs-link-color']);
        $this->assertSame('#fec84d', $result['--bs-body-bg']);
    }

    public function test_bootstrap_adapter_renders_style_pack_css(): void
    {
        $adapter = new BootstrapTemplateVarsAdapter();
        $css = $adapter->renderStylePackCss([
            '--mw-primary-color' => '#3498db',
            '--mw-background-color' => '#ffffff',
        ]);

        $this->assertStringContainsString(':root {', $css);
        $this->assertStringContainsString('--mw-primary-color: #3498db;', $css);
        $this->assertStringContainsString('--bs-primary: #3498db;', $css);
        $this->assertStringContainsString('--mw-background-color: #ffffff;', $css);
        $this->assertStringContainsString('--bs-body-bg: #ffffff;', $css);
    }

    // =============================================
    // CSS rendering / security tests
    // =============================================

    public function test_render_css_vars_structure(): void
    {
        $adapter = new BigTemplateVarsAdapter();
        $css = $adapter->renderCssVars([
            '--mw-primary-color' => '#ff0000',
            '--mw-body-color' => '#000000',
        ]);

        $lines = explode("\n", $css);
        $this->assertSame(':root {', $lines[0]);
        $this->assertStringContainsString('--mw-primary-color: #ff0000;', $lines[1]);
        $this->assertStringContainsString('--mw-body-color: #000000;', $lines[2]);
        $this->assertSame('}', $lines[3]);
    }

    public function test_css_value_escaping_strips_semicolons(): void
    {
        $adapter = new BigTemplateVarsAdapter();
        $css = $adapter->renderCssVars([
            '--mw-primary-color' => '#ff0000; background: url(evil)',
        ]);

        // Semicolons and braces should be stripped from values
        $this->assertStringNotContainsString('; background', $css);
    }

    public function test_css_property_escaping(): void
    {
        $adapter = new BigTemplateVarsAdapter();
        $css = $adapter->renderCssVars([
            '--mw-primary-color' => '#ff0000',
        ]);

        // Property name should be clean
        $this->assertStringContainsString('--mw-primary-color:', $css);
    }
}