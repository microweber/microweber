<?php

namespace MicroweberPackages\Template\Tests\Unit\DesignSystem;

use MicroweberPackages\Template\Services\DesignSystem\StylePackRegistry;
use PHPUnit\Framework\TestCase;

class StylePackRegistryTest extends TestCase
{
    protected StylePackRegistry $registry;
    protected string $fixtureDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registry = new StylePackRegistry();
        $this->fixtureDir = sys_get_temp_dir() . '/mw_stylepack_test_' . uniqid();
        mkdir($this->fixtureDir . '/colors', 0755, true);
    }

    protected function tearDown(): void
    {
        if (is_dir($this->fixtureDir . '/colors')) {
            array_map('unlink', glob($this->fixtureDir . '/colors/*'));
            rmdir($this->fixtureDir . '/colors');
        }
        if (is_dir($this->fixtureDir)) {
            array_map('unlink', glob($this->fixtureDir . '/*'));
            @rmdir($this->fixtureDir);
        }
        parent::tearDown();
    }

    public function test_empty_registry(): void
    {
        $this->assertSame([], $this->registry->all());
        $this->assertSame([], $this->registry->categories());
    }

    public function test_register_pack(): void
    {
        $pack = $this->makePack('Test Pack');
        $this->registry->registerPack($pack, 'colors');

        $this->assertCount(1, $this->registry->getByCategory('colors'));
        $this->assertContains('colors', $this->registry->categories());
    }

    public function test_register_deduplicates_by_title(): void
    {
        $pack1 = $this->makePack('Same', ['--mw-primary-color' => '#aaa']);
        $pack2 = $this->makePack('Same', ['--mw-primary-color' => '#bbb']);

        $this->registry->registerPack($pack1, 'colors');
        $this->registry->registerPack($pack2, 'colors');

        $packs = $this->registry->getByCategory('colors');
        $this->assertCount(1, $packs);
        $this->assertSame('#bbb', $packs[0]['fieldSettings']['styleProperties'][0]['properties']['--mw-primary-color']);
    }

    public function test_find_by_title(): void
    {
        $this->registry->registerPack($this->makePack('Alpha'), 'colors');
        $this->registry->registerPack($this->makePack('Beta'), 'colors');

        $found = $this->registry->findByTitle('Beta', 'colors');
        $this->assertNotNull($found);
        $this->assertSame('Beta', $found['title']);

        $this->assertNull($this->registry->findByTitle('Gamma', 'colors'));
    }

    public function test_extract_properties(): void
    {
        $pack = $this->makePack('Extract', ['--mw-primary-color' => '#123', '--mw-body-color' => '#456']);
        $props = $this->registry->extractProperties($pack);

        $this->assertSame('#123', $props['--mw-primary-color']);
        $this->assertSame('#456', $props['--mw-body-color']);
    }

    public function test_load_packs_from_file(): void
    {
        $file = $this->fixtureDir . '/colors/test.json';
        file_put_contents($file, json_encode($this->makePackFile('File Pack')));

        $this->registry->loadPacksFromFile($file, 'colors');

        $this->assertCount(1, $this->registry->getByCategory('colors'));
        $this->assertSame('File Pack', $this->registry->getByCategory('colors')[0]['title']);
    }

    public function test_load_packs_from_directory(): void
    {
        file_put_contents(
            $this->fixtureDir . '/colors/p1.json',
            json_encode($this->makePackFile('Pack 1'))
        );
        file_put_contents(
            $this->fixtureDir . '/colors/p2.json',
            json_encode($this->makePackFile('Pack 2'))
        );

        $this->registry->loadPacksFromDirectory($this->fixtureDir . '/colors', 'colors');

        $this->assertCount(2, $this->registry->getByCategory('colors'));
    }

    public function test_load_from_nonexistent_file(): void
    {
        $this->registry->loadPacksFromFile('/nonexistent/file.json', 'colors');
        $this->assertSame([], $this->registry->getByCategory('colors'));
    }

    public function test_load_from_nonexistent_directory(): void
    {
        $this->registry->loadPacksFromDirectory('/nonexistent/dir', 'colors');
        $this->assertSame([], $this->registry->getByCategory('colors'));
    }

    public function test_load_shared_packs(): void
    {
        $this->registry->loadSharedPacks();
        $allPacks = $this->registry->all();

        $this->assertNotEmpty($allPacks);
        $this->assertContains('colors', $this->registry->categories());
    }

    public function test_load_shared_packs_idempotent(): void
    {
        $this->registry->loadSharedPacks();
        $count1 = count($this->registry->getByCategory('colors'));

        $this->registry->loadSharedPacks();
        $count2 = count($this->registry->getByCategory('colors'));

        $this->assertSame($count1, $count2);
    }

    public function test_multiple_categories(): void
    {
        $this->registry->registerPack($this->makePack('Color Pack'), 'colors');
        $this->registry->registerPack($this->makePack('Font Pack'), 'fonts');

        $this->assertCount(1, $this->registry->getByCategory('colors'));
        $this->assertCount(1, $this->registry->getByCategory('fonts'));
        $this->assertCount(2, $this->registry->categories());
    }

    public function test_get_nonexistent_category(): void
    {
        $this->assertSame([], $this->registry->getByCategory('nonexistent'));
    }

    public function test_reset(): void
    {
        $this->registry->registerPack($this->makePack('Test'), 'colors');
        $this->assertNotEmpty($this->registry->all());

        $this->registry->reset();
        $this->assertSame([], $this->registry->all());
    }

    public function test_is_valid_pack(): void
    {
        $this->assertTrue($this->registry->isValidPack($this->makePack('Valid')));
        $this->assertFalse($this->registry->isValidPack(['title' => 'No fieldSettings']));
        $this->assertFalse($this->registry->isValidPack([]));
        $this->assertFalse($this->registry->isValidPack([
            'title' => 'Empty styleProperties',
            'fieldSettings' => ['styleProperties' => []],
        ]));
    }

    public function test_rejects_invalid_packs(): void
    {
        $this->registry->registerPack(['invalid' => true], 'colors');
        $this->assertSame([], $this->registry->getByCategory('colors'));
    }

    // --- Helpers ---

    private function makePack(string $title, array $properties = null): array
    {
        return [
            'title' => $title,
            'fieldType' => 'stylePack',
            'selectors' => [':root'],
            'fieldSettings' => [
                'styleProperties' => [
                    [
                        'label' => $title,
                        'properties' => $properties ?? [
                            '--mw-primary-color' => '#ff0000',
                            '--mw-background-color' => '#ffffff',
                            '--mw-body-color' => '#000000',
                        ],
                    ],
                ],
            ],
        ];
    }

    private function makePackFile(string $title, array $properties = null): array
    {
        return ['settings' => [$this->makePack($title, $properties)]];
    }
}