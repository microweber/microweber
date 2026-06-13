<?php

namespace MicroweberPackages\Template\Tests\Unit\DesignSystem;

use MicroweberPackages\Template\Services\DesignSystem\Adapters\BigTemplateVarsAdapter;
use MicroweberPackages\Template\Services\DesignSystem\Adapters\BootstrapTemplateVarsAdapter;
use MicroweberPackages\Template\Services\DesignSystem\ColorSchemesRegistry;
use MicroweberPackages\Template\Services\DesignSystem\DesignSystemService;
use MicroweberPackages\Template\Services\DesignSystem\StylePackRegistry;
use PHPUnit\Framework\TestCase;

class DesignSystemServiceTest extends TestCase
{
    protected DesignSystemService $service;
    protected ColorSchemesRegistry $colorSchemes;
    protected StylePackRegistry $stylePacks;

    protected function setUp(): void
    {
        parent::setUp();
        $this->colorSchemes = new ColorSchemesRegistry();
        $this->stylePacks = new StylePackRegistry();
        $this->service = new DesignSystemService($this->colorSchemes, $this->stylePacks);
    }

    public function test_color_schemes_accessor(): void
    {
        $this->assertSame($this->colorSchemes, $this->service->colorSchemes());
    }

    public function test_style_packs_accessor(): void
    {
        $this->assertSame($this->stylePacks, $this->service->stylePacks());
    }

    public function test_builtin_adapters_registered(): void
    {
        $adapters = $this->service->registeredAdapters();

        $this->assertContains('big', $adapters);
        $this->assertContains('bootstrap', $adapters);
    }

    public function test_adapter_for_big(): void
    {
        $adapter = $this->service->adapterFor('big');
        $this->assertInstanceOf(BigTemplateVarsAdapter::class, $adapter);
    }

    public function test_adapter_for_bootstrap(): void
    {
        $adapter = $this->service->adapterFor('bootstrap');
        $this->assertInstanceOf(BootstrapTemplateVarsAdapter::class, $adapter);
    }

    public function test_adapter_for_case_insensitive(): void
    {
        $adapter = $this->service->adapterFor('Big');
        $this->assertInstanceOf(BigTemplateVarsAdapter::class, $adapter);

        $adapter = $this->service->adapterFor('BOOTSTRAP');
        $this->assertInstanceOf(BootstrapTemplateVarsAdapter::class, $adapter);
    }

    public function test_adapter_for_unknown_falls_back_to_big(): void
    {
        $adapter = $this->service->adapterFor('unknown-template');
        $this->assertInstanceOf(BigTemplateVarsAdapter::class, $adapter);
    }

    public function test_get_palettes_for_big_template(): void
    {
        $this->colorSchemes->registerPalette([
            'name' => 'Test Palette',
            'mainColors' => ['#ff0000', '#00ff00', '#0000ff', '#ffffff'],
            'properties' => [
                '--primaryColor' => '#ff0000',
                '--links' => '#00ff00',
                '--background' => '#ffffff',
                '--headingColor' => '#000000',
            ],
        ]);

        $palettes = $this->service->getPalettesForTemplate('big');

        $this->assertCount(1, $palettes);
        $this->assertSame('Test Palette', $palettes[0]['name']);
        $this->assertSame(['#ff0000', '#00ff00', '#0000ff', '#ffffff'], $palettes[0]['mainColors']);

        // Properties should be mapped to --mw-* namespace
        $this->assertSame('#ff0000', $palettes[0]['properties']['--mw-primary-color']);
        $this->assertSame('#00ff00', $palettes[0]['properties']['--mw-link-color']);
        $this->assertSame('#ffffff', $palettes[0]['properties']['--mw-background-color']);
    }

