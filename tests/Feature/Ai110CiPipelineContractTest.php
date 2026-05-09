<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-110 / AI-110 / TICKET-BS + TICKET-BR — CI pipeline regression
 * coverage.
 *
 * Pins:
 *   - The CI workflow runs `php artisan migrate --pretend` so schema
 *     drift / broken migrations fail the build BEFORE the
 *     integration-tests stage spins up MySQL (TICKET-BS).
 *   - The unit-tests stage counts the suite size and emits a notice
 *     when a suite exceeds 100 tests, prompting the parallel-runner
 *     evaluation called out by TICKET-BR.
 *   - tests/README.md documents the hard constraints (no
 *     RunInSeparateProcess, no DatabaseTransactions, no parallel
 *     runs) so future contributors know the limits before
 *     attempting `--parallel`.
 *
 * Style after the cycle-52..109 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class Ai110CiPipelineContractTest extends TestCase
{
    private const CI_YML  = '.github/workflows/cicd-pipeline.yml';
    private const TESTS_README = 'tests/README.md';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function ci_pipeline_runs_migrate_pretend_smoke(): void
    {
        $src = $this->read(self::CI_YML);

        $this->assertStringContainsString(
            'Migrate --pretend smoke check (AI-110 / TICKET-BS)',
            $src,
            self::CI_YML . ': must contain the migrate-pretend step name'
        );
        $this->assertStringContainsString(
            'php artisan migrate --pretend',
            $src,
            self::CI_YML . ': must invoke `php artisan migrate --pretend`'
        );
    }

    #[Test]
    public function ci_pipeline_counts_test_suite_size(): void
    {
        $src = $this->read(self::CI_YML);

        $this->assertStringContainsString(
            'Count tests in ${{ matrix.test-suite }} suite',
            $src,
            self::CI_YML . ': must count the test-suite size before running'
        );

        // The threshold check from the brief (>100 tests).
        $this->assertMatchesRegularExpression(
            '/if\\s*\\[\\s*"\\$COUNT"\\s*-gt\\s*100/',
            $src,
            self::CI_YML . ': must compare the count against the 100-test threshold'
        );

        // The notice mentions the parallel-runner consideration.
        $this->assertStringContainsString(
            'AI-110 / TICKET-BR',
            $src,
            self::CI_YML . ': must reference TICKET-BR in the notice'
        );
    }

    #[Test]
    public function tests_readme_documents_isolation_constraints(): void
    {
        $src = $this->read(self::TESTS_README);

        // Each of the 6 hard constraints from project-memory
        // `feedback_testing` must be documented.
        foreach ([
            'No `RunInSeparateProcess`',
            'No `DatabaseTransactions`',
            'No parallel runs',
            'kill the previous test run',
            'microweber_testing',
            '512MB',
        ] as $needle) {
            $this->assertStringContainsString(
                $needle,
                $src,
                "tests/README.md: must document `{$needle}` constraint"
            );
        }
    }
}
