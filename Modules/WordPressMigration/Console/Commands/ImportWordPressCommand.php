<?php

namespace Modules\WordPressMigration\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use Modules\WordPressMigration\Services\Http\HttpProbeFetcher;
use Modules\WordPressMigration\Services\Importers\RssFeedImporter;
use Modules\WordPressMigration\Services\Importers\SitemapPageImporter;
use Modules\WordPressMigration\Services\Importers\WpRestImporter;
use Modules\WordPressMigration\Services\Importers\WxrImporter;
use Modules\WordPressMigration\Services\StagingWriter;
use Modules\WordPressMigration\Services\Taxonomy\TaxonomyIndex;
use Modules\WordPressMigration\Services\Taxonomy\TaxonomyLookup;
use Modules\WordPressMigration\Services\WordPressContentMapper;
use Modules\WordPressMigration\Services\WordPressMigrationJobRepository;
use Modules\WordPressMigration\Services\WordPressSiteProbe;
use Modules\WordPressMigration\Services\WordPressSiteProbeResult;

/**
 * Headless driver for the WordPress migration pipeline.
 *
 * Usage:
 *   php artisan microweber:import:wordpress https://example.com --mode=rest --max=50
 *   php artisan microweber:import:wordpress https://example.com --dry-run --yes
 *   php artisan microweber:import:wordpress /path/to/export.xml --mode=wxr --yes
 *
 * Modes:
 *   rest | rss | sitemap | wxr
 *   When `--mode` is omitted and the source is a URL, the probe picks
 *   the strongest capability (rest > rss > sitemap).
 *
 * --dry-run
 *   Writes through {@see StagingWriter} to `wp_migration_staging_*`
 *   instead of landing on live `content`. This matches the Filament
 *   preview-before-commit flow exactly, so a CLI dry-run populates
 *   the same staging tables the admin preview page reads.
 *
 * --yes
 *   Auto-accepts all confirmation prompts. Required for CI jobs and
 *   the Phase 10 smoke test — otherwise the command would block
 *   waiting for operator input.
 *
 * Exit codes:
 *   0  success (items staged or committed)
 *   1  unreachable source / no usable importer
 *   2  validation error (bad URL, unknown mode, missing file)
 *   3  importer threw mid-run
 *
 * The command shares the {@see WpRestImporter}, {@see RssFeedImporter},
 * {@see SitemapPageImporter} and {@see WxrImporter} classes with the
 * Filament admin page so the two surfaces cannot drift. When a URL
 * probe precedes the run it is persisted via {@see WordPressMigrationJobRepository}
 * — CLI-launched jobs show up in the Filament history list the same
 * way operator-launched ones do.
 */
class ImportWordPressCommand extends Command
{
    protected $signature = 'microweber:import:wordpress
        {url : WordPress site URL (or a .xml path when --mode=wxr)}
        {--mode= : rest|rss|sitemap|wxr (auto-detected from probe when omitted)}
        {--dry-run : Write to the staging tables instead of live content}
        {--yes : Auto-accept confirmations (for CI / scripting)}
        {--max=100 : Cap on items processed this run}';

    protected $description = 'Import content from a WordPress source into Microweber';

    private const MODE_REST = 'rest';
    private const MODE_RSS = 'rss';
    private const MODE_SITEMAP = 'sitemap';
    private const MODE_WXR = 'wxr';

    private const VALID_MODES = [
        self::MODE_REST,
        self::MODE_RSS,
        self::MODE_SITEMAP,
        self::MODE_WXR,
    ];

    public function handle(): int
    {
        $urlOrPath = (string) $this->argument('url');
        $mode = $this->option('mode');
        $dryRun = (bool) $this->option('dry-run');
        $assumeYes = (bool) $this->option('yes');
        $max = (int) $this->option('max');

        if ($max <= 0) {
            $this->error('--max must be a positive integer');
            return 2;
        }

        if ($mode !== null && ! in_array($mode, self::VALID_MODES, true)) {
            $this->error('--mode must be one of: ' . implode(', ', self::VALID_MODES));
            return 2;
        }

        if ($mode === self::MODE_WXR) {
            return $this->runWxr($urlOrPath, $dryRun, $assumeYes, $max);
        }

        return $this->runUrlMode($urlOrPath, $mode, $dryRun, $assumeYes, $max);
    }

