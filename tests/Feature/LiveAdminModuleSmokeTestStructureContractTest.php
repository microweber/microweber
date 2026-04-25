<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Plan C.1 second-bullet contract — pin the structural rules
 * every Plan C.2 module-smoke test must follow:
 *
 *   1. Lives under `tests/Browser/` (not `tests/Feature/`,
 *      `tests/Unit/`, or any nested subdirectory).
 *   2. Composes the existing `AdminLoginTrait` so the same
 *      cached-login pipeline used everywhere else in the suite
 *      drives the smoke. Letting a module-smoke test ship its
 *      own login flow would fragment the suite and rebuild the
 *      session on every test method (the cache lives on
 *      `Tests\DuskTestCase::$adminLoggedIn`).
 *
 * Why a contract test (not just a docblock):
 *   Plan C.2 lists ~60 module smoke tests across batches of 10.
 *   Across that many separate commits a future contributor could
 *   easily land a test that imports its own `LoginsAsAdmin`-like
 *   helper or skips the login step entirely (since most module
 *   admin pages 302 to /admin/login but render their final
 *   markup before the redirect cancels). The smoke would
 *   superficially pass while exercising a logged-out shell —
 *   silently destroying the suite's value.
 *
 * Sister to the naming-contract test added under Plan C.1's
 * first bullet: that one pins the FILENAME convention; this one
 * pins the STRUCTURE convention. Together they give Plan C.2 a
 * complete authoring scaffold.
 *
 * Lives under tests/Feature/ alongside the other matrix and
 * trait-contract tests; only reads files, no DB, no HTTP.
 */
class LiveAdminModuleSmokeTestStructureContractTest extends TestCase
{
    private const NAMING_PATTERN = '/^LiveAdminModule[A-Z][A-Za-z0-9]*SmokeTest\.php$/';

    private const REQUIRED_TRAIT_FQN = 'Tests\\Browser\\Traits\\AdminLoginTrait';

    private const REQUIRED_TRAIT_USE_RE = '/use\s+Tests\\\\Browser\\\\Traits\\\\AdminLoginTrait\s*;/';

    private const REQUIRED_TRAIT_COMPOSE_RE = '/^\s*use\s+AdminLoginTrait\s*;/m';

    /**
     * Discover every `LiveAdminModule*SmokeTest.php` anywhere
     * under tests/. The Plan C.1 second-bullet contract is "live
     * under tests/Browser/", so files matching the canonical
     * filename pattern outside that directory are themselves a
     * violation — discover broadly, then check location.
     *
     * @return list<array{path: string, basename: string}>
     */
    private function discoverModuleSmokeTests(): array
    {
        $testsRoot = base_path('tests');
        $iter = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $testsRoot,
                \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::CURRENT_AS_PATHNAME,
            ),
        );

        $found = [];
        foreach ($iter as $path) {
            if (! is_string($path)) {
                continue;
            }
            $basename = basename($path);
            if (! preg_match(self::NAMING_PATTERN, $basename)) {
                continue;
            }
            $found[] = ['path' => $path, 'basename' => $basename];
        }

        return $found;
    }

    #[Test]
    public function admin_login_trait_target_exists_at_the_canonical_namespace(): void
    {
        // The whole contract assumes the trait exists and is
        // namespaced exactly. If somebody moves or renames the
        // trait, this test fires before the structure check below
        // mass-fails confusingly.
        $this->assertTrue(
            trait_exists(self::REQUIRED_TRAIT_FQN),
            sprintf(
                'Plan C.1 second-bullet contract assumes %s exists. '
                . 'If the trait moved, update both the contract and the '
                . 'C.2 module-smoke tests that compose it.',
                self::REQUIRED_TRAIT_FQN,
            ),
        );
    }

    #[Test]
    public function every_module_smoke_test_lives_under_tests_browser(): void
    {
        $tests = $this->discoverModuleSmokeTests();

        // Plan C.2 hasn't started yet — empty result is the
        // expected baseline today. Fires only once tests land.
        if ($tests === []) {
            $this->addToAssertionCount(1);

            return;
        }

        $expectedDir = base_path('tests/Browser') . DIRECTORY_SEPARATOR;

        $offenders = [];
        foreach ($tests as $entry) {
            if (! str_starts_with($entry['path'], $expectedDir)) {
                $offenders[] = str_replace(base_path() . '/', '', $entry['path']);
            }
        }

        $this->assertSame(
            [],
            $offenders,
            'Plan C.1 second-bullet drift — these module-smoke tests do not live '
            . 'directly under tests/Browser/. Move each file to that directory so '
            . 'the Dusk runner picks it up via its standard glob: '
            . json_encode($offenders, JSON_UNESCAPED_SLASHES)
        );
    }

    #[Test]
    public function every_module_smoke_test_composes_the_admin_login_trait(): void
    {
        $tests = $this->discoverModuleSmokeTests();

        if ($tests === []) {
            $this->addToAssertionCount(1);

            return;
        }

        $offenders = [];
        foreach ($tests as $entry) {
            $source = (string) file_get_contents($entry['path']);

            $imported = (bool) preg_match(self::REQUIRED_TRAIT_USE_RE, $source);
            $composed = (bool) preg_match(self::REQUIRED_TRAIT_COMPOSE_RE, $source);

            if (! $imported || ! $composed) {
                $offenders[$entry['basename']] = [
                    'imports_trait' => $imported,
                    'composes_trait' => $composed,
                ];
            }
        }

        $this->assertSame(
            [],
            $offenders,
            'Plan C.1 second-bullet drift — these module-smoke tests do not compose '
            . 'AdminLoginTrait. Add `use Tests\\Browser\\Traits\\AdminLoginTrait;` '
            . 'to the imports and `use AdminLoginTrait;` inside the class body so '
            . 'the smoke reuses the cached-login pipeline instead of fragmenting '
            . 'the suite with bespoke login code: '
            . json_encode($offenders, JSON_UNESCAPED_SLASHES)
        );
    }
}
