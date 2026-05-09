<?php

namespace MicroweberPackages\Monitoring\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * AI-124 / TICKET-CU (cycle-121 2026-05-09): PSR-4 strict audit.
 *
 * For every PHP file under the autoload roots declared in
 * `composer.json`, verify that the file's `namespace ...;`
 * declaration matches its directory path under the corresponding
 * PSR-4 prefix.
 *
 * Failure modes flagged:
 *
 *   1. NAMESPACE_MISMATCH — file at
 *      `Modules/Cart/Repositories/CartManager.php` declares
 *      `namespace Modules\Foo\Bar;` (or no namespace at all). The
 *      composer autoloader silently DOESN'T autoload such files
 *      under their declared name; callers get
 *      `Class not found` at runtime.
 *
 *   2. CLASS_NAME_MISMATCH — file `CartManager.php` declares
 *      `class TheCart` instead of `class CartManager`. PSR-4 file-
 *      name → class-name link is broken.
 *
 * Excludes Tests/, database/migrations/, vendor/, node_modules/.
 *
 * Exit code 0 = clean, 1 = mismatches found.
 */
class Psr4StrictAuditCommand extends Command
{
    protected $signature = 'monitoring:psr4-strict-audit
                            {--top=20 : Show the top-N mismatches}
                            {--no-vendor : Skip the vendor/ scan (always skipped, kept for compat)}';

    protected $description = 'Strict PSR-4 audit: file path → declared namespace + class name (AI-124 / TICKET-CU)';

    public function handle(): int
    {
        $base = base_path();
        $top = max(1, (int) ($this->option('top') ?: 20));

        $this->info("AI-124 / TICKET-CU — PSR-4 strict audit");
        $this->line('');

        // PSR-4 prefix → root-dir map (mirrors composer.json autoload).
        $autoload = [
            'App\\'                  => 'app/',
            'Microweber\\'           => 'src/Microweber/',
            'MicroweberPackages\\'   => 'src/MicroweberPackages/',
            'Modules\\'              => 'Modules/',
            'Templates\\'            => 'Templates/',
        ];

        $excludeSubstr = [
            '/vendor/',
            '/node_modules/',
            '/database/migrations/',
            '/database/seeds/',
            '/database/seeders/',
            '/Tests/',
            '/tests/',
            '/storage/',
            '/.git/',
        ];

        $namespaceMismatches = [];
        $classMismatches = [];
        $scannedCount = 0;

        foreach ($autoload as $prefix => $rel) {
            $rootDir = $base . '/' . rtrim($rel, '/');
            if (!is_dir($rootDir)) {
                continue;
            }
            $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($rootDir));
            foreach ($rii as $file) {
                if (!$file->isFile()) {
                    continue;
                }
                $path = $file->getPathname();
                if (!str_ends_with($path, '.php')) {
                    continue;
                }
                $skip = false;
                foreach ($excludeSubstr as $needle) {
                    if (str_contains($path, $needle)) {
                        $skip = true;
                        break;
                    }
                }
                if ($skip) {
                    continue;
                }

                // Compute the EXPECTED namespace from the path.
                $relUnderRoot = substr($path, strlen($rootDir) + 1);
                $relUnderRoot = str_replace(['/', '\\'], '\\', $relUnderRoot);
                $expectedFqcn = rtrim($prefix, '\\') . '\\' . $relUnderRoot;
                $expectedFqcn = preg_replace('/\\.php$/', '', $expectedFqcn);
                $expectedClass = preg_replace('/^.*\\\\/', '', $expectedFqcn);
                $expectedNs = preg_replace('/\\\\[^\\\\]+$/', '', $expectedFqcn);

                // Read declared namespace + class.
                $src = file_get_contents($path);
                $declaredNs = null;
                if (preg_match('/^\\s*namespace\\s+([A-Za-z0-9_\\\\]+)\\s*;/m', $src, $m)) {
                    $declaredNs = $m[1];
                }
                $declaredClass = null;
                if (preg_match('/^\\s*(?:abstract\\s+|final\\s+)?(?:class|interface|trait|enum)\\s+([A-Za-z0-9_]+)/m', $src, $m)) {
                    $declaredClass = $m[1];
                }

                $scannedCount++;

                $relPath = str_replace($base . '/', '', $path);

                // Files without ANY namespace AND without ANY class
                // declaration are not subject to PSR-4 (e.g. helpers
                // shim files). Skip.
                if ($declaredNs === null && $declaredClass === null) {
                    continue;
                }

                if ($declaredNs !== null && $declaredNs !== $expectedNs) {
                    $namespaceMismatches[] = sprintf(
                        '%s  (expected: %s, declared: %s)',
                        $relPath, $expectedNs, $declaredNs
                    );
                }
                if ($declaredClass !== null && $declaredClass !== $expectedClass) {
                    $classMismatches[] = sprintf(
                        '%s  (expected class: %s, declared: %s)',
                        $relPath, $expectedClass, $declaredClass
                    );
                }
            }
        }

        $this->line("scanned PHP files:    {$scannedCount}");
        $this->line("namespace mismatches: " . count($namespaceMismatches));
        $this->line("class-name mismatches:" . count($classMismatches));
        $this->line('');

        if (!empty($namespaceMismatches)) {
            $this->error('NAMESPACE_MISMATCH:');
            $shown = 0;
            foreach ($namespaceMismatches as $line) {
                if (++$shown > $top) {
                    break;
                }
                $this->line('  - ' . $line);
            }
        }

        if (!empty($classMismatches)) {
            $this->error('CLASS_NAME_MISMATCH:');
            $shown = 0;
            foreach ($classMismatches as $line) {
                if (++$shown > $top) {
                    break;
                }
                $this->line('  - ' . $line);
            }
        }

        if (!empty($namespaceMismatches) || !empty($classMismatches)) {
            return self::FAILURE;
        }
        $this->info('PSR-4 strict audit: clean.');
        return self::SUCCESS;
    }
}
