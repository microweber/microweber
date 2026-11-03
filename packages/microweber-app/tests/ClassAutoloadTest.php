<?php

declare(strict_types=1);

namespace MicroweberPackages\App\StandaloneTests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Standalone smoke test for the microweber-app package.
 *
 * This is the deeply-coupled application-tier package: its service providers
 * boot the whole Microweber CMS runtime and its autoloaded helper files
 * reference services that are not declared composer dependencies, so a full
 * Testbench boot cannot run in isolation. This suite verifies the package's
 * key classes are PSR-4 autoloadable (and the autoloaded function files load)
 * from the package's own declared dependencies.
 */
class ClassAutoloadTest extends TestCase
{
    /**
     * @return array<string, array{0: class-string}>
     */
    public static function keyClasses(): array
    {
        return [
            'Application' => [\MicroweberPackages\App\Application::class],
            'MicroweberServiceProvider' => [\MicroweberPackages\App\Providers\MicroweberServiceProvider::class],
            'AppServiceProvider' => [\MicroweberPackages\App\Providers\AppServiceProvider::class],
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

    #[Test]
    public function autoloaded_helper_functions_are_defined(): void
    {
        // These are declared in the composer `autoload.files` helper scripts,
        // which are executed when vendor/autoload.php is loaded.
        $this->assertTrue(function_exists('array_to_module_params'));
        $this->assertTrue(function_exists('mw'));
    }
}
