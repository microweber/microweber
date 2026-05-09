<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-117 / AI-120 / TICKET-BN — boot-time query audit command
 * regression coverage.
 *
 * Pins:
 *   - The command class exists at the canonical path.
 *   - Its signature is `monitoring:boot-query-audit` with the
 *     three documented options (--route, --threshold, --top, --no-warmup).
 *   - It uses `DB::enableQueryLog()` (the brief's required mechanism).
 *   - It exposes a brief-faithful threshold default of 50.
 *   - It is registered in MonitoringServiceProvider so
 *     `php artisan list` exposes it.
 *
 * Style after the cycle-52..116 contract tests (file-system reads only,
 * no DB touch).
 */
class BootQueryAuditCommandContractTest extends TestCase
{
    private const COMMAND  = 'src/MicroweberPackages/Monitoring/Console/Commands/BootQueryAuditCommand.php';
    private const PROVIDER = 'src/MicroweberPackages/Monitoring/Providers/MonitoringServiceProvider.php';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function command_exists_with_canonical_signature(): void
    {
        $src = $this->read(self::COMMAND);

        $this->assertStringContainsString(
            'class BootQueryAuditCommand extends Command',
            $src,
            'Command class must extend Illuminate\\Console\\Command'
        );

        $this->assertStringContainsString(
            "monitoring:boot-query-audit",
            $src,
            'Command signature must be `monitoring:boot-query-audit`'
        );

        // Required options from the brief.
        foreach (['--route', '--threshold', '--top', '--no-warmup'] as $opt) {
            $this->assertStringContainsString(
                ltrim($opt, '-'),
                $src,
                "Command must declare option `{$opt}`"
            );
        }
    }

    #[Test]
    public function command_uses_db_enable_query_log_per_brief(): void
    {
        $src = $this->read(self::COMMAND);

        $this->assertStringContainsString(
            'DB::enableQueryLog()',
            $src,
            'Command must call DB::enableQueryLog() (the brief-required mechanism)'
        );
        $this->assertStringContainsString(
            'DB::getQueryLog()',
            $src,
            'Command must call DB::getQueryLog() to harvest the captured queries'
        );
    }

    #[Test]
    public function command_default_threshold_is_50(): void
    {
        $src = $this->read(self::COMMAND);

        // Brief says target <50 warm-cache queries.
        $this->assertMatchesRegularExpression(
            '/--threshold=50\b/',
            $src,
            'Command default threshold must be 50 (per the brief target)'
        );
    }

    #[Test]
    public function command_is_registered_in_monitoring_provider(): void
    {
        $src = $this->read(self::PROVIDER);

        $this->assertStringContainsString(
            'BootQueryAuditCommand::class',
            $src,
            'MonitoringServiceProvider must register BootQueryAuditCommand'
        );

        $this->assertStringContainsString(
            "\$this->app->runningInConsole()",
            $src,
            'MonitoringServiceProvider must gate the commands() call on runningInConsole()'
        );
    }

    #[Test]
    public function artisan_list_exposes_the_command(): void
    {
        // End-to-end: kick artisan list and confirm the command is
        // discoverable. This is the "manual runbook step" Acceptance.
        $output = [];
        $exit = 0;
        exec('cd ' . escapeshellarg(base_path()) . ' && php artisan list 2>&1', $output, $exit);
        $this->assertSame(0, $exit, 'php artisan list must exit 0');

        $combined = implode("\n", $output);
        $this->assertStringContainsString(
            'monitoring:boot-query-audit',
            $combined,
            '`php artisan list` must show the new command (provider registration sanity)'
        );
    }
}
