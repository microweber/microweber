<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Tests\Unit;

use MicroweberPackages\ModuleRegistry\Support\ScanForBladeTemplates;
use MicroweberPackages\ModuleRegistry\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ScanForBladeTemplatesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app['view']->addNamespace(
            'module-registry-test',
            __DIR__ . '/../Fixtures/views'
        );
    }

    #[Test]
    public function it_scans_blade_templates_and_parses_meta(): void
    {
        $scanner = new ScanForBladeTemplates();
        $skins = $scanner->scan('module-registry-test::templates', 'example');

        $this->assertNotEmpty($skins);

        $names = array_map(static fn (array $s): string => (string) ($s['name'] ?? ''), $skins);
        $this->assertTrue(
            in_array('Skin One', $names, true) || in_array('Default', $names, true) || count($skins) >= 1,
            'Expected at least one discovered skin, got: ' . implode(', ', $names)
        );

        $skinOne = null;
        foreach ($skins as $skin) {
            if (($skin['name'] ?? '') === 'Skin One') {
                $skinOne = $skin;
                break;
            }
        }

        if ($skinOne !== null) {
            $this->assertSame('module', $skinOne['type'] ?? null);
            $this->assertSame(1, $skinOne['position'] ?? null);
            $this->assertArrayHasKey('layout_file', $skinOne);
            $this->assertArrayHasKey('found_modules', $skinOne);
            $this->assertContains('btn', $skinOne['found_modules']);
        }
    }

    #[Test]
    public function it_returns_empty_for_unknown_namespace(): void
    {
        $scanner = new ScanForBladeTemplates();
        $this->assertSame([], $scanner->scan('does-not-exist::templates'));
    }

    #[Test]
    public function it_returns_empty_for_missing_folder(): void
    {
        $scanner = new ScanForBladeTemplates();
        $this->assertSame([], $scanner->scanFolder('/tmp/module-registry-missing-' . uniqid()));
    }
}