    public function test_get_palettes_for_bootstrap_template(): void
    {
        $this->colorSchemes->registerPalette([
            'name' => 'Bootstrap Test',
            'mainColors' => ['#3498db', '#2ecc71', '#e74c3c', '#ffffff'],
            'properties' => [
                '--primaryColor' => '#3498db',
                '--links' => '#2ecc71',
                '--background' => '#ffffff',
            ],
        ]);

        $palettes = $this->service->getPalettesForTemplate('bootstrap');

        $this->assertCount(1, $palettes);
        // Should have both --mw-* and --bs-* vars
        $this->assertSame('#3498db', $palettes[0]['properties']['--mw-primary-color']);
        $this->assertSame('#3498db', $palettes[0]['properties']['--bs-primary']);
        $this->assertSame('#ffffff', $palettes[0]['properties']['--mw-background-color']);
        $this->assertSame('#ffffff', $palettes[0]['properties']['--bs-body-bg']);
    }

    public function test_get_style_packs_for_big_template(): void
    {
        $this->stylePacks->registerPack([
            'title' => 'Test Style Pack',
            'fieldType' => 'stylePack',
            'selectors' => [':root'],
            'fieldSettings' => [
                'styleProperties' => [
                    [
                        'label' => 'Test Style Pack',
                        'properties' => [
                            '--mw-primary-color' => '#ff0000',
                            '--mw-background-color' => '#ffffff',
                        ],
                    ],
                ],
            ],
        ], 'colors');

        $packs = $this->service->getStylePacksForTemplate('big', 'colors');

        $this->assertCount(1, $packs);
        $props = $packs[0]['fieldSettings']['styleProperties'][0]['properties'];
        // Identity mapping for Big
        $this->assertSame('#ff0000', $props['--mw-primary-color']);
        $this->assertSame('#ffffff', $props['--mw-background-color']);
    }

    public function test_get_style_packs_for_bootstrap_template(): void
    {
        $this->stylePacks->registerPack([
            'title' => 'BS Pack',
            'fieldType' => 'stylePack',
            'selectors' => [':root'],
            'fieldSettings' => [
                'styleProperties' => [
                    [
                        'label' => 'BS Pack',
                        'properties' => [
                            '--mw-primary-color' => '#3498db',
                            '--mw-background-color' => '#ffffff',
                        ],
                    ],
                ],
            ],
        ], 'colors');

        $packs = $this->service->getStylePacksForTemplate('bootstrap', 'colors');

        $this->assertCount(1, $packs);
        $props = $packs[0]['fieldSettings']['styleProperties'][0]['properties'];

        // Should have both --mw-* and --bs-*
        $this->assertSame('#3498db', $props['--mw-primary-color']);
        $this->assertSame('#3498db', $props['--bs-primary']);
        $this->assertSame('#ffffff', $props['--mw-background-color']);
        $this->assertSame('#ffffff', $props['--bs-body-bg']);
    }

    public function test_register_custom_adapter(): void
    {
        $customAdapter = new class extends \MicroweberPackages\Template\Services\DesignSystem\Adapters\TemplateVarsAdapter {
            public function templateName(): string { return 'custom'; }
            public function varPrefix(): string { return '--custom-'; }
            public function propertyMap(): array { return ['--mw-primary-color' => '--custom-primary']; }
            public function mapPaletteToVars(array $p): array { return $p; }
        };

        $this->service->registerAdapter($customAdapter);

        $this->assertContains('custom', $this->service->registeredAdapters());
        $adapter = $this->service->adapterFor('custom');
        $this->assertSame('custom', $adapter->templateName());
    }

    public function test_empty_palettes_for_template(): void
    {
        $palettes = $this->service->getPalettesForTemplate('big');
        $this->assertSame([], $palettes);
    }

    public function test_empty_style_packs_for_template(): void
    {
        $packs = $this->service->getStylePacksForTemplate('big', 'colors');
        $this->assertSame([], $packs);
    }

