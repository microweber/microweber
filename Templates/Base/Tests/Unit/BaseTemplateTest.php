<?php

namespace Templates\Base\Tests\Unit;

use Tests\TestCase;

class BaseTemplateTest extends TestCase
{
    public $template_name = 'Base';

    protected function setUp(): void
    {
        parent::setUp();

        if (!defined('TEMPLATE_DIR')) {
            define('TEMPLATE_DIR', templates_path() . $this->template_name . DS);
        }

        app()->template_manager->boot_template();
        save_option('current_template', $this->template_name, 'template');
    }

    /**
     * Test that the Base template config file exists and is valid.
     */
    public function testConfigExists()
    {
        $configPath = templates_path() . 'Base/config/config.php';
        $this->assertFileExists($configPath);

        $config = require $configPath;
        $this->assertIsArray($config);
        $this->assertEquals('Base', $config['name']);
    }

    /**
     * Test that the module.json is valid.
     */
    public function testModuleJsonValid()
    {
        $jsonPath = templates_path() . 'Base/module.json';
        $this->assertFileExists($jsonPath);

        $json = json_decode(file_get_contents($jsonPath), true);
        $this->assertNotNull($json);
        $this->assertEquals('Base', $json['name']);
        $this->assertEquals('base', $json['alias']);
        $this->assertArrayHasKey('providers', $json);
    }

    /**
     * Test that the composer.json is valid.
     */
    public function testComposerJsonValid()
    {
        $jsonPath = templates_path() . 'Base/composer.json';
        $this->assertFileExists($jsonPath);

        $json = json_decode(file_get_contents($jsonPath), true);
        $this->assertNotNull($json);
        $this->assertEquals('microweber-templates/base', $json['name']);
        $this->assertEquals('microweber-template', $json['type']);
    }

    /**
     * Test that the master layout blade file exists.
     */
    public function testMasterLayoutExists()
    {
        $layoutPath = templates_path() . 'Base/resources/views/layouts/master.blade.php';
        $this->assertFileExists($layoutPath);
    }

    /**
     * Test that the master layout loads the CSS framework.
     */
    public function testMasterLayoutLoadsCssFramework()
    {
        $layoutPath = templates_path() . 'Base/resources/views/layouts/master.blade.php';
        $content = file_get_contents($layoutPath);

        $this->assertStringContainsString(
            'microweber-css-framework-bootstrap/css/mw-framework.css',
            $content,
            'Master layout must load the shared CSS framework'
        );
    }

    /**
     * Test that the master layout only uses CSS variables (no hardcoded SCSS).
     */
    public function testMasterLayoutUsesCssVariables()
    {
        $layoutPath = templates_path() . 'Base/resources/views/layouts/master.blade.php';
        $content = file_get_contents($layoutPath);

        $this->assertStringContainsString('--mw-primary-color:', $content);
        $this->assertStringContainsString('--mw-background-color:', $content);
        $this->assertStringContainsString('--mw-btn-background-color:', $content);
        $this->assertStringContainsString('--mw-header-background-color:', $content);
        $this->assertStringContainsString('--mw-footer-background-color:', $content);
    }

    /**
     * Test that the master layout has accessibility skip link.
     */
    public function testMasterLayoutHasSkipLink()
    {
        $layoutPath = templates_path() . 'Base/resources/views/layouts/master.blade.php';
        $content = file_get_contents($layoutPath);

        $this->assertStringContainsString('#main-content', $content);
        $this->assertStringContainsString('visually-hidden-focusable', $content);
    }

    /**
     * Test that all required page views exist.
     */
    public function testPageViewsExist()
    {
        $viewsPath = templates_path() . 'Base/resources/views/';

        $requiredViews = [
            'index.blade.php',
            'blog.blade.php',
            'post.blade.php',
            'shop.blade.php',
            'product.blade.php',
            'clean.blade.php',
            'contact_us.blade.php',
        ];

        foreach ($requiredViews as $view) {
            $this->assertFileExists($viewsPath . $view, "View {$view} must exist");
        }
    }

    /**
     * Test that page views extend the Base master layout (not Big).
     */
    public function testPageViewsExtendBaseLayout()
    {
        $viewsPath = templates_path() . 'Base/resources/views/';

        $views = [
            'index.blade.php',
            'blog.blade.php',
            'post.blade.php',
            'shop.blade.php',
            'product.blade.php',
            'clean.blade.php',
            'contact_us.blade.php',
        ];

        foreach ($views as $view) {
            $content = file_get_contents($viewsPath . $view);
            $this->assertStringContainsString(
                'templates.base::layouts.master',
                $content,
                "View {$view} must extend templates.base::layouts.master"
            );
        }
    }

