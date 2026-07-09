<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract: a Filament resource's getNavigationBadge() (and any DB-touching
 * getNavigationBadgeColor()) must NOT run an unguarded count()/query.
 *
 * Navigation badges render on EVERY admin page, so a query against a table that
 * does not exist yet (fresh install, a module whose migration is still pending,
 * or a partially-imported DB) throws a QueryException that white-screens the
 * entire admin panel. Every such method must swallow DB errors (try/catch or a
 * Schema::hasTable guard) and degrade to "no badge" (null).
 *
 * Regression guard for the module_dependencies / backup_history white-screens.
 */
class NavigationBadgeResilienceContractTest extends TestCase
{
    #[Test]
    public function every_navigation_badge_query_is_guarded_against_a_missing_table(): void
    {
        $roots = [
            base_path('src/MicroweberPackages'),
            base_path('Modules'),
        ];

        $offenders = [];

        foreach ($roots as $root) {
            if (!is_dir($root)) {
                continue;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if ($file->getExtension() !== 'php' || !str_ends_with($file->getFilename(), 'Resource.php')) {
                    continue;
                }

                $source = file_get_contents($file->getPathname());

                foreach (['getNavigationBadge', 'getNavigationBadgeColor'] as $method) {
                    $body = $this->extractMethodBody($source, $method);
                    if ($body === null) {
                        continue;
                    }

                    // Only methods that actually hit the DB can white-screen.
                    $touchesDb = preg_match('/->count\(|::count\(|->get\(|->first\(|->exists\(|->sum\(/', $body) === 1;
                    if (!$touchesDb) {
                        continue;
                    }

                    $isGuarded = preg_match('/\btry\b|hasTable|rescue\s*\(/', $body) === 1;
                    if (!$isGuarded) {
                        $offenders[] = str_replace(base_path() . '/', '', $file->getPathname()) . "::{$method}()";
                    }
                }
            }
        }

        $this->assertSame(
            [],
            $offenders,
            "Unguarded navigation-badge DB queries (wrap in try/catch → null so a missing table can't white-screen the admin):\n  - "
                . implode("\n  - ", $offenders)
        );
    }

    /**
     * Extract a method's body via brace-counting (handles nested try/if braces).
     */
    private function extractMethodBody(string $source, string $method): ?string
    {
        $pos = strpos($source, "function {$method}(");
        if ($pos === false) {
            return null;
        }

        $open = strpos($source, '{', $pos);
        if ($open === false) {
            return null;
        }

        $depth = 0;
        $len = strlen($source);
        for ($i = $open; $i < $len; $i++) {
            if ($source[$i] === '{') {
                $depth++;
            } elseif ($source[$i] === '}') {
                $depth--;
                if ($depth === 0) {
                    return substr($source, $open, $i - $open + 1);
                }
            }
        }

        return null;
    }
}
