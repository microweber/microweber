<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-121 / AI-124 / TICKET-CT + TICKET-CU — config orphan audit
 * + PSR-4 strict audit regression coverage.
 *
 * Pins:
 *   - Both commands exist at the canonical paths.
 *   - Each is registered in MonitoringServiceProvider.
 *   - Each is exposed via `php artisan list` (functional sanity).
 *   - The TICKET-CT signature checks `config('foo.bar')` callsites.
 *   - The TICKET-CU signature mirrors composer.json's PSR-4 prefix
 *     map for the in-tree namespaces (App, Microweber,
 *     MicroweberPackages, Modules, Templates).
 *
 * Style after the cycle-52..120 contract tests (file-system reads only,
 * no DB touch).
 */
class ConfigOrphanAndPsr4AuditContractTest extends TestCase
{
    private const CT_CMD = 'src/MicroweberPackages/Monitoring/Console/Commands/ConfigOrphanAuditCommand.php';
    private const CU_CMD = 'src/MicroweberPackages/Monitoring/Console/Commands/Psr4StrictAuditCommand.php';
    private const PROVIDER = 'src/MicroweberPackages/Monitoring/Providers/MonitoringServiceProvider.php';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function ticket_ct_command_exists_with_canonical_signature(): void
    {
        $src = $this->read(self::CT_CMD);

        $this->assertStringContainsString(
            'class ConfigOrphanAuditCommand extends Command',
            $src,
            'ConfigOrphanAuditCommand must extend Illuminate\\Console\\Command'
        );
        $this->assertStringContainsString(
            'monitoring:config-orphan-audit',
            $src,
            'TICKET-CT signature must be `monitoring:config-orphan-audit`'
        );
        $this->assertStringContainsString(
            "config(",
            $src,
            'Command must scan for `config()` callsites'
        );
    }

    #[Test]
    public function ticket_cu_command_exists_with_canonical_signature(): void
    {
        $src = $this->read(self::CU_CMD);

        $this->assertStringContainsString(
            'class Psr4StrictAuditCommand extends Command',
            $src,
            'Psr4StrictAuditCommand must extend Illuminate\\Console\\Command'
        );
        $this->assertStringContainsString(
            'monitoring:psr4-strict-audit',
            $src,
            'TICKET-CU signature must be `monitoring:psr4-strict-audit`'
        );

        // Brief required: scan PSR-4 roots from composer.json.
        // Pin the 5 in-tree autoload prefixes.
        foreach ([
            "'App\\\\'",
            "'Microweber\\\\'",
            "'MicroweberPackages\\\\'",
            "'Modules\\\\'",
            "'Templates\\\\'",
        ] as $prefix) {
            $this->assertStringContainsString(
                $prefix,
                $src,
                "Psr4StrictAuditCommand must include the autoload prefix `{$prefix}` from composer.json"
            );
        }
    }

    #[Test]
    public function both_commands_registered_in_monitoring_provider(): void
    {
        $src = $this->read(self::PROVIDER);

        foreach ([
            'ConfigOrphanAuditCommand::class',
            'Psr4StrictAuditCommand::class',
        ] as $needle) {
            $this->assertStringContainsString(
                $needle,
                $src,
                "MonitoringServiceProvider must register `{$needle}`"
            );
        }
    }

    #[Test]
    public function artisan_list_exposes_both_commands(): void
    {
        $output = [];
        $exit = 0;
        exec('cd ' . escapeshellarg(base_path()) . ' && php artisan list 2>&1', $output, $exit);
        $this->assertSame(0, $exit, 'php artisan list must exit 0');

        $combined = implode("\n", $output);
        $this->assertStringContainsString(
            'monitoring:config-orphan-audit',
            $combined,
            '`php artisan list` must show monitoring:config-orphan-audit'
        );
        $this->assertStringContainsString(
            'monitoring:psr4-strict-audit',
            $combined,
            '`php artisan list` must show monitoring:psr4-strict-audit'
        );
    }
}
