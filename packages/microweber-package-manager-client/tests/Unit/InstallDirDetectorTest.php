<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Tests\Unit;

use MicroweberPackages\PackageManagerClient\InstallDirDetector;
use MicroweberPackages\PackageManagerClient\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class InstallDirDetectorTest extends TestCase
{
    private string $base;
    private InstallDirDetector $detector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->base = sys_get_temp_dir() . '/mw-pmc-detect-' . uniqid('', true);
        mkdir($this->base . '/Modules', 0777, true);
        mkdir($this->base . '/Templates', 0777, true);

        $this->detector = new InstallDirDetector([
            'base_path' => $this->base,
            'modules_path' => 'Modules',
            'templates_path' => 'Templates',
        ]);
    }

    protected function tearDown(): void
    {
        $this->removeTree($this->base);
        parent::tearDown();
    }

    #[Test]
    public function it_detects_microweber_module(): void
    {
        $target = $this->detector->detect([
            'name' => 'microweber-modules/sample-hello',
            'type' => 'microweber-module',
            'target-dir' => 'SampleHello',
        ]);

        $this->assertSame('microweber-module', $target->type);
        $this->assertSame('SampleHello', $target->directory);
        $this->assertSame('Modules/SampleHello', $target->relativePath);
        $this->assertSame(
            $this->base . DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . 'SampleHello',
            $target->absolutePath
        );
    }

    #[Test]
    public function it_detects_microweber_template(): void
    {
        $target = $this->detector->detect([
            'name' => 'microweber-templates/sample-theme',
            'type' => 'microweber-template',
            'target-dir' => 'SampleTheme',
        ]);

        $this->assertSame('microweber-template', $target->type);
        $this->assertSame('SampleTheme', $target->directory);
        $this->assertSame('Templates/SampleTheme', $target->relativePath);
        $this->assertStringContainsString('Templates', $target->absolutePath);
    }

    #[Test]
    public function it_detects_nwidart_laravel_module(): void
    {
        $target = $this->detector->detect([
            'name' => 'acme/sample-nwidart',
            'type' => 'laravel-module',
            'extra' => [
                'laravel-module' => ['name' => 'SampleNwidart'],
            ],
        ]);

        $this->assertSame('laravel-module', $target->type);
        $this->assertSame('SampleNwidart', $target->directory);
        $this->assertSame('Modules/SampleNwidart', $target->relativePath);
    }

    #[Test]
    public function it_detects_nwidart_from_autoload_psr4(): void
    {
        $target = $this->detector->detect([
            'name' => 'acme/blog',
            'type' => 'library',
            'autoload' => [
                'psr-4' => [
                    'Modules\\Blog\\' => '',
                ],
            ],
        ]);

        $this->assertSame('laravel-module', $target->type);
        $this->assertSame('Blog', $target->directory);
    }

    #[Test]
    public function it_derives_studly_directory_from_name(): void
    {
        $dir = $this->detector->resolveDirectory([
            'name' => 'microweber-modules/my-cool-module',
            'type' => 'microweber-module',
        ]);

        $this->assertSame('MyCool', $dir); // strips -module suffix then studly → MyCool
    }

    #[Test]
    public function it_rejects_path_traversal_in_target_dir(): void
    {
        $this->expectException(\MicroweberPackages\PackageManagerClient\Exceptions\PackageManagerException::class);

        $this->detector->detect([
            'name' => 'evil/pkg',
            'type' => 'microweber-module',
            'target-dir' => '../../etc',
        ]);
    }

    private function removeTree(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = scandir($dir) ?: [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            is_dir($path) ? $this->removeTree($path) : @unlink($path);
        }
        @rmdir($dir);
    }
}