    private function runUrlMode(string $url, ?string $forcedMode, bool $dryRun, bool $assumeYes, int $max): int
    {
        if (preg_match('~^https?://~i', $url) !== 1) {
            $this->error("URL must start with http:// or https:// — got: {$url}");
            return 2;
        }

        $this->line("Probing <info>{$url}</info> ...");
        $probe = new WordPressSiteProbe(app(HttpProbeFetcher::class));
        $result = $probe->probe($url);

        $this->line('Detected capabilities: ' . (empty($result->capabilities) ? '<fg=red>none</>' : implode(', ', $result->capabilities)));

        $mode = $forcedMode ?? $this->pickAutoMode($result->capabilities);
        if ($mode === null) {
            $this->error('No REST, RSS, or sitemap capability detected. Pass --mode=wxr with a local file to skip the probe.');
            return 1;
        }

        if ($forcedMode !== null && ! in_array($forcedMode, $result->capabilities, true)) {
            $this->warn("Requested mode '{$forcedMode}' is not in the probe's capability list (" . implode(', ', $result->capabilities) . '). Continuing anyway at your request.');
        }

        $repository = app(WordPressMigrationJobRepository::class);
        $job = $repository->storeProbeResult($result);

        $this->line(sprintf(
            '%s via <info>%s</info>%s (job #%d, ~%d posts / ~%d pages)',
            $dryRun ? 'Dry-run' : 'Import',
            $mode,
            $max > 0 ? " · max {$max}" : '',
            (int) $job->id,
            (int) $result->estimatedPosts,
            (int) $result->estimatedPages,
        ));

        if (! $this->confirmStart($assumeYes, $dryRun)) {
            $this->line('Aborted.');
            return 0;
        }

        $repository->markRunning($job);

        try {
            [$items, $progressExtras] = $this->fetchItemsForUrlMode(
                mode: $mode,
                job: $job,
                max: $max,
            );

            $written = $this->dispatchItems(
                items: $items,
                job: $job,
                mode: $mode,
                dryRun: $dryRun,
            );

            $repository->updateProgress($job, array_merge(
                $progressExtras,
                ['imported' => $written, 'processed' => $written]
            ));
            $repository->markFinished($job);

            $this->info(sprintf(
                '%s complete: %d items %s.',
                $dryRun ? 'Dry-run' : 'Import',
                $written,
                $dryRun ? 'staged for preview' : 'written to live content',
            ));
            return 0;
        } catch (\Throwable $e) {
            $repository->markFailed($job, $e->getMessage());
            $this->error('Importer threw: ' . $e->getMessage());
            return 3;
        }
    }

    /**
     * @return array{0: iterable<MigrationItemDTO>, 1: array<string, mixed>}
     */
    private function fetchItemsForUrlMode(string $mode, WordPressMigrationJob $job, int $max): array
    {
        $seen = $this->alreadyImportedGuids();

        switch ($mode) {
            case self::MODE_REST:
                $importer = new WpRestImporter(app(HttpProbeFetcher::class));
                $result = $importer->walk((string) $job->source_url, $seen, $max);
                return [
                    $result->items,
                    [
                        'pages_fetched' => $result->pagesFetched,
                        'stop_reason' => $result->stopReason,
                        'media_count' => count($result->media),
                    ],
                ];

            case self::MODE_RSS:
                $importer = app(RssFeedImporter::class);
                $result = $importer->walk((string) $job->source_url, $seen, $max);
                return [
                    $result->items,
                    [
                        'pages_fetched' => $result->pagesFetched,
                        'stop_reason' => $result->stopReason,
                    ],
                ];

            case self::MODE_SITEMAP:
                $probe = (array) ($job->probe_result ?? []);
                $sitemapUrl = (string) ($probe['sitemap_index_url'] ?? ($job->source_url . '/sitemap.xml'));
                $importer = app(SitemapPageImporter::class);
                $result = $importer->walk($sitemapUrl, $seen, $max);
                return [
                    $result->items,
                    [
                        'pages_fetched' => $result->pagesFetched,
                        'pages_skipped' => $result->pagesSkipped,
                        'stop_reason' => $result->stopReason,
                    ],
                ];
        }

        throw new \InvalidArgumentException("Unsupported mode '{$mode}' for URL-based import");
    }

