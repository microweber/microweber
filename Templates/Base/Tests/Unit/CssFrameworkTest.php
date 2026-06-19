<?php

namespace Templates\Base\Tests\Unit;

use Tests\TestCase;

class CssFrameworkTest extends TestCase
{
    private string $frameworkPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->frameworkPath = base_path('packages/microweber-css-framework-bootstrap/resources/assets/css');
    }

    /**
     * Test that the main framework CSS file exists.
     */
    public function testFrameworkEntryPointExists()
    {
        $this->assertFileExists($this->frameworkPath . '/mw-framework.css');
    }

    /**
     * Test that the variables file exists.
     */
    public function testVariablesFileExists()
    {
        $this->assertFileExists($this->frameworkPath . '/mw-variables.css');
    }

    /**
     * Test that all component CSS files exist.
     */
    public function testComponentFilesExist()
    {
        $components = [
            'mw-body.css',
            'mw-links.css',
            'mw-buttons.css',
            'mw-forms.css',
            'mw-header.css',
            'mw-footer.css',
            'mw-sections.css',
            'mw-shop.css',
            'mw-testimonials.css',
            'mw-utilities.css',
        ];

        foreach ($components as $component) {
            $this->assertFileExists(
                $this->frameworkPath . '/components/' . $component,
                "Component CSS file '{$component}' must exist"
            );
        }
    }

    /**
     * Test that the framework entry point imports all components.
     */
    public function testFrameworkImportsAllComponents()
    {
        $content = file_get_contents($this->frameworkPath . '/mw-framework.css');

        $this->assertStringContainsString('mw-variables.css', $content);
        $this->assertStringContainsString('mw-body.css', $content);
        $this->assertStringContainsString('mw-links.css', $content);
        $this->assertStringContainsString('mw-buttons.css', $content);
        $this->assertStringContainsString('mw-forms.css', $content);
        $this->assertStringContainsString('mw-header.css', $content);
        $this->assertStringContainsString('mw-footer.css', $content);
        $this->assertStringContainsString('mw-sections.css', $content);
        $this->assertStringContainsString('mw-shop.css', $content);
        $this->assertStringContainsString('mw-testimonials.css', $content);
        $this->assertStringContainsString('mw-utilities.css', $content);
        // Components migrated from Big so the framework is self-sufficient for
        // nav/menus, section layouts and animated backgrounds.
        $this->assertStringContainsString('mw-nav.css', $content);
        $this->assertStringContainsString('mw-layouts.css', $content);
        $this->assertStringContainsString('mw-animations.css', $content);
        // The framework vendors Bootstrap so consuming templates load nothing else.
        $this->assertStringContainsString('vendor/bootstrap.min.css', $content);
    }

    /**
     * Test that the variables file uses --mw- prefix consistently.
     */
    public function testVariablesUseMwPrefix()
    {
        $content = file_get_contents($this->frameworkPath . '/mw-variables.css');

        // Must contain --mw- prefixed variables
        $this->assertStringContainsString('--mw-primary-color:', $content);
        $this->assertStringContainsString('--mw-background-color:', $content);
        $this->assertStringContainsString('--mw-body-font-family:', $content);
        $this->assertStringContainsString('--mw-heading-color:', $content);
        $this->assertStringContainsString('--mw-link-color:', $content);
        $this->assertStringContainsString('--mw-btn-background-color:', $content);
        $this->assertStringContainsString('--mw-header-background-color:', $content);
        $this->assertStringContainsString('--mw-footer-background-color:', $content);
        $this->assertStringContainsString('--mw-form-control-background:', $content);
        $this->assertStringContainsString('--mw-text-on-dark-background-color:', $content);
    }

    /**
     * Test that components reference --mw- variables (not hardcoded values).
     */
    public function testComponentsUseVariables()
    {
        $components = [
            'mw-body.css' => '--mw-background-color',
            'mw-buttons.css' => '--mw-btn-background-color',
            'mw-forms.css' => '--mw-form-control-background',
            'mw-header.css' => '--mw-header-background-color',
            'mw-footer.css' => '--mw-footer-background-color',
            'mw-sections.css' => '--mw-section-background-color',
            'mw-links.css' => '--mw-link-color',
        ];

        foreach ($components as $file => $variable) {
            $content = file_get_contents($this->frameworkPath . '/components/' . $file);
            $this->assertStringContainsString(
                $variable,
                $content,
                "Component '{$file}' must use the CSS variable '{$variable}'"
            );
        }
    }

    /**
     * Test that the framework composer.json is valid.
     */
    public function testFrameworkComposerJsonValid()
    {
        $jsonPath = base_path('packages/microweber-css-framework-bootstrap/composer.json');
        $this->assertFileExists($jsonPath);

        $json = json_decode(file_get_contents($jsonPath), true);
        $this->assertNotNull($json);
        $this->assertEquals('microweber/css-framework-bootstrap', $json['name']);
    }

    /**
     * Test that the framework service provider exists.
     */
    public function testFrameworkServiceProviderExists()
    {
        $providerPath = base_path('packages/microweber-css-framework-bootstrap/Providers/CssFrameworkServiceProvider.php');
        $this->assertFileExists($providerPath);

        $content = file_get_contents($providerPath);
        $this->assertStringContainsString('class CssFrameworkServiceProvider', $content);
    }

    /**
     * Test that published assets exist in public directory.
     */
    public function testPublishedAssetsExist()
    {
        $publicPath = public_path('packages/microweber-css-framework-bootstrap/css');

        $this->assertFileExists($publicPath . '/mw-framework.css');
        $this->assertFileExists($publicPath . '/mw-variables.css');
        $this->assertDirectoryExists($publicPath . '/components');
    }

    /**
     * Test that the design style packs are included in the framework.
     */
    public function testDesignStylePacksExist()
    {
        $stylesPath = base_path('packages/microweber-css-framework-bootstrap/resources/assets/design-styles');

        $this->assertDirectoryExists($stylesPath . '/style-packs');
        $this->assertDirectoryExists($stylesPath . '/style-packs/colors');
        $this->assertDirectoryExists($stylesPath . '/style-packs/button-styles');
        $this->assertDirectoryExists($stylesPath . '/main-styles');
        $this->assertDirectoryExists($stylesPath . '/mw-styles');
    }
}