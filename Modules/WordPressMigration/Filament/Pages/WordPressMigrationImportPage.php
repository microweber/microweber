<?php

namespace Modules\WordPressMigration\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Modules\WordPressMigration\DTOs\WxrImportResult;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use Modules\WordPressMigration\Services\Http\HttpProbeFetcher;
use Modules\WordPressMigration\Services\Http\WpAppPasswordCredential;
use Modules\WordPressMigration\Services\Importers\RssFeedImporter;
use Modules\WordPressMigration\Services\Importers\SitemapPageImporter;
use Modules\WordPressMigration\Services\Importers\WpRestImporter;
use Modules\WordPressMigration\Services\Importers\WxrImporter;
use Modules\WordPressMigration\Services\Taxonomy\TaxonomyIndex;
use Modules\WordPressMigration\Services\WordPressContentMapper;
use Illuminate\Support\Facades\DB;
use Modules\WordPressMigration\Services\WordPressMigrationJobRepository;
use Modules\WordPressMigration\Services\WordPressSiteProbe;
use Modules\WordPressMigration\Services\WordPressSiteProbeResult;

/**
 * Single-screen entry point for "I want to migrate my WordPress
 * site into this Microweber install."
 *
 * Flow:
 *   1. Operator pastes a URL and (optionally) an application
 *      password for REST auth.
 *   2. They click **Check**. The page runs WordPressSiteProbe,
 *      stores the outcome on wp_migration_jobs (upsert by URL),
 *      and renders the detected capabilities + item count so the
 *      operator can see what's pullable before committing.
 *   3. If the source is usable, a **Start import** button
 *      transitions the job from `ready` to `running`. The actual
 *      import worker is wired in a later Phase 2 task; for now
 *      this records the intent and surfaces a confirmation.
 *
 * State lives entirely on the associated wp_migration_jobs row,
 * so closing the browser mid-probe (or mid-import once the
 * worker lands) doesn't lose progress — re-opening the page with
 * the same URL re-hydrates from the repository.
 */
class WordPressMigrationImportPage extends Page
{
    use WithFileUploads;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-down-on-square-stack';
    protected static ?string $title = 'Import from WordPress';
    protected static ?string $navigationLabel = 'WordPress Migration';
    protected static string | \UnitEnum | null $navigationGroup = 'Tools';
    protected string $view = 'microweber-module-wordpressmigration::pages.import';

    public ?array $data = [
        'source_url' => '',
        'wp_application_password' => '',
    ];

    public ?int $jobId = null;

    /**
     * Livewire-managed temporary upload for the offline WXR path.
     * Hydrates into a {@see TemporaryUploadedFile} once the browser
     * has pushed the `.xml` bytes to the Livewire temp endpoint.
     * Cleared back to null after a successful import so a second
     * upload starts from a blank slate.
     */
    public $wxrUpload = null;

    public ?int $wxrJobId = null;

