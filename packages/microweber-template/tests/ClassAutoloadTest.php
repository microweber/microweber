<?php

declare(strict_types=1);

namespace MicroweberPackages\Template\StandaloneTests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Standalone smoke test for the microweber-template package.
 *
 * This is a deeply-coupled application-tier package: its service provider
 * registers sibling CMS providers (template-fonts, template-custom-css,
 * minifier) that are not declared composer dependencies, so a full Testbench
 * boot cannot run in isolation. This suite verifies the package's key classes
 * are PSR-4 autoloadable from the package's own declared dependencies.
 */
class ClassAutoloadTest extends TestCase
{
    /**
     * @return array<string, array{0: class-string}>
     */
    public static function keyClasses(): array
    {
        return [
            'TemplateManagerServiceProvider' => [\MicroweberPackages\Template\TemplateManagerServiceProvider::class],
            'TemplateManager' => [\MicroweberPackages\Template\TemplateManager::class],
            'LayoutsManager' => [\MicroweberPackages\Template\LayoutsManager::class],
        ];
    }

    #[Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('keyClasses')]
    public function key_class_is_autoloadable(string $class): void
    {
        $this->assertTrue(
            class_exists($class),
            "Expected class {$class} to be autoloadable."
        );
    }
}
