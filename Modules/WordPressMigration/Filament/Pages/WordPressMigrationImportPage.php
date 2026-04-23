<?php

namespace Modules\WordPressMigration\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use Modules\WordPressMigration\Services\Importers\RssFeedImporter;
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

        // Phase 3 scope: only the RSS importer is wired end-to-end.
        // REST/WXR/sitemap importers register their capability in the
        // probe but don't have an execution path here yet — surface a
        // clear message instead of silently falling through to RSS.
        if (!in_array(WordPressSiteProbeResult::MODE_RSS, $capabilities, true)) {
            $this->dangerNotification(
                'No RSS feed was detected on this source. REST/WXR/sitemap import modes are coming in later phases.'
            );
            return;
        }

        $repository->markRunning($job);

        try {
            $count = $this->runRssImport($job, $repository);
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