    public function mount(): void
    {
        $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('source_url')
                    ->label('WordPress site URL')
                    ->placeholder('https://example.com')
                    ->helperText('We will probe /wp-json, /feed, /sitemap.xml, and /robots.txt to figure out the best import mode.')
                    ->required()
                    ->url()
                    ->maxLength(500),
                TextInput::make('wp_application_password')
                    ->label('Application password (optional)')
                    ->password()
                    ->revealable()
                    ->helperText('Only needed for REST mode on sites that hide drafts/private content. Stored encrypted and auto-purged after 24 hours.')
                    ->maxLength(255),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('check')
                ->label('Check')
                ->icon('heroicon-o-magnifying-glass')
                ->color('primary')
                ->action(fn () => $this->check()),
        ];
    }

    public function check(): void
    {
        $state = $this->form->getState();
        $sourceUrl = trim((string)($state['source_url'] ?? ''));
        $password = (string)($state['wp_application_password'] ?? '');

        if ($sourceUrl === '') {
            $this->dangerNotification('Enter a URL first.');
            return;
        }

        $probe = app(WordPressSiteProbe::class);
        $result = $probe->probe($sourceUrl);

        $repository = app(WordPressMigrationJobRepository::class);
        $job = $repository->storeProbeResult(
            $result,
            $password !== '' ? $password : null,
        );

        $this->jobId = $job->id;

        if ($result->mode === WordPressSiteProbeResult::MODE_UNREACHABLE) {
            $this->dangerNotification(
                'Could not reach the site. ' . ($result->errors[0] ?? 'Check the URL and try again.')
            );
            return;
        }

        Notification::make()
            ->title('Probe complete')
            ->body($this->summaryLine($result))
            ->success()
            ->send();
    }

    public function startImport(): void
    {
        $job = $this->getJob();
        if ($job === null) {
            $this->dangerNotification('Run a probe first.');
            return;
        }

        if ($job->status === WordPressMigrationJob::STATUS_UNREACHABLE) {
            $this->dangerNotification('The source is unreachable — no importer can run against it.');
            return;
        }

        if ($job->isTerminal()) {
            $this->dangerNotification("This migration already {$job->status}. Start a new one if you want to re-run.");
            return;
        }

        if ($job->status === WordPressMigrationJob::STATUS_RUNNING) {
            Notification::make()
                ->title('Already running')
                ->body('The import worker is already processing this source.')
                ->info()
                ->send();
            return;
        }

        $repository = app(WordPressMigrationJobRepository::class);
        $capabilities = (array)($job->probe_result['capabilities'] ?? []);

        // Mode selection: prefer REST when the source exposes it —
        // REST carries strictly more than RSS (term slugs, media ids,
        // accurate author display names, featured_media, etc.) AND
        // produces stable `wp:{id}` guids, so the old "RSS wins because
        // of stable guids" reasoning no longer applies. RSS is the
        // middle fallback, sitemap is the last resort. WXR is still
        // unwired — file upload lives in a later phase.
        $hasRest = in_array(WordPressSiteProbeResult::MODE_REST, $capabilities, true);
        $hasRss = in_array(WordPressSiteProbeResult::MODE_RSS, $capabilities, true);
        $hasSitemap = in_array(WordPressSiteProbeResult::MODE_SITEMAP, $capabilities, true);

        if (!$hasRest && !$hasRss && !$hasSitemap) {
            $this->dangerNotification(
                'No REST, RSS or sitemap was detected on this source. WXR import is coming in a later phase.'
            );
            return;
        }

        $repository->markRunning($job);

        try {
            $count = match (true) {
                $hasRest => $this->runRestImport($job, $repository),
                $hasRss => $this->runRssImport($job, $repository),
                default => $this->runSitemapImport($job, $repository),
            };
            $repository->markFinished($job);

            Notification::make()
                ->title('Import finished')
                ->body("Imported {$count} items from " . (string)$job->source_host . '.')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            $repository->markFailed($job, $e->getMessage());
            Notification::make()
                ->title('Import failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Run the offline WXR path: parse the uploaded `.xml` export,
     * prime the taxonomy index from its channel blocks, then map
     * each item through {@see WordPressContentMapper} with
     * `importSource=wordpress_wxr` so later re-runs (REST or WXR)
     * can dedupe against the same `(import_source, source_guid)`
     * pair.
     *
     * Unlike the URL-probe path this never calls {@see WordPressSiteProbe}
     * — a WXR export is authoritative by itself, and the operator
     * may not even have the origin site still reachable. We still
     * persist a {@see WordPressMigrationJob} row (mode=`wxr`) so the
     * history surface and the Dusk test have something to assert
     * against.
     */
    public function importWxrFile(): void
    {
        if (!($this->wxrUpload instanceof TemporaryUploadedFile)) {
            $this->dangerNotification('Choose a WXR file first.');
            return;
        }

        $path = $this->wxrUpload->getRealPath();
        if ($path === false || !is_file($path)) {
            $this->dangerNotification('Uploaded WXR file is no longer accessible.');
            return;
        }

        $result = (new WxrImporter())->import($path);

        if ($result->stopReason === WxrImportResult::STOP_UNREACHABLE) {
            $this->dangerNotification(
                'WXR file could not be parsed: ' . ($result->warnings[0] ?? 'unknown format')
            );
            return;
        }

        $repository = app(WordPressMigrationJobRepository::class);
        $filename = $this->wxrUpload->getClientOriginalName();
        $job = $this->upsertWxrJob($result, $filename);
        $repository->markRunning($job);

        try {
            $taxonomy = (new TaxonomyIndex())->prime(
                $result->categories,
                $result->tags,
                $result->users,
            );

            $mapper = new WordPressContentMapper(
                importSource: 'wordpress_wxr',
                taxonomy: $taxonomy,
            );

            $count = 0;
            foreach ($result->items as $dto) {
                $mapper->map($dto);
                $count++;
            }

            $repository->updateProgress($job, [
                'imported' => $count,
                'items_seen' => $result->itemsSeen,
                'media_count' => count($result->media),
                'stop_reason' => $result->stopReason,
                'warnings' => $result->warnings,
                'source_filename' => $filename,
            ]);
            $repository->markFinished($job);

            $this->wxrJobId = $job->id;
            $this->wxrUpload = null;

            Notification::make()
                ->title('Import finished')
                ->body("Imported {$count} items from {$filename}.")
                ->success()
                ->send();
        } catch (\Throwable $e) {
            $repository->markFailed($job, $e->getMessage());
            Notification::make()
                ->title('Import failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Upsert a migration job row for the WXR import.
     *
     * WXR has no URL to probe against, so we derive a synthetic
     * identity `wxr://{host-or-filename}`. When the file's channel
     * carried a `<wp:base_site_url>` (surfaced on DTOs as sourceHost),
     * we key on that so a subsequent REST re-sync over the same
     * origin can reconcile; otherwise we fall back to the uploaded
     * filename so two distinct uploads don't collide.
     */
    private function upsertWxrJob(WxrImportResult $result, string $filename): WordPressMigrationJob
    {
        $sourceHost = null;
        foreach ($result->items as $dto) {
            if ($dto->sourceHost !== null && $dto->sourceHost !== '') {
                $sourceHost = $dto->sourceHost;
                break;
            }
        }

        $sourceUrl = $sourceHost !== null
            ? 'wxr://' . $sourceHost
            : 'wxr://' . $filename;
        $hash = WordPressMigrationJobRepository::hashUrl($sourceUrl);

        $job = WordPressMigrationJob::query()
            ->where('source_url_hash', $hash)
            ->first();

        if ($job === null) {
            $job = new WordPressMigrationJob();
            $job->source_url = $sourceUrl;
            $job->source_url_hash = $hash;
        }
        $job->source_host = $sourceHost ?? $filename;
        $job->mode = 'wxr';
        $job->status = WordPressMigrationJob::STATUS_READY;
        $job->last_probed_at = now();
        $job->save();

        return $job;
    }

    /**
     * Run the REST path end-to-end against `/wp-json/wp/v2/*`.
     *
     * The shape mirrors {@see runRssImport()} — walk, map, record
     * progress — but with two extra moves unique to REST:
     *
     *   1. App-password credential hydration. When the job was probed
     *      with a password, we decrypt it off the model (the cast
     *      auto-decrypts on read) and build a {@see WpAppPasswordCredential}
     *      so every list request carries a Basic header. A malformed
     *      or missing credential drops us back to anon mode; we never
     *      fail the import just because auth is unusable — public
     *      posts/pages can still land.
     *   2. Taxonomy-first pass. Before mapping posts, we prime a
     *      {@see TaxonomyIndex} with the raw categories/tags/users
     *      the walker surfaced. The produced lookup is handed to the
     *      mapper so each post's categories_items / tagging_tagged
     *      rows attach by local id on the same save that writes the
     *      content row — no second pass required.
     *
     * The import_source string for this path is `wordpress_rest` so
     * later re-runs (and the purge logic in both sibling Dusk tests)
     * can tell REST-imported rows apart from RSS/sitemap-imported ones.
     */
    private function runRestImport(WordPressMigrationJob $job, WordPressMigrationJobRepository $repository): int
    {
        $maxItems = (int)($job->options['max_items'] ?? 100);

        $credential = null;
        $raw = (string)($job->encrypted_credentials ?? '');
        if ($raw !== '' && $job->hasValidCredentials()) {
            try {
                $credential = WpAppPasswordCredential::fromString($raw);
            } catch (\InvalidArgumentException) {
                // Malformed stored credential — fall through to anon.
            }
        }

        // Resolve the fetcher through the container so tests can swap
        // in a FakeHttpProbeFetcher. `new WpRestImporter(null, ...)`
        // would hard-wire CurlHttpProbeFetcher and defeat the bind.
        $importer = new WpRestImporter(app(HttpProbeFetcher::class), $credential);
        $result = $importer->walk(
            (string)$job->source_url,
            $this->alreadyImportedGuids(),
            $maxItems,
        );

        $taxonomy = (new TaxonomyIndex())->prime(
            $result->categories,
            $result->tags,
            $result->users,
        );

        $mapper = new WordPressContentMapper(
            importSource: 'wordpress_rest',
            taxonomy: $taxonomy,
        );

        $count = 0;
        foreach ($result->items as $dto) {
            $mapper->map($dto);
            $count++;
        }

        $repository->updateProgress($job, [
            'imported' => $count,
            'pages_fetched' => $result->pagesFetched,
            'stop_reason' => $result->stopReason,
            'media_count' => count($result->media),
            'warnings' => $result->warnings,
        ]);

        return $count;
    }

    /**
     * Run the sitemap path end-to-end against the probed sitemap URL.
     *
     * {@see SitemapPageImporter::walk()} takes care of the
     * crawl-then-extract chain; we hand it the full list of previously
     * imported guids so a re-run skips pages already in the content
     * table instead of re-scraping them.
     */
    private function runSitemapImport(WordPressMigrationJob $job, WordPressMigrationJobRepository $repository): int
    {
        $probe = (array)($job->probe_result ?? []);
        $sitemapUrl = (string)($probe['sitemap_index_url']
            ?? ($job->source_url . '/sitemap.xml'));
        $maxItems = (int)($job->options['max_items'] ?? 100);

        $importer = app(SitemapPageImporter::class);
        $result = $importer->walk($sitemapUrl, $this->alreadyImportedGuids(), $maxItems);

        $mapper = new WordPressContentMapper(importSource: 'wordpress_sitemap');
        $count = 0;
        foreach ($result->items as $dto) {
            $mapper->map($dto);
            $count++;
        }

        $repository->updateProgress($job, [
            'imported' => $count,
            'pages_fetched' => $result->pagesFetched,
            'pages_skipped' => $result->pagesSkipped,
            'stop_reason' => $result->stopReason,
        ]);

        return $count;
    }

    /**
     * Run the RSS path end-to-end against `$job->source_url`.
     *
     * We call {@see RssFeedImporter::walk()} rather than `import()`
     * so pagination + dedupe against previously-imported guids are
     * respected on re-runs. The walker already caps at `max_items`;
     * we default to the configured limit but fall back to a safe
     * 100 when no per-job override has been set.
     *
     * Synchronous on purpose. Phase 9 will move this behind a queue
     * worker with live progress polling; for now a single blocking
     * call keeps the admin UX coherent — the operator clicks, waits,
     * sees a finished notification — without introducing queue
     * infrastructure this early.
     */
    private function runRssImport(WordPressMigrationJob $job, WordPressMigrationJobRepository $repository): int
    {
        $maxItems = (int)($job->options['max_items'] ?? 100);
        $importer = app(RssFeedImporter::class);
        $result = $importer->walk((string)$job->source_url, $this->alreadyImportedGuids(), $maxItems);

        $mapper = new WordPressContentMapper();
        $count = 0;
        foreach ($result->items as $dto) {
            $mapper->map($dto);
            $count++;
        }

        $repository->updateProgress($job, [
            'imported' => $count,
            'pages_fetched' => $result->pagesFetched,
            'stop_reason' => $result->stopReason,
        ]);

        return $count;
    }

    /**
     * Guids we've already imported (from any prior run) so the
     * walker can short-circuit as soon as it sees one. Loading them
     * up-front is O(n) over the content_data rows we wrote, which
     * is fine at any plausible per-site scale.
     *
     * @return list<string>
     */
    private function alreadyImportedGuids(): array
    {
        return DB::table('content_data')
            ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
            ->pluck('field_value')
            ->map(fn ($v) => (string)$v)
            ->values()
            ->all();
    }

    public function getJob(): ?WordPressMigrationJob
    {
        if ($this->jobId === null) {
            return null;
        }
        return WordPressMigrationJob::find($this->jobId);
    }

    public function getProbeSummary(): ?array
    {
        $job = $this->getJob();
        if ($job === null || $job->probe_result === null) {
            return null;
        }

        $probe = $job->probe_result->getArrayCopy();
        return [
            'mode' => $probe['mode'] ?? null,
            'capabilities' => $probe['capabilities'] ?? [],
            'estimated_posts' => $probe['estimated_posts'] ?? null,
            'estimated_pages' => $probe['estimated_pages'] ?? null,
            'rest_namespace' => $probe['rest_namespace'] ?? null,
            'sitemap_index_url' => $probe['sitemap_index_url'] ?? null,
            'warnings' => $probe['warnings'] ?? [],
            'errors' => $probe['errors'] ?? [],
            'source_url' => $probe['source_url'] ?? null,
            'source_host' => $probe['source_host'] ?? null,
            'status' => $job->status,
            'is_usable' => !in_array($job->status, [
                WordPressMigrationJob::STATUS_UNREACHABLE,
                WordPressMigrationJob::STATUS_FAILED,
            ], true),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && (auth()->user()->is_admin ?? false);
    }

    private function summaryLine(WordPressSiteProbeResult $result): string
    {
        $parts = ["Detected mode: {$result->mode}"];
        if ($result->estimatedPosts !== null) {
            $parts[] = "~{$result->estimatedPosts} posts";
        }
        if ($result->estimatedPages !== null) {
            $parts[] = "~{$result->estimatedPages} pages";
        }
        return implode(', ', $parts);
    }

    private function dangerNotification(string $body): void
    {
        Notification::make()
            ->title('Probe failed')
            ->body($body)
            ->danger()
            ->send();
    }
}
