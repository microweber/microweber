<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-103 / AI-113 / TICKET-CP — CI grep-gate regression coverage.
 *
 * Pins:
 *   - `scripts/grep-gate.sh` exists and is the single source-of-truth
 *     for the two forbidden patterns:
 *        1. inline `style="...background-image: url({{ thumbnail|asset...`
 *        2. inline `onclick="...{{...`
 *   - The CI workflow `.github/workflows/cicd-pipeline.yml` invokes
 *     the script (no duplicated regex logic).
 *   - The waivers file `scripts/grep-gate-waivers.txt` exists and
 *     uses the documented `<path>:<lineno>` line format.
 *   - Running the gate against the current tree returns 0 (the gate
 *     is functional today — existing offences are either fixed or
 *     waivered). This is the "Existing clean files must still pass"
 *     acceptance criterion from the brief.
 *
 * Style after the cycle-52..102 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class Ai113CiGrepGateContractTest extends TestCase
{
    private const SCRIPT  = 'scripts/grep-gate.sh';
    private const WAIVERS = 'scripts/grep-gate-waivers.txt';
    private const CI_YML  = '.github/workflows/cicd-pipeline.yml';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function grep_gate_script_exists_and_is_executable(): void
    {
        $path = base_path(self::SCRIPT);
        $this->assertFileExists($path, self::SCRIPT . ' must exist');
        $this->assertTrue(
            is_executable($path),
            self::SCRIPT . ' must be executable (chmod +x)'
        );
    }

    #[Test]
    public function grep_gate_script_blocks_both_forbidden_patterns(): void
    {
        $src = $this->read(self::SCRIPT);

        // Pattern 1: inline style background-image url + Blade interpolation.
        $this->assertStringContainsString(
            'background-image:',
            $src,
            self::SCRIPT . ': must mention `background-image:` in PATTERN_1'
        );
        $this->assertStringContainsString(
            'thumbnail|asset',
            $src,
            self::SCRIPT . ': PATTERN_1 must match thumbnail() OR asset() helper interpolations'
        );

        // Pattern 2: inline onclick + Blade interpolation.
        $this->assertMatchesRegularExpression(
            '/PATTERN_2=.*onclick="\\[.*\\]\\*.*\\\\\\{\\\\\\{/',
            $src,
            self::SCRIPT . ': PATTERN_2 must match `onclick="...{{...`'
        );
    }

    #[Test]
    public function ci_workflow_invokes_grep_gate_script(): void
    {
        $src = $this->read(self::CI_YML);

        // The CI step must call the script (single source-of-truth).
        $this->assertStringContainsString(
            'bash scripts/grep-gate.sh --all',
            $src,
            self::CI_YML . ': must invoke `bash scripts/grep-gate.sh --all`'
        );

        // The step name must reference AI-113 so the CI run summary is
        // grep-able.
        $this->assertMatchesRegularExpression(
            '/-\\s*name:\\s*Grep-gate forbidden Blade patterns \\(AI-113/',
            $src,
            self::CI_YML . ': step must be named with the AI-113 ticket reference'
        );
    }

    #[Test]
    public function waivers_file_exists_with_documented_format(): void
    {
        $src = $this->read(self::WAIVERS);

        $this->assertStringContainsString(
            'AI-113 / TICKET-CP grep-gate waivers',
            $src,
            self::WAIVERS . ': must carry the AI-113 audit-trail header'
        );
        $this->assertStringContainsString(
            '<path>:<line-number>',
            $src,
            self::WAIVERS . ': must document the `<path>:<lineno>` line format'
        );

        // At least one waiver entry exists (the cycle-103 baseline of
        // existing admin-side legacy callsites).
        $this->assertMatchesRegularExpression(
            '/^[^#\\s][^:]+:\\d+/m',
            $src,
            self::WAIVERS . ': must contain at least one `path:lineno` waiver entry'
        );
    }

    #[Test]
    public function grep_gate_runs_clean_against_current_tree(): void
    {
        // The "Existing clean files must still pass" acceptance
        // criterion — running the gate against today's tree must
        // exit 0 (because new violations would also fail this test
        // and tell the dev to either fix them or add a waiver).
        $script = base_path(self::SCRIPT);
        exec("bash {$script} --all 2>&1", $output, $exitCode);
        $this->assertSame(
            0,
            $exitCode,
            "scripts/grep-gate.sh --all returned non-zero. Output:\n" . implode("\n", $output)
        );
    }
}
