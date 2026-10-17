<?php

namespace MicroweberPackages\Monitoring\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class BootQueryAuditCommand extends Command
{
    protected $signature = 'monitoring:boot-query-audit
                            {--route=/ : URL path to hit (e.g. /, /admin)}
                            {--threshold=50 : Fail if query count exceeds this}
                            {--top=10 : Show the top-N most-fired queries}
                            {--no-warmup : Skip the discarded warm-up request}';

    protected $description = 'Spot-check service-provider boot() methods via DB::enableQueryLog() (target <50 warm-cache queries)';

    public function handle(): int
    {
        $route = (string) ($this->option('route') ?: '/');
        $threshold = (int) ($this->option('threshold') ?: 50);
        $top = max(1, (int) ($this->option('top') ?: 10));
        $skipWarmup = (bool) $this->option('no-warmup');

        $this->info("AI-120 / TICKET-BN — boot-time query audit");
        $this->line("  route:       {$route}");
        $this->line("  threshold:   {$threshold} queries");
        $this->line("  top-N shown: {$top}");
        $this->line('');

        // First request: warm-up (Filament panel discovery, module
        // registry, view-cache compilation). Discarded.
        if (!$skipWarmup) {
            $this->line('warming caches (first request, discarded) ...');
            try {
                $this->fireRequest($route);
            } catch (\Throwable $e) {
                $this->warn('warm-up request raised: ' . $e->getMessage());
            }
        }

        // Real measured request.
        DB::flushQueryLog();
        DB::enableQueryLog();
        $start = microtime(true);
        try {
            $this->fireRequest($route);
        } catch (\Throwable $e) {
            $this->warn('measured request raised: ' . $e->getMessage());
        }
        $elapsedMs = (microtime(true) - $start) * 1000;
        $log = DB::getQueryLog();
        DB::disableQueryLog();

        $count = count($log);
        $this->line('');
        $this->info("queries fired: {$count}   elapsed: " . number_format($elapsedMs, 1) . ' ms');

        // Group by normalized SQL (replace bindings) so repeats roll up.
        $byPattern = [];
        foreach ($log as $row) {
            $sql = preg_replace('/\\s+/', ' ', trim((string) ($row['query'] ?? '')));
            $sql = preg_replace("/'[^']*'/", '?', $sql);
            $sql = preg_replace('/\\b\\d+\\b/', '?', $sql);
            $byPattern[$sql] = ($byPattern[$sql] ?? 0) + 1;
        }
        arsort($byPattern);

        $this->line('');
        $this->info("top {$top} most-fired queries:");
        $shown = 0;
        foreach ($byPattern as $sql => $n) {
            $shown++;
            if ($shown > $top) {
                break;
            }
            $sqlPreview = strlen($sql) > 120 ? (substr($sql, 0, 117) . '...') : $sql;
            $this->line("  ×{$n}  {$sqlPreview}");
        }

        $this->line('');
        if ($count > $threshold) {
            $this->error("FAIL: {$count} queries exceeds threshold {$threshold}.");
            $this->warn('  Investigate the top-N above. Look for:');
            $this->warn('    - SELECT * FROM cms_settings ... fired per-helper invocation');
            $this->warn('    - module discovery queries that should be cached');
            $this->warn('    - eager-loadable relations firing N+1');
            return self::FAILURE;
        }

        $this->info("OK: {$count} queries within threshold {$threshold}.");
        return self::SUCCESS;
    }

    /**
     * Fire a synthetic HTTP request through the kernel without
     * spawning a subprocess. Returns the Response so callers can
     * inspect status if needed (we discard it here).
     */
    protected function fireRequest(string $path)
    {
        /** @var \Illuminate\Contracts\Http\Kernel $kernel */
        $kernel = $this->getLaravel()->make(\Illuminate\Contracts\Http\Kernel::class);
        $request = \Illuminate\Http\Request::create($path, 'GET');
        $response = $kernel->handle($request);
        $kernel->terminate($request, $response);
        return $response;
    }
}
