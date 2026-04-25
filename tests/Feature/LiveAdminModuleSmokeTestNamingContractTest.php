<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Plan C.1 first-bullet contract — pin the file-naming convention
 * for the per-module smoke tests Plan C.2 will land in batches.
 *
 * Plan C.1 first-bullet task framing:
 *   "Every new test file named `LiveAdminModule<ModuleName>SmokeTest.php`."
 *
 * Why a contract test (not just a docblock):
 *   Plan C.2 lists ~60 module smoke tests to author in batches of 10.
 *   Across that many separate commits, file-naming drift is the most
 *   likely silent regression — `AdminModuleAccordionTest.php`,
 *   `LiveAdminAccordionSmokeTest.php`, and similar lookalikes would
 *   all "look right" at PR-review time but break the convention. A
 *   future contributor extending the suite couldn't grep one
 *   canonical pattern to find sibling tests; CI batchers couldn't
 *   target the suite via a single glob; and the eventual matrix
 *   harness (Plan D) couldn't auto-discover them.
 *
 * Two enforcement slices:
 *
 *   1. Every existing `LiveAdminModule*.php` file in tests/Browser
 *      must end in `SmokeTest.php` (catches "I forgot the Smoke
 *      suffix" and "I used Test instead of SmokeTest"). This slice
 *      is permissive when the directory has zero matches — Plan
 *      C.2 hasn't started yet, so an empty result set is the
 *      expected baseline today and shouldn't trigger a failure.
 *
 *   2. The class name in each such file must match the filename
 *      basename (PSR-4 / PHPUnit convention; without this, the
 *      Dusk runner discovers the file but autoloading fails at
 *      run time with a class-not-found error).
 *
 * The Plan C.2 inventory itself isn't enforced here — that belongs
 * to a separate "Plan C.2 progress" tracker. This test is purely
 * about naming/structure for whatever subset has actually shipped.
 *
 * Lives under tests/Feature/ alongside the other Plan-B/B.4 trait
 * contract tests; only reads files, no DB, no HTTP.
 */
class LiveAdminModuleSmokeTestNamingContractTest extends TestCase
{
    private const NAMING_PATTERN = '/^LiveAdminModule[A-Z][A-Za-z0-9]*SmokeTest\.php$/';

    private const CLASS_PATTERN = '/^class\s+(LiveAdminModule[A-Z][A-Za-z0-9]*SmokeTest)\b/m';

    /**
     * Scan tests/Browser for any `LiveAdminModule*.php` file and
     * return the basenames found. Returns an empty list when Plan
     * C.2 hasn't started.
     *
     * @return list<string>
     */
    private function discoverLiveAdminModuleFiles(): array
    {
        $dir = base_path('tests/Browser');
        $files = glob($dir . '/LiveAdminModule*.php') ?: [];

        return array_values(array_map(
            fn (string $path): string => basename($path),
            $files,
        ));
    }

    #[Test]
    public function naming_pattern_accepts_canonical_module_smoke_filenames(): void
    {
        // Pin the regex against Plan-C.2-listed canonical names so a
        // future refactor of the pattern that tightens it too far
        // (or loosens it too far) fails this test, not the bigger
        // contract test below where the failure would be confusingly
        // indirect. Sample three names spanning short / compound /
        // hyphenated-original variants the inventory contains.
        $this->assertMatchesRegularExpression(
            self::NAMING_PATTERN,
            'LiveAdminModuleAccordionSmokeTest.php',
            'Canonical short-name module test must match the naming pattern',
        );
        $this->assertMatchesRegularExpression(
            self::NAMING_PATTERN,
            'LiveAdminModuleContactFormSmokeTest.php',
            'Compound camelCase module test must match the naming pattern',
        );
        $this->assertMatchesRegularExpression(
            self::NAMING_PATTERN,
            'LiveAdminModuleWhiteLabelSmokeTest.php',
            'Multi-word camelCase module test must match the naming pattern',
        );
    }

    #[Test]
    public function naming_pattern_rejects_obvious_drift(): void
    {
        // Each lookalike below would feel "close enough" at PR
        // review time but would silently fragment the suite. Pin
        // them as explicit non-matches so the regex can't widen
        // accidentally.
        $forbidden = [
            'LiveAdminAccordionSmokeTest.php' => 'missing the literal "Module" segment',
            'AdminModuleAccordionSmokeTest.php' => 'missing the literal "Live" prefix',
            'LiveAdminModuleAccordionTest.php' => 'missing the literal "Smoke" segment',
            'LiveAdminModuleAccordion.php' => 'missing the literal "SmokeTest" suffix',
            'LiveAdminModulesAccordionSmokeTest.php' => 'plural "Modules" instead of singular',
            'LiveAdminModuleaccordionSmokeTest.php' => 'lowercase first letter of module name',
        ];

        foreach ($forbidden as $name => $reason) {
            $this->assertDoesNotMatchRegularExpression(
                self::NAMING_PATTERN,
                $name,
                "Plan C.1 first-bullet drift — `{$name}` must NOT match the naming pattern ({$reason})",
            );
        }
    }

    #[Test]
    public function every_existing_live_admin_module_file_matches_the_pattern(): void
    {
        $files = $this->discoverLiveAdminModuleFiles();

        // Plan C.2 hasn't started yet — an empty result is the
        // expected baseline today. The sister test below pins what
        // the pattern means; this one fires only once Plan C.2
        // tests start landing.
        if ($files === []) {
            $this->addToAssertionCount(1);

            return;
        }

        $offenders = [];
        foreach ($files as $name) {
            if (! preg_match(self::NAMING_PATTERN, $name)) {
                $offenders[] = $name;
            }
        }

        $this->assertSame(
            [],
            $offenders,
            'Plan C.1 first-bullet drift — these files in tests/Browser/ start with '
            . '"LiveAdminModule" but do not match the canonical '
            . 'LiveAdminModule<ModuleName>SmokeTest.php pattern. Rename each file '
            . '(and its class declaration) to the canonical shape: '
            . json_encode($offenders, JSON_UNESCAPED_SLASHES)
        );
    }

    #[Test]
    public function class_declaration_matches_filename_in_every_module_smoke_test(): void
    {
        $files = $this->discoverLiveAdminModuleFiles();

        if ($files === []) {
            $this->addToAssertionCount(1);

            return;
        }

        $mismatches = [];
        foreach ($files as $name) {
            // Only enforce the class-name match for files that
            // already match the filename pattern; the previous test
            // surfaces filename violations on their own, and we
            // don't want to double-count the same file as both a
            // naming-drift offender AND a class-name mismatch.
            if (! preg_match(self::NAMING_PATTERN, $name)) {
                continue;
            }

            $expectedClass = pathinfo($name, PATHINFO_FILENAME);
            $source = (string) file_get_contents(base_path('tests/Browser/' . $name));

            if (! preg_match(self::CLASS_PATTERN, $source, $m) || $m[1] !== $expectedClass) {
                $mismatches[$name] = [
                    'expected_class' => $expectedClass,
                    'declared_class' => $m[1] ?? '(none found)',
                ];
            }
        }

        $this->assertSame(
            [],
            $mismatches,
            'Plan C.1 first-bullet drift — these module-smoke test files declare a class '
            . 'whose name does not match the filename basename. PSR-4 / PHPUnit autoload '
            . 'will fail at run time with a class-not-found error. Rename either the file '
            . 'or the class so they match: '
            . json_encode($mismatches, JSON_UNESCAPED_SLASHES)
        );
    }
}
