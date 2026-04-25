<?php

namespace MicroweberPackages\Install\tests;

use Illuminate\Support\Facades\Artisan;
use MicroweberPackages\Install\Console\Commands\InstallCommand;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * The CLI installer is the only supported headless install path
 * (Docker, CI, scripted multi-domain provisioning) and the docs at
 * `docs/installation.md` advertise the exact option names. A
 * regression here — option dropped from the signature, command
 * un-registered from the kernel — would make every scripted install
 * silently break on the next deploy.
 *
 * The two sibling guards below are deliberately lightweight: they
 * never *run* the installer (that would mutate the connected
 * database) — they only prove the artisan command is reachable and
 * the public option set still matches the documented contract.
 */
class InstallCommandRegistrationTest extends TestCase
{
    #[Test]
    public function microweber_install_command_is_registered(): void
    {
        $commands = Artisan::all();

        $this->assertArrayHasKey(
            'microweber:install',
            $commands,
            'microweber:install must remain registered in the artisan command '
            . 'list — every scripted/Docker/CI install in docs/installation.md '
            . 'depends on this exact name.'
        );
        $this->assertInstanceOf(
            InstallCommand::class,
            $commands['microweber:install'],
            'microweber:install must resolve to the documented InstallCommand '
            . 'class — a different binding here would mean the option signature '
            . 'and env-var fallbacks documented in docs/installation.md no '
            . 'longer apply.'
        );
    }

    #[Test]
    public function microweber_install_exposes_every_documented_option(): void
    {
        $definition = (new InstallCommand(app(\MicroweberPackages\Install\Http\Controllers\InstallController::class)))
            ->getDefinition();

        $documented = [
            'db-host',
            'db-name',
            'db-username',
            'db-password',
            'db-driver',
            'db-prefix',
            'email',
            'username',
            'password',
            'default-content',
            'template',
            'config-only',
            'language',
            'app-url',
            'app-debug',
        ];

        foreach ($documented as $option) {
            $this->assertTrue(
                $definition->hasOption($option),
                "microweber:install must expose --{$option} — it's documented in "
                . 'docs/installation.md and removing it would break every '
                . 'scripted install that passes that flag.'
            );
        }
    }
}
