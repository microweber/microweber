<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Verify the Bootstrap template ships with the expected page layouts
 * and that every color-scheme JSON in the style-packs folder is valid
 * and exposes the CSS custom properties the template relies on.
 */
class BootstrapTemplateLayoutsTest extends TestCase
{
    private string $templateRoot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->templateRoot = base_path('Templates/Bootstrap');
    }

    public function test_bootstrap_template_exposes_expected_page_layouts(): void
    {
        $viewsDir = $this->templateRoot . '/resources/views';
        $this->assertDirectoryExists($viewsDir);

        $expectedLayouts = [
            'index',
            'clean',
            'contact_us',
            'about',
            'services',
            'landing',
            'pricing',
            'hosting-compare',
        ];

        foreach ($expectedLayouts as $name) {
            $path = $viewsDir . '/' . $name . '.blade.php';
            $this->assertFileExists($path, "Expected layout file {$name}.blade.php missing");

            $contents = file_get_contents($path);
            $this->assertStringContainsString('type: layout', $contents,
                "Layout {$name} must declare type: layout in its frontmatter");
            $this->assertStringContainsString("@extends('templates.bootstrap::layouts.master')", $contents,
                "Layout {$name} must extend the master template");
            $this->assertStringContainsString("@section('content')", $contents,
                "Layout {$name} must define the content section");
        }
    }

    public function test_new_layouts_reference_only_existing_module_skins(): void
    {
        $viewsDir = $this->templateRoot . '/resources/views';
        $moduleTemplatesDir = $this->templateRoot . '/resources/views/modules/layouts/templates';

        $newLayouts = ['about', 'services', 'landing', 'pricing', 'hosting-compare'];

        foreach ($newLayouts as $name) {
            $contents = file_get_contents($viewsDir . '/' . $name . '.blade.php');
            preg_match_all('/<module\s+type="layouts"\s+template="([^"]+)"/', $contents, $matches);

            $this->assertNotEmpty($matches[1],
                "Layout {$name} should compose at least one layout module");

            foreach ($matches[1] as $templatePath) {
                $fullPath = $moduleTemplatesDir . '/' . $templatePath . '.blade.php';
                $this->assertFileExists($fullPath,
                    "Layout {$name} references missing module template: {$templatePath}");
            }
        }
    }

    public function test_hosting_compare_page_stacks_the_new_superhosting_style_skins(): void
    {
        // The hosting-compare page is modelled on superhosting.bg's
        // plan-comparison page: a titles block, the 4-tier pricing cards,
        // the advantages grid, and the detailed comparison matrix.
        $page = file_get_contents($this->templateRoot . '/resources/views/hosting-compare.blade.php');

        foreach ([
            'titles/skin-1',
            'pricing/skin-2',
            'features/skin-2',
            'pricing/skin-3',
        ] as $expected) {
            $this->assertStringContainsString('template="' . $expected . '"', $page,
                "hosting-compare page must compose the {$expected} layout");
        }
    }

    public function test_new_module_skins_declare_valid_metadata_and_ship_previews(): void
    {
        $moduleTemplatesDir = $this->templateRoot . '/resources/views/modules/layouts/templates';

        $expected = [
            'pricing/skin-2' => ['name' => 'Pricing 2 - Hosting Plans', 'categories' => 'Pricing'],
            'pricing/skin-3' => ['name' => 'Pricing 3 - Compare Matrix', 'categories' => 'Pricing'],
            'features/skin-2' => ['name' => 'Features 2 - Advantages Grid', 'categories' => 'Features'],
        ];

        foreach ($expected as $skin => $meta) {
            $path = $moduleTemplatesDir . '/' . $skin . '.blade.php';
            $this->assertFileExists($path, "Missing skin file: {$skin}.blade.php");

            $contents = file_get_contents($path);
            $this->assertStringContainsString('type: layout', $contents,
                "Skin {$skin} must declare type: layout");
            $this->assertStringContainsString('name: ' . $meta['name'], $contents,
                "Skin {$skin} must declare the expected name metadata");
            $this->assertStringContainsString('categories: ' . $meta['categories'], $contents,
                "Skin {$skin} must be filed under the {$meta['categories']} category so the layout picker lists it");

            // Must be inline-editable (admins drag modules into a `rel="module"` section)
            $this->assertMatchesRegularExpression(
                '/rel="module"/',
                $contents,
                "Skin {$skin} must expose rel=\"module\" so the admin inline editor treats the section as editable"
            );

            // Must honour the shared padding-top/padding-bottom layout classes
            $this->assertStringContainsString("\$classes['padding_top']", $contents,
                "Skin {$skin} must apply the shared padding_top layout class");
            $this->assertStringContainsString("\$classes['padding_bottom']", $contents,
                "Skin {$skin} must apply the shared padding_bottom layout class");
        }
    }

    public function test_new_pricing_and_features_skins_compile_as_blade(): void
    {
        $moduleTemplatesDir = $this->templateRoot . '/resources/views/modules/layouts/templates';
        $compiler = app('blade.compiler');

        foreach (['pricing/skin-2', 'pricing/skin-3', 'features/skin-2'] as $skin) {
            $raw = file_get_contents($moduleTemplatesDir . '/' . $skin . '.blade.php');
            $compiled = $compiler->compileString($raw);
            $this->assertNotEmpty($compiled, "Blade compiler returned empty output for {$skin}");
            // Compiler errors throw; reaching here means the template is syntactically valid.
            $this->assertStringNotContainsString('<?php /* Blade syntax error', $compiled);
        }
    }

    public function test_all_color_style_packs_are_valid_json(): void
    {
        $colorsDir = $this->templateRoot . '/resources/assets/design-styles/style-packs/colors';
        $this->assertDirectoryExists($colorsDir);

        $files = glob($colorsDir . '/*.json');
        $this->assertGreaterThanOrEqual(10, count($files),
            'Expected at least 10 color style packs');

        foreach ($files as $file) {
            $raw = file_get_contents($file);
            $decoded = json_decode($raw, true);
            $this->assertSame(JSON_ERROR_NONE, json_last_error(),
                "Invalid JSON in " . basename($file) . ': ' . json_last_error_msg());
            $this->assertIsArray($decoded);
            $this->assertArrayHasKey('settings', $decoded,
                basename($file) . ' must have a settings key');
        }
    }

    public function test_every_color_pack_defines_required_css_variables(): void
    {
        $colorsDir = $this->templateRoot . '/resources/assets/design-styles/style-packs/colors';
        $files = glob($colorsDir . '/*.json');

        // Minimum set that any theme must provide for the template to render correctly
        $requiredVars = [
            '--mw-background-color',
            '--mw-primary-color',
            '--mw-body-color',
            '--mw-heading-color',
            '--mw-link-color',
            '--mw-btn-background-color',
            '--mw-btn-text-color',
            '--mw-header-background-color',
        ];

        $missing = [];
        foreach ($files as $file) {
            $decoded = json_decode(file_get_contents($file), true);
            $properties = $decoded['settings'][0]['fieldSettings']['styleProperties'][0]['properties'] ?? null;

            if (!is_array($properties)) {
                $missing[basename($file)] = ['<no styleProperties block>'];
                continue;
            }

            $lacking = array_diff($requiredVars, array_keys($properties));
            if (!empty($lacking)) {
                $missing[basename($file)] = array_values($lacking);
            }
        }

        $this->assertEmpty($missing,
            "Color packs missing required CSS vars:\n" . json_encode($missing, JSON_PRETTY_PRINT));
    }

    public function test_main_colors_registry_points_to_style_packs_folder(): void
    {
        $registry = $this->templateRoot . '/resources/assets/design-styles/main-styles/main-colors.json';
        $this->assertFileExists($registry);

        $decoded = json_decode(file_get_contents($registry), true);
        $this->assertSame(JSON_ERROR_NONE, json_last_error());

        $merged = $decoded['settings'][0]['mergeFieldSettingsFromFolders'] ?? [];
        $this->assertContains('resources/assets/design-styles/style-packs/colors', $merged,
            'main-colors.json must merge style packs from the colors folder');
    }
}