    private function runWxr(string $path, bool $dryRun, bool $assumeYes, int $max): int
    {
        if (! is_file($path)) {
            $this->error("WXR file not found at: {$path}");
            return 2;
        }

        $this->line("Reading <info>{$path}</info> ...");
        $result = (new WxrImporter())->import($path, [], $max);

        if (empty($result->items) && ! empty($result->warnings)) {
            $this->error('WXR parse failed: ' . $result->warnings[0]);
            return 2;
        }

        $sourceUrl = 'wxr://' . basename($path);
        $job = WordPressMigrationJob::query()
            ->where('source_url_hash', hash('sha256', $sourceUrl))
            ->first();

        if ($job === null) {
            $job = WordPressMigrationJob::create([
                'source_url' => $sourceUrl,
                'source_url_hash' => hash('sha256', $sourceUrl),
                'source_host' => 'wxr-import',
                'status' => WordPressMigrationJob::STATUS_READY,
                'mode' => 'wxr',
            ]);
        }

        $this->line(sprintf(
            '%s via <info>wxr</info>: %d items parsed from %s.',
            $dryRun ? 'Dry-run' : 'Import',
            count($result->items),
            basename($path),
        ));

        if (! $this->confirmStart($assumeYes, $dryRun)) {
            $this->line('Aborted.');
            return 0;
        }

        $repository = app(WordPressMigrationJobRepository::class);
        $repository->markRunning($job);

        try {
            $taxonomy = (new TaxonomyIndex())->prime(
                $result->categories,
                $result->tags,
                $result->users,
            );

            $written = $this->dispatchItems(
                items: $result->items,
                job: $job,
                mode: self::MODE_WXR,
                dryRun: $dryRun,
                taxonomy: $taxonomy,
            );

            $repository->updateProgress($job, [
                'imported' => $written,
                'processed' => $written,
                'items_seen' => $result->itemsSeen,
                'media_count' => count($result->media),
                'stop_reason' => $result->stopReason,
            ]);
            $repository->markFinished($job);

            $this->info(sprintf(
                '%s complete: %d items %s.',
                $dryRun ? 'Dry-run' : 'Import',
                $written,
                $dryRun ? 'staged for preview' : 'written to live content',
            ));
            return 0;
        } catch (\Throwable $e) {
            $repository->markFailed($job, $e->getMessage());
            $this->error('Importer threw: ' . $e->getMessage());
            return 3;
        }
    }

    /**
     * @param iterable<MigrationItemDTO> $items
     */
    private function dispatchItems(
        iterable $items,
        WordPressMigrationJob $job,
        string $mode,
        bool $dryRun,
        ?TaxonomyLookup $taxonomy = null,
    ): int {
        $importSource = match ($mode) {
            self::MODE_REST => 'wordpress_rest',
            self::MODE_WXR => 'wordpress_wxr',
            self::MODE_SITEMAP => 'wordpress_sitemap',
            default => WordPressContentMapper::IMPORT_SOURCE_WORDPRESS_RSS,
        };

        if ($dryRun) {
            $writer = new StagingWriter($importSource);
            $count = 0;
            foreach ($items as $dto) {
                $writer->stage((int) $job->id, $dto);
                $count++;
            }
            return $count;
        }

        $mapper = new WordPressContentMapper(
            importSource: $importSource,
            taxonomy: $taxonomy,
        );

        $count = 0;
        foreach ($items as $dto) {
            $mapper->map($dto);
            $count++;
        }
        return $count;
    }

    /**
     * @param list<string> $capabilities
     */
    private function pickAutoMode(array $capabilities): ?string
    {
        foreach ([self::MODE_REST, self::MODE_RSS, self::MODE_SITEMAP] as $candidate) {
            if (in_array($candidate, $capabilities, true)) {
                return $candidate;
            }
        }
        return null;
    }

    /**
     * @return list<string>
     */
    private function alreadyImportedGuids(): array
    {
        return DB::table('content_data')
            ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
            ->pluck('field_value')
            ->map(fn ($v) => (string) $v)
            ->values()
            ->all();
    }

    private function confirmStart(bool $assumeYes, bool $dryRun): bool
    {
        if ($assumeYes) {
            return true;
        }
        $prompt = $dryRun
            ? 'Stage items to the preview tables?'
            : 'Write imported items to live content?';
        return (bool) $this->confirm($prompt, true);
    }
}
