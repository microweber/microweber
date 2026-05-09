<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-104 / AI-111 / TICKET-BQ — SETUP.md regression coverage.
 *
 * Pins:
 *   - SETUP.md exists at the repo root.
 *   - It documents every section the brief called out: PHP/Node
 *     version requirements, local setup steps, test commands,
 *     same-origin live-edit note, Telescope note, app-key rotation
 *     procedure.
 *
 * Style after the cycle-52..103 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class SetupMdContractTest extends TestCase
{
    private const SETUP = 'SETUP.md';

    private function read(): string
    {
        $path = base_path(self::SETUP);
        $this->assertFileExists($path, self::SETUP . ' must exist at repo root');
        return file_get_contents($path);
    }

    #[Test]
    public function setup_md_documents_php_and_node_versions(): void
    {
        $src = $this->read();

        $this->assertMatchesRegularExpression(
            '/\\*\\*PHP\\*\\*[\\s\\S]{0,200}\\^?8\\.3/',
            $src,
            'SETUP.md: must document PHP version requirement (^8.3)'
        );
        $this->assertMatchesRegularExpression(
            '/\\*\\*Node\\*\\*[\\s\\S]{0,80}22\\.x?/',
            $src,
            'SETUP.md: must document Node version requirement (22.x)'
        );
    }

    #[Test]
    public function setup_md_documents_local_setup_steps(): void
    {
        $src = $this->read();

        // The brief asked for "local setup steps". Pin a few specific
        // commands that must appear so a fresh checkout can follow
        // along.
        foreach ([
            'composer install',
            'npm ci',
            'npm run build',
            'cp .env.example .env',
            'php artisan key:generate',
            'php artisan migrate',
        ] as $cmd) {
            $this->assertStringContainsString(
                $cmd,
                $src,
                "SETUP.md: must include the `{$cmd}` step"
            );
        }
    }

    #[Test]
    public function setup_md_documents_test_commands(): void
    {
        $src = $this->read();

        $this->assertStringContainsString(
            'vendor/bin/phpunit',
            $src,
            'SETUP.md: must document the phpunit test command'
        );
        $this->assertStringContainsString(
            'php artisan dusk',
            $src,
            'SETUP.md: must document the Dusk browser-test command'
        );
        $this->assertStringContainsString(
            'scripts/grep-gate.sh',
            $src,
            'SETUP.md: must document the AI-113 grep-gate command'
        );
    }

    #[Test]
    public function setup_md_documents_same_origin_live_edit(): void
    {
        $src = $this->read();

        $this->assertMatchesRegularExpression(
            '/##\\s+Same-origin Live-Edit/',
            $src,
            'SETUP.md: must contain a "Same-origin Live-Edit" section'
        );

        // The section must explain the postMessage handshake (so devs
        // setting up cross-origin reverse proxies know to fix APP_URL).
        $this->assertStringContainsString(
            'postMessage',
            $src,
            'SETUP.md: same-origin section must mention the postMessage handshake'
        );
    }

    #[Test]
    public function setup_md_documents_telescope(): void
    {
        $src = $this->read();

        $this->assertMatchesRegularExpression(
            '/##\\s+Telescope/',
            $src,
            'SETUP.md: must contain a "Telescope" section'
        );

        // Must explicitly warn about production exposure.
        $this->assertMatchesRegularExpression(
            '/Never[\\s\\S]{0,80}APP_ENV=production/',
            $src,
            'SETUP.md: Telescope section must warn against APP_ENV=production'
        );
    }

    #[Test]
    public function setup_md_documents_app_key_rotation_procedure(): void
    {
        $src = $this->read();

        $this->assertMatchesRegularExpression(
            '/##\\s+App-key rotation/',
            $src,
            'SETUP.md: must contain an "App-key rotation procedure" section'
        );

        // The procedure must mention APP_PREVIOUS_KEYS as the
        // transition mechanism (without it, rotating the key strands
        // existing encrypted rows).
        $this->assertStringContainsString(
            'APP_PREVIOUS_KEYS',
            $src,
            'SETUP.md: app-key rotation must mention APP_PREVIOUS_KEYS for the transition window'
        );

        // And must mention php artisan key:generate.
        $this->assertStringContainsString(
            'php artisan key:generate',
            $src,
            'SETUP.md: app-key rotation must mention `php artisan key:generate`'
        );
    }
}
