<?php

declare(strict_types=1);

namespace MicroweberPackages\Console\Tests;

use Illuminate\Console\Command;
use MicroweberPackages\Console\Commands\ResetCommand;
use MicroweberPackages\Console\Commands\UpdateCommand;
use PHPUnit\Framework\Attributes\Test;

/**
 * Exercises the package's real console Command classes: they must be
 * autoloadable, extend the Laravel base Command, and resolve their signature
 * into the expected artisan command name.
 */
class CommandsTest extends TestCase
{
    #[Test]
    public function command_classes_are_autoloadable(): void
    {
        foreach ([
            UpdateCommand::class,
            ResetCommand::class,
            \MicroweberPackages\Console\Commands\AddLicenseKeyCommand::class,
            \MicroweberPackages\Console\Commands\ModuleCommand::class,
            \MicroweberPackages\Console\Commands\PackageInstallCommand::class,
        ] as $class) {
            $this->assertTrue(class_exists($class), "{$class} should be autoloadable.");
            $this->assertTrue(
                is_subclass_of($class, Command::class),
                "{$class} should extend the Laravel console Command base class."
            );
        }
    }

    #[Test]
    public function update_command_resolves_its_signature_name(): void
    {
        $command = new UpdateCommand();

        $this->assertInstanceOf(Command::class, $command);
        $this->assertSame('microweber:update', $command->getName());
    }

    #[Test]
    public function reset_command_resolves_its_signature_name(): void
    {
        $command = new ResetCommand();

        $this->assertSame('microweber:reset', $command->getName());
    }
}
