<?php

namespace MicroweberPackages\Monitoring\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * AI-124 / TICKET-CT (cycle-121 2026-05-09): config-orphan audit.
 *
 * Greps every `config('foo.bar')` callsite in the source tree and
 * cross-checks that the dot-key resolves to a real value in the
 * matching `config/foo.php` file. Flags two failure modes:
 *
 *   1. ORPHAN — `config('foo.bar')` reads a key that doesn't
 *      exist in any config file. Returns null silently, often the
 *      root cause of mysterious "feature is disabled" reports.
 *
 *   2. UNUSED — a key declared in `config/foo.php` but never read
 *      via `config()`. Dead config entries; cleanup target.
 *
 * Exit code 0 = clean, 1 = orphans found, 2 = unused (informational
 * — `--strict` upgrades unused to a failure).
 */
class ConfigOrphanAuditCommand extends Command
{
    protected $signature = 'monitoring:config-orphan-audit
                            {--strict : Treat unused-keys as a failure (default: warn-only)}
                            {--top=20 : Show the top-N orphans/unused per category}';

    protected $description = 'Cross-check every config(\'foo.bar\') callsite against config/*.php (AI-124 / TICKET-CT)';

    public function handle(): int
    {
        $base = base_path();
        $configDir = config_path();
        $strict = (bool) $this->option('strict');
        $top = max(1, (int) ($this->option('top') ?: 20));

        $this->info("AI-124 / TICKET-CT — config-orphan audit");
        $this->line("  config dir: {$configDir}");
        $this->line('');

        // 1. Build the canonical key tree from config/*.php.
        $declared = [];
        foreach (File::files($configDir) as $file) {
            $name = $file->getFilenameWithoutExtension();
            $values = include $file->getPathname();
            if (!is_array($values)) {
                continue;
            }
            $this->flatten($values, $name, $declared);
        }
        $declaredCount = count($declared);

        // 2. Scan source tree for `config('foo.bar')` callsites.
        $usages = [];
        $scanRoots = [
            $base . '/app',
            $base . '/src/MicroweberPackages',
            $base . '/Modules',
            $base . '/bootstrap',
            $base . '/routes',
        ];
        foreach ($scanRoots as $root) {
            if (!is_dir($root)) {
                continue;
            }
            $this->scanRoot($root, $usages);
        }
        $usageCount = count($usages);

        // 3. Cross-check.
        $orphans = [];
        foreach ($usages as $key => $sites) {
            // A key like `app.debug` is fine if `app.debug` OR any
            // prefix exists (some callers read a sub-tree via
            // `config('app')`).
            if (isset($declared[$key])) {
                continue;
            }
            // Trailing-dot prefix lookup: if the user reads
            // `config('foo.bar.baz')` and `foo.bar` exists as an
            // array, accept it.
            $parts = explode('.', $key);
            $accepted = false;
            while (count($parts) > 0) {
                array_pop($parts);
                if (count($parts) === 0) {
                    break;
                }
                if (isset($declared[implode('.', $parts)])) {
                    $accepted = true;
                    break;
                }
            }
            if (!$accepted) {
                $orphans[$key] = $sites;
            }
        }

        $unused = [];
        foreach ($declared as $key => $_) {
            if (isset($usages[$key])) {
                continue;
            }
            // Same prefix-tolerance for declared keys (a caller might
            // read the parent block).
            $parts = explode('.', $key);
            $found = false;
            while (count($parts) > 0) {
                if (isset($usages[implode('.', $parts)])) {
                    $found = true;
                    break;
                }
                array_pop($parts);
            }
            if (!$found) {
                $unused[$key] = true;
            }
        }

        $this->line("declared keys (config/*.php):  {$declaredCount}");
        $this->line("read-sites (config('...') ): " . $usageCount);
        $this->line("orphans:                      " . count($orphans));
        $this->line("unused:                       " . count($unused));
        $this->line('');

        if (!empty($orphans)) {
            $this->error("ORPHAN config keys (read but never declared):");
            $shown = 0;
            foreach ($orphans as $key => $sites) {
                if (++$shown > $top) {
                    break;
                }
                $sample = $sites[0] ?? '';
                $this->line("  - {$key}    (e.g. {$sample})");
            }
        }

        if (!empty($unused)) {
            $this->warn('UNUSED config keys (declared but never read):');
            $shown = 0;
            foreach (array_keys($unused) as $key) {
                if (++$shown > $top) {
                    break;
                }
                $this->line("  - {$key}");
            }
        }

        if (!empty($orphans)) {
            return self::FAILURE;
        }
        if ($strict && !empty($unused)) {
            $this->error('STRICT mode: unused-keys count > 0.');
            return 2;
        }
        $this->info('config-orphan audit: clean.');
        return self::SUCCESS;
    }

    /**
     * Flatten a nested array into dot-keys.
     */
    protected function flatten(array $values, string $prefix, array &$out): void
    {
        foreach ($values as $k => $v) {
            $key = $prefix . '.' . $k;
            $out[$key] = true;
            if (is_array($v)) {
                $this->flatten($v, $key, $out);
            }
        }
    }

    /**
     * Recursively scan a root for `config('foo.bar')` patterns.
     */
    protected function scanRoot(string $root, array &$usages): void
    {
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root));
        foreach ($rii as $file) {
            if (!$file->isFile()) {
                continue;
            }
            $path = $file->getPathname();
            if (!str_ends_with($path, '.php')) {
                continue;
            }
            $src = file_get_contents($path);
            if (!str_contains($src, 'config(')) {
                continue;
            }
            if (!preg_match_all("/config\\(\\s*'([a-zA-Z0-9_.-]+)'/", $src, $matches, PREG_OFFSET_CAPTURE)) {
                continue;
            }
            foreach ($matches[1] as $hit) {
                $key = $hit[0];
                $offset = $hit[1];
                $line = substr_count($src, "\n", 0, $offset) + 1;
                $rel = str_replace(base_path() . '/', '', $path);
                $usages[$key][] = "{$rel}:{$line}";
            }
        }
    }
}