    public function test_full_integration_shared_assets(): void
    {
        // Load shared palettes and packs
        $this->colorSchemes->loadSharedPalettes();
        $this->stylePacks->loadSharedPacks();

        // Big template
        $bigPalettes = $this->service->getPalettesForTemplate('big');
        $this->assertNotEmpty($bigPalettes);
        foreach ($bigPalettes as $palette) {
            $this->assertNotEmpty($palette['name']);
            $this->assertNotEmpty($palette['mainColors']);
            $this->assertNotEmpty($palette['properties']);
            // All property keys should be in --mw-* namespace
            foreach (array_keys($palette['properties']) as $key) {
                $this->assertMatchesRegularExpression('/^--mw-|^--text-on-/', $key, "Property {$key} should be in --mw-* namespace for Big template");
            }
        }

        // Bootstrap template
        $bsPalettes = $this->service->getPalettesForTemplate('bootstrap');
        $this->assertNotEmpty($bsPalettes);
        foreach ($bsPalettes as $palette) {
            $hasBsVar = false;
            foreach (array_keys($palette['properties']) as $key) {
                if (str_starts_with($key, '--bs-')) {
                    $hasBsVar = true;
                    break;
                }
            }
            $this->assertTrue($hasBsVar, "Bootstrap palette should include --bs-* vars");
        }

        // Style packs
        $bigPacks = $this->service->getStylePacksForTemplate('big', 'colors');
        $this->assertNotEmpty($bigPacks);

        $bsPacks = $this->service->getStylePacksForTemplate('bootstrap', 'colors');
        $this->assertNotEmpty($bsPacks);
    }

    /**
     * The active template's OWN folder palettes
     * (Templates/<Name>/resources/assets/color-palettes.json) must be merged
     * into the result so a template never loses its bespoke catalog when the
     * shared registry loads. Big ships ~134 palettes there.
     *
     * Uses the real capitalized "Big" dir name (case-sensitive filesystem) so
     * the folder-merge path actually resolves, unlike the lowercase fixtures
     * above which deliberately do not match a real template dir.
     */
    public function test_get_palettes_merges_template_folder_palettes(): void
    {
        // Repo root: .../src/MicroweberPackages/Template/Tests/Unit/DesignSystem -> up 6
        $templatesDir = dirname(__DIR__, 6) . DIRECTORY_SEPARATOR . 'Templates';

        $file = $templatesDir . DIRECTORY_SEPARATOR . 'Big'
            . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'assets'
            . DIRECTORY_SEPARATOR . 'color-palettes.json';

        if (!is_file($file)) {
            $this->markTestSkipped('Big template color-palettes.json not present.');
        }

        $folderCount = count(json_decode((string) file_get_contents($file), true) ?: []);

        // Drive the folder-merge without booting the framework by overriding
        // the templates base dir.
        $service = new class ($this->colorSchemes, $this->stylePacks, $templatesDir) extends DesignSystemService {
            private string $dir;
            public function __construct($cs, $sp, string $dir)
            {
                parent::__construct($cs, $sp);
                $this->dir = $dir;
            }
            protected function templatesBaseDir(): ?string
            {
                return $this->dir;
            }
        };

        // Shared registry empty + Big folder has N -> result should equal N
        $palettes = $service->getPalettesForTemplate('Big');
        $this->assertCount($folderCount, $palettes);
        $this->assertGreaterThan(100, count($palettes), 'Big should keep its full bespoke palette catalog.');

        // Properties are still mapped to the --mw-* namespace via the adapter
        foreach (array_keys($palettes[0]['properties']) as $key) {
            $this->assertMatchesRegularExpression('/^--mw-|^--text-on-/', $key);
        }
    }

    /**
     * A template with no own color-palettes.json (e.g. Bootstrap) returns only
     * the shared catalog — no merge, no error.
     */
    public function test_get_palettes_without_folder_returns_shared_only(): void
    {
        $this->colorSchemes->registerPalette([
            'name' => 'Solo Shared',
            'mainColors' => ['#111', '#222', '#333', '#fff'],
            'properties' => ['--primaryColor' => '#111'],
        ]);

        // "Bootstrap" has no resources/assets/color-palettes.json
        $palettes = $this->service->getPalettesForTemplate('Bootstrap');
        $this->assertCount(1, $palettes);
        $this->assertSame('Solo Shared', $palettes[0]['name']);
    }
}