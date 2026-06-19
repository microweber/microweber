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
     * Test that Big template section styles are present in the shared framework.
     */
    public function testBigTemplateSectionStylesMigrated()
    {
        $content = file_get_contents($this->frameworkPath . '/components/mw-sections.css');

        // Timeline styles
        $this->assertStringContainsString('.feature-40', $content, 'Feature-40 timeline must be migrated');
        $this->assertStringContainsString('.timeline.mw-timeline', $content, 'MW timeline must be migrated');

        // Guesthouse styles
        $this->assertStringContainsString('.guesthouse-header', $content, 'Guesthouse header must be migrated');
        $this->assertStringContainsString('.guesthouse-slider', $content, 'Guesthouse slider must be migrated');

        // Beauty slider
        $this->assertStringContainsString('.beauty .product-slider', $content, 'Beauty product slider must be migrated');

        // Misc section
        $this->assertStringContainsString('.misc-12', $content, 'Misc-12 section must be migrated');

        // Overlay data attributes
        $this->assertStringContainsString('[data-overlay]', $content, 'Data overlay attributes must be migrated');
        $this->assertStringContainsString('[data-overlay="5"]', $content, 'Overlay opacity levels must be migrated');

        // Parallax
        $this->assertStringContainsString('[data-parallax]', $content, 'Parallax styles must be migrated');

        // Slick slider
        $this->assertStringContainsString('.slickSlider-skin-1', $content, 'Slick slider skin must be migrated');
        $this->assertStringContainsString('.slick-arrows-1', $content, 'Slick arrows must be migrated');
        $this->assertStringContainsString('.slick-arrows-2', $content, 'Slick arrows variant 2 must be migrated');

        // bxSlider
        $this->assertStringContainsString('.bx-wrapper', $content, 'bxSlider wrapper must be migrated');
        $this->assertStringContainsString('.bxSlider-skin-1', $content, 'bxSlider skin 1 must be migrated');
        $this->assertStringContainsString('.bxSlider-skin-3', $content, 'bxSlider skin 3 must be migrated');

        // Color scheme fix
        $this->assertStringContainsString('body.Base', $content, 'Color scheme fix for Base body must exist');
    }

    /**
     * Test that Big template utility classes are present in the shared framework.
     */
    public function testBigTemplateUtilitiesMigrated()
    {
        $content = file_get_contents($this->frameworkPath . '/components/mw-utilities.css');

        // Box shadows
        $this->assertStringContainsString('.shadow-0', $content, 'Shadow utility 0 must be migrated');
        $this->assertStringContainsString('.shadow-9', $content, 'Shadow utility 9 must be migrated');
        $this->assertStringContainsString('.hover-shadow-5', $content, 'Hover shadow must be migrated');

        // Hover utilities
        $this->assertStringContainsString('.hover-bg-primary', $content, 'Hover bg primary must be migrated');
        $this->assertStringContainsString('.hover-text-primary', $content, 'Hover text primary must be migrated');
        $this->assertStringContainsString('.hover-border-color-primary', $content, 'Hover border color must be migrated');

        // Border color utilities
        $this->assertStringContainsString('.border-color-primary', $content, 'Border color primary must be migrated');
        $this->assertStringContainsString('.border-color-dark', $content, 'Border color dark must be migrated');

        // Lead sizes
        $this->assertStringContainsString('.lead-1', $content, 'Lead-1 must be migrated');
        $this->assertStringContainsString('.lead-2', $content, 'Lead-2 must be migrated');
        $this->assertStringContainsString('.lead-3', $content, 'Lead-3 must be migrated');

        // Extended spacers
        $this->assertStringContainsString('.p-6', $content, 'Extended spacer p-6 must exist');
        $this->assertStringContainsString('.p-13', $content, 'Extended spacer p-13 must exist');
        $this->assertStringContainsString('.mt-10', $content, 'Extended margin mt-10 must exist');

        // Element sizes
        $this->assertStringContainsString('.w-200', $content, 'Width utility w-200 must be migrated');
        $this->assertStringContainsString('.h-400', $content, 'Height utility h-400 must be migrated');
        $this->assertStringContainsString('.maxw-300', $content, 'Max-width utility must be migrated');

        // Icon sizes
        $this->assertStringContainsString('icon-size-24px', $content, 'Icon size 24px must be migrated');
        $this->assertStringContainsString('icon-size-48px', $content, 'Icon size 48px must be migrated');

        // Container responsive
        $this->assertStringContainsString('min-width: 1400px', $content, 'Container responsive widths must be migrated');
        $this->assertStringContainsString('min-width: 2000px', $content, 'Container 2000px breakpoint must be migrated');

        // Container fluid
        $this->assertStringContainsString('.container-fluid', $content, 'Container fluid must be migrated');
        $this->assertStringContainsString('--mw-container-fluid-padding', $content, 'Container fluid must use CSS vars');

        // Position utilities
        $this->assertStringContainsString('.top-0', $content, 'Position top-0 must be migrated');
        $this->assertStringContainsString('.border-width-2', $content, 'Border width utility must be migrated');
        $this->assertStringContainsString('.text-decoration-no', $content, 'Text decoration utility must be migrated');
    }

    /**
     * Test that Big template shop styles are present in the shared framework.
     */
    public function testBigTemplateShopStylesMigrated()
    {
        $content = file_get_contents($this->frameworkPath . '/components/mw-shop.css');

        // Shop inner page
        $this->assertStringContainsString('.shop-inner-page .main-price', $content, 'Shop inner page pricing must be migrated');
        $this->assertStringContainsString('.shop-inner-page .heading', $content, 'Shop inner page heading must be migrated');
        $this->assertStringContainsString('.shop-inner-page .quantity', $content, 'Shop quantity controls must be migrated');
        $this->assertStringContainsString('.shop-inner-page .reviews', $content, 'Shop reviews must be migrated');
        $this->assertStringContainsString('.shop-inner-page .elevatezoom', $content, 'ElevateZoom must be migrated');

        // Shop products
        $this->assertStringContainsString('.image-square-2-colums-horizontal', $content, 'Image column sizes must be migrated');
        $this->assertStringContainsString('.image-square-3-colums-horizontal', $content, 'Image 3-col must be migrated');
        $this->assertStringContainsString('.image-square-4-colums-horizontal', $content, 'Image 4-col must be migrated');

        // Shopmag
        $this->assertStringContainsString('.shopmag-shop-left-column', $content, 'Shopmag shop must be migrated');
        $this->assertStringContainsString('.product-custom-fields-holder', $content, 'Product custom fields must be migrated');
    }

    /**
     * Test that Big template body/typography styles are in the shared framework.
     */
    public function testBigTemplateBodyStylesMigrated()
    {
        $content = file_get_contents($this->frameworkPath . '/components/mw-body.css');

        // Typography area
        $this->assertStringContainsString('.typography-area', $content, 'Big typography-area must be migrated');

        // List styles
        $this->assertStringContainsString('ul.style-1', $content, 'List style-1 must be migrated');
        $this->assertStringContainsString('ul.no-style', $content, 'List no-style must be migrated');

        // Ajax loading
        $this->assertStringContainsString('.js-ajax-loading', $content, 'Ajax loading must be migrated');
        $this->assertStringContainsString('mw-pulse', $content, 'Pulse keyframe must be migrated');

        // MW UI buttons
        $this->assertStringContainsString('.mw-ui-btn.df', $content, 'MW UI button defaults must be migrated');
    }

    /**
     * Test that Big template footer styles are in the shared framework.
     */
    public function testBigTemplateFooterStylesMigrated()
    {
        $content = file_get_contents($this->frameworkPath . '/components/mw-footer.css');

        $this->assertStringContainsString('.footer-skin-link', $content, 'Footer skin link must be migrated');
        $this->assertStringContainsString('.footer_skin', $content, 'Footer skin must be migrated');
        $this->assertStringContainsString('.footer-skin-ling-black-bg', $content, 'Footer black bg skin must be migrated');
    }

    /**
     * Test that variables file contains extended design tokens.
     */
    public function testVariablesContainExtendedTokens()
    {
        $content = file_get_contents($this->frameworkPath . '/mw-variables.css');

        $this->assertStringContainsString('--mw-lead-1-size', $content, 'Lead size token must exist');
        $this->assertStringContainsString('--mw-lead-2-size', $content, 'Lead 2 size token must exist');
        $this->assertStringContainsString('--mw-lead-3-size', $content, 'Lead 3 size token must exist');
        $this->assertStringContainsString('--mw-container-fluid-padding', $content, 'Container fluid padding token must exist');
    }

    /**
     * Test that sections CSS uses --mw- variables instead of SCSS vars.
     */
    public function testSectionsUsesCssVariablesNotScss()
    {
        $content = file_get_contents($this->frameworkPath . '/components/mw-sections.css');

        // Must not contain SCSS variables
        $this->assertStringNotContainsString('$silver', $content, 'Sections must not use SCSS $silver');
        $this->assertStringNotContainsString('$secondary', $content, 'Sections must not use SCSS $secondary');
        $this->assertStringNotContainsString('$dark', $content, 'Sections must not use SCSS $dark');
        $this->assertStringNotContainsString('$light', $content, 'Sections must not use SCSS $light');
        $this->assertStringNotContainsString('$white', $content, 'Sections must not use SCSS $white');

        // Must use --mw- variables
        $this->assertStringContainsString('var(--mw-primary-color)', $content, 'Must use --mw-primary-color');
        $this->assertStringContainsString('var(--mw-heading-color)', $content, 'Must use --mw-heading-color');
        $this->assertStringContainsString('var(--mw-secondary-color)', $content, 'Must use --mw-secondary-color');
    }

    /**
     * Test that utilities CSS uses --mw- variables instead of SCSS vars.
     */
    public function testUtilitiesUsesCssVariablesNotScss()
    {
        $content = file_get_contents($this->frameworkPath . '/components/mw-utilities.css');

        // Must not contain SCSS syntax
        $this->assertStringNotContainsString('@import', $content, 'Utilities must not use @import');
        $this->assertStringNotContainsString('@each', $content, 'Utilities must not use SCSS @each');
        $this->assertStringNotContainsString('@include', $content, 'Utilities must not use SCSS @include');
        $this->assertStringNotContainsString('$spacer', $content, 'Utilities must not use SCSS $spacer');

        // Must use CSS variables where appropriate
        $this->assertStringContainsString('var(--mw-primary-color)', $content, 'Must use --mw-primary-color');
        $this->assertStringContainsString('var(--mw-container-fluid-padding)', $content, 'Must use --mw-container-fluid-padding');
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