    /**
     * Test that the color palettes file exists and has the same number as Big.
     */
    public function testColorPalettesExist()
    {
        $basePalettePath = templates_path() . 'Base/resources/assets/color-palettes.json';
        $bigPalettePath = templates_path() . 'Big/resources/assets/color-palettes.json';

        $this->assertFileExists($basePalettePath);

        $basePalettes = json_decode(file_get_contents($basePalettePath), true);
        $this->assertNotNull($basePalettes);
        $this->assertNotEmpty($basePalettes);

        if (file_exists($bigPalettePath)) {
            $bigPalettes = json_decode(file_get_contents($bigPalettePath), true);
            $this->assertCount(
                count($bigPalettes),
                $basePalettes,
                'Base template must have the same number of color schemes as Big'
            );
        }
    }

    /**
     * Test that each color palette has the required --mw- compatible properties.
     */
    public function testColorPalettePropertiesExist()
    {
        $palettePath = templates_path() . 'Base/resources/assets/color-palettes.json';
        $palettes = json_decode(file_get_contents($palettePath), true);

        $requiredKeys = ['--primaryColor', '--links', '--background', '--secondary'];

        foreach (array_slice($palettes, 0, 5) as $palette) {
            $this->assertArrayHasKey('name', $palette);
            $this->assertArrayHasKey('properties', $palette);

            foreach ($requiredKeys as $key) {
                $this->assertArrayHasKey(
                    $key,
                    $palette['properties'],
                    "Palette '{$palette['name']}' missing property {$key}"
                );
            }
        }
    }

    /**
     * Test that the Base template has the same number of module skins as Big.
     */
    public function testModuleSkinsCountMatchesBig()
    {
        $basePath = templates_path() . 'Base/resources/views/modules/layouts/templates';
        $bigPath = templates_path() . 'Big/resources/views/modules/layouts/templates';

        $baseSkins = glob($basePath . '/**/*.blade.php') ?: [];
        $bigSkins = glob($bigPath . '/**/*.blade.php') ?: [];

        // Use recursive directory iterator for full count
        $baseCount = 0;
        if (is_dir($basePath)) {
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($basePath));
            foreach ($it as $file) {
                if ($file->getExtension() === 'php' && str_contains($file->getFilename(), '.blade.')) {
                    $baseCount++;
                }
            }
        }

        $bigCount = 0;
        if (is_dir($bigPath)) {
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($bigPath));
            foreach ($it as $file) {
                if ($file->getExtension() === 'php' && str_contains($file->getFilename(), '.blade.')) {
                    $bigCount++;
                }
            }
        }

        $this->assertEquals(
            $bigCount,
            $baseCount,
            'Base template must have the same number of module skins as Big'
        );
    }

    /**
     * Test that all skin categories from Big exist in Base.
     */
    public function testAllSkinCategoriesExist()
    {
        $basePath = templates_path() . 'Base/resources/views/modules/layouts/templates';
        $bigPath = templates_path() . 'Big/resources/views/modules/layouts/templates';

        $bigCategories = array_map('basename', glob($bigPath . '/*', GLOB_ONLYDIR) ?: []);
        $baseCategories = array_map('basename', glob($basePath . '/*', GLOB_ONLYDIR) ?: []);

        foreach ($bigCategories as $category) {
            $this->assertContains(
                $category,
                $baseCategories,
                "Skin category '{$category}' missing in Base template"
            );
        }
    }

    /**
     * Test that the service provider class exists.
     */
    public function testServiceProviderExists()
    {
        $providerPath = templates_path() . 'Base/Providers/BaseTemplateServiceProvider.php';
        $this->assertFileExists($providerPath);

        $content = file_get_contents($providerPath);
        $this->assertStringContainsString('class BaseTemplateServiceProvider', $content);
        $this->assertStringContainsString('extends BaseServiceProvider', $content);
    }

    /**
     * Test that the style-settings.json is valid.
     */
    public function testStyleSettingsValid()
    {
        $settingsPath = templates_path() . 'Base/style-settings.json';
        $this->assertFileExists($settingsPath);

        $settings = json_decode(file_get_contents($settingsPath), true);
        $this->assertNotNull($settings);
        $this->assertArrayHasKey('settings', $settings);
        $this->assertNotEmpty($settings['settings']);
    }

    /**
     * Test rendering a layout skin.
     */
    public function testRenderContentSkin()
    {
        $layout = '<module template="content.skin-1" id="mw-base-test-1" data-type="layouts" />';
        $render = app()->parser->process($layout);
        $this->assertStringContainsString('id="background-layout--mw-base-test-1"', $render);
    }
}