<?php

namespace MicroweberPackages\Template\Tests\Unit\DesignSystem;

use MicroweberPackages\Template\Services\DesignSystem\ColorSchemesRegistry;
use PHPUnit\Framework\TestCase;

class ColorSchemesRegistryTest extends TestCase
{
    protected ColorSchemesRegistry $registry;
    protected string $fixtureDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registry = new ColorSchemesRegistry();
        $this->fixtureDir = sys_get_temp_dir() . '/mw_design_test_' . uniqid();
        mkdir($this->fixtureDir, 0755, true);
    }

    protected function tearDown(): void
    {
        // Clean up fixtures
        if (is_dir($this->fixtureDir)) {
            array_map('unlink', glob($this->fixtureDir . '/*'));
            rmdir($this->fixtureDir);
        }
        parent::tearDown();
    }

    public function test_empty_registry_returns_empty_array(): void
    {
        $this->assertSame([], $this->registry->all());
    }

    public function test_register_palette(): void
    {
        $palette = $this->makePalette('Test Palette');
        $this->registry->registerPalette($palette);

        $this->assertCount(1, $this->registry->all());
        $this->assertSame('Test Palette', $this->registry->all()[0]['name']);
    }

    public function test_register_deduplicates_by_name(): void
    {
        $palette1 = $this->makePalette('Amber', ['--primaryColor' => '#aaa']);
        $palette2 = $this->makePalette('Amber', ['--primaryColor' => '#bbb']);

        $this->registry->registerPalette($palette1);
        $this->registry->registerPalette($palette2);

        $this->assertCount(1, $this->registry->all());
        $this->assertSame('#bbb', $this->registry->all()[0]['properties']['--primaryColor']);
    }

    public function test_find_by_name(): void
    {
        $this->registry->registerPalette($this->makePalette('Alpha'));
        $this->registry->registerPalette($this->makePalette('Beta'));

        $found = $this->registry->findByName('Beta');
        $this->assertNotNull($found);
        $this->assertSame('Beta', $found['name']);

        $this->assertNull($this->registry->findByName('Gamma'));
    }

    public function test_load_palettes_from_file(): void
    {
        $file = $this->fixtureDir . '/palettes.json';
        $data = [
            $this->makePalette('File Palette 1'),
            $this->makePalette('File Palette 2'),
        ];
        file_put_contents($file, json_encode($data));

        $this->registry->loadPalettesFromFile($file);

        $this->assertCount(2, $this->registry->all());
        $this->assertSame('File Palette 1', $this->registry->all()[0]['name']);
    }

    public function test_load_from_nonexistent_file_does_not_error(): void
    {
        $this->registry->loadPalettesFromFile('/nonexistent/path.json');
        $this->assertSame([], $this->registry->all());
    }

    public function test_load_from_invalid_json_does_not_error(): void
    {
        $file = $this->fixtureDir . '/bad.json';
        file_put_contents($file, 'NOT JSON');

        $this->registry->loadPalettesFromFile($file);
        $this->assertSame([], $this->registry->all());
    }

    public function test_load_palettes_from_directory(): void
    {
        file_put_contents(
            $this->fixtureDir . '/p1.json',
            json_encode([$this->makePalette('Dir Palette 1')])
        );
        file_put_contents(
            $this->fixtureDir . '/p2.json',
            json_encode([$this->makePalette('Dir Palette 2')])
        );

        $this->registry->loadPalettesFromDirectory($this->fixtureDir);

        $this->assertCount(2, $this->registry->all());
    }

    public function test_load_from_nonexistent_directory_does_not_error(): void
    {
        $this->registry->loadPalettesFromDirectory('/nonexistent/dir');
        $this->assertSame([], $this->registry->all());
    }

    public function test_load_shared_palettes(): void
    {
        $this->registry->loadSharedPalettes();
        $palettes = $this->registry->all();

        $this->assertNotEmpty($palettes);
        foreach ($palettes as $palette) {
            $this->assertTrue($this->registry->isValidPalette($palette));
        }
    }

    public function test_load_shared_palettes_idempotent(): void
    {
        $this->registry->loadSharedPalettes();
        $count1 = count($this->registry->all());

        $this->registry->loadSharedPalettes();
        $count2 = count($this->registry->all());

        $this->assertSame($count1, $count2);
    }

    public function test_get_property_names(): void
    {
        $this->registry->registerPalette(
            $this->makePalette('A', ['--primaryColor' => '#000', '--links' => '#111'])
        );
        $this->registry->registerPalette(
            $this->makePalette('B', ['--primaryColor' => '#222', '--background' => '#333'])
        );

        $names = $this->registry->getPropertyNames();
        $this->assertContains('--primaryColor', $names);
        $this->assertContains('--links', $names);
        $this->assertContains('--background', $names);
    }

    public function test_reset_clears_everything(): void
    {
        $this->registry->registerPalette($this->makePalette('X'));
        $this->assertCount(1, $this->registry->all());

        $this->registry->reset();
        $this->assertSame([], $this->registry->all());
    }

    public function test_is_valid_palette(): void
    {
        $this->assertTrue($this->registry->isValidPalette($this->makePalette('Valid')));
        $this->assertFalse($this->registry->isValidPalette(['name' => 'Missing mainColors']));
        $this->assertFalse($this->registry->isValidPalette(['mainColors' => ['#aaa']]));
        $this->assertFalse($this->registry->isValidPalette(['name' => 'X', 'mainColors' => ['#aaa']]));
    }

    public function test_rejects_invalid_palettes(): void
    {
        $this->registry->registerPalette(['invalid' => true]);
        $this->assertSame([], $this->registry->all());
    }

    // --- Helpers ---

    private function makePalette(string $name, array $properties = null): array
    {
        return [
            'name' => $name,
            'mainColors' => ['#ff0000', '#00ff00', '#0000ff', '#ffffff'],
            'properties' => $properties ?? [
                '--primaryColor' => '#ff0000',
                '--links' => '#00ff00',
                '--background' => '#ffffff',
            ],
        ];
    }
}