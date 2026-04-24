<?php

namespace Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource;
use Modules\WordPressMigration\Models\WordPressMigrationJob;

/**
 * Phase 9 job-detail view with real-time progress polling.
 *
 * On top of the default Filament infolist (status, probe result,
 * progress JSON), this page adds a polled counter widget that
 * refreshes every {@see self::POLL_INTERVAL_SECONDS}s while the
 * job is running. The worker writes `processed`, `total`, and
 * `failed` onto `wp_migration_jobs.progress` as it ticks; polling
 * re-reads those numbers so the operator can watch the import
 * move without reloading the page.
 *
 * Polling only emits while the job is in a non-terminal status
 * (probing / running). Once the job is finished, failed, or
 * canceled there is nothing new to read, so we stop polling to
 * avoid pointless DB round-trips.
 *
 * Progress key aliases (different importers emit different names):
 *   processed ← progress.processed | progress.imported | progress.done
 *   total     ← progress.total     | probe_result.estimated_posts
 *                                    + probe_result.estimated_pages
 *   failed    ← progress.failed    (defaults to 0)
 *
 * ETA:
 *   - rate = processed / elapsed seconds since started_at
 *   - eta_seconds = (total - processed) / rate, or null if
 *     we can't compute it yet (no total or no processed).
 */
class ViewWordPressMigration extends ViewRecord
{
    protected static string $resource = WordPressMigrationResource::class;

    public const POLL_INTERVAL_SECONDS = 3;

    protected string $view = 'microweber-module-wordpressmigration::pages.resource-view';

    protected function getHeaderActions(): array
    {
        /** @var WordPressMigrationJob $record */
        $record = $this->record;

        return [
            Actions\Action::make('preview')
                ->label('Preview staging')
                ->icon('heroicon-o-eye')
                ->color('primary')
                ->url('/admin/word-press-migration-preview-page?job=' . $record->id),
            Actions\Action::make('logs')
                ->label('Logs')
                ->icon('heroicon-o-list-bullet')
                ->color('gray')
                ->url(WordPressMigrationResource::getUrl('logs', ['record' => $record])),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Job')
                    ->schema([
                        TextEntry::make('id')->label('ID'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                WordPressMigrationJob::STATUS_PROBING => 'gray',
                                WordPressMigrationJob::STATUS_READY => 'info',
                                WordPressMigrationJob::STATUS_RUNNING => 'warning',
                                WordPressMigrationJob::STATUS_FINISHED => 'success',
                                WordPressMigrationJob::STATUS_UNREACHABLE,
                                WordPressMigrationJob::STATUS_FAILED => 'danger',
                                WordPressMigrationJob::STATUS_CANCELED => 'gray',
                                default => 'gray',
                            }),
                        TextEntry::make('mode')->placeholder('—'),
                        TextEntry::make('source_host'),
                        TextEntry::make('source_url')->columnSpanFull(),
                        TextEntry::make('last_probed_at')
                            ->dateTime('M d, Y H:i')
                            ->placeholder('—'),
                        TextEntry::make('started_at')
                            ->dateTime('M d, Y H:i')
                            ->placeholder('—'),
                        TextEntry::make('finished_at')
                            ->dateTime('M d, Y H:i')
                            ->placeholder('—'),
                        TextEntry::make('last_error')
                            ->columnSpanFull()
                            ->placeholder('—'),
                    ])
                    ->columns(2),
                Section::make('Probe result')
                    ->schema([
                        KeyValueEntry::make('probe_result')
                            ->state(fn (WordPressMigrationJob $record): array => $record->probe_result
                                ? $record->probe_result->getArrayCopy()
                                : [])
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),
                Section::make('Progress')
                    ->schema([
                        KeyValueEntry::make('progress')
                            ->state(fn (WordPressMigrationJob $record): array => $record->progress
                                ? $record->progress->getArrayCopy()
                                : [])
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),
            ]);
    }

    /**
     * Re-read the record so the polled blade fragment picks up
     * the worker's latest tick.
     */
    public function refreshProgress(): void
    {
        if ($this->record instanceof WordPressMigrationJob && $this->record->exists) {
            $this->record->refresh();
        }
    }

    /**
     * Live counters + ETA, computed fresh each poll tick.
     *
     * @return array{
     *   processed: int,
     *   total: ?int,
     *   failed: int,
     *   percentage: ?int,
     *   elapsed_seconds: ?int,
     *   eta_seconds: ?int,
     *   eta_human: ?string,
     *   is_running: bool,
     *   status: string,
     *   should_poll: bool,
     * }
     */
    public function getProgressStatsProperty(): array
    {
        /** @var WordPressMigrationJob|null $record */
        $record = $this->record;

        if (! $record instanceof WordPressMigrationJob) {
            return self::emptyStats('unknown');
        }

        $progress = $record->progress?->getArrayCopy() ?? [];

        $processed = (int) ($progress['processed']
            ?? $progress['imported']
            ?? $progress['done']
            ?? 0);

        $total = $this->resolveTotal($record, $progress);
        $failed = (int) ($progress['failed'] ?? 0);

        $percentage = $total !== null && $total > 0
            ? (int) floor(min(100, ($processed / $total) * 100))
            : null;

        // Carbon 3 returns signed diffs — absolute-value so we don't
        // flip the sign of elapsed time when `started_at` is in the past
        // (which is the normal case for a running import).
        $elapsed = $record->started_at !== null
            ? (int) abs(Carbon::now()->diffInSeconds($record->started_at))
            : null;

        $eta = $this->resolveEta($processed, $total, $elapsed);

        $status = (string) $record->status;
        $isRunning = $status === WordPressMigrationJob::STATUS_RUNNING
            || $status === WordPressMigrationJob::STATUS_PROBING;

        return [
            'processed' => $processed,
            'total' => $total,
            'failed' => $failed,
            'percentage' => $percentage,
            'elapsed_seconds' => $elapsed,
            'eta_seconds' => $eta,
            'eta_human' => $eta !== null ? self::humanDuration($eta) : null,
            'is_running' => $isRunning,
            'status' => $status,
            'should_poll' => $isRunning,
        ];
    }

    /**
     * @param array<string, mixed> $progress
     */
    private function resolveTotal(WordPressMigrationJob $record, array $progress): ?int
    {
        $raw = $progress['total'] ?? null;
        if ($raw !== null) {
            return (int) $raw;
        }

        $probe = $record->probe_result?->getArrayCopy() ?? [];
        $posts = $probe['estimated_posts'] ?? null;
        $pages = $probe['estimated_pages'] ?? null;

        if ($posts === null && $pages === null) {
            return null;
        }

        return (int) ($posts ?? 0) + (int) ($pages ?? 0);
    }

    private function resolveEta(int $processed, ?int $total, ?int $elapsed): ?int
    {
        if ($total === null || $processed <= 0 || $elapsed === null || $elapsed <= 0) {
            return null;
        }
        $remaining = $total - $processed;
        if ($remaining <= 0) {
            return 0;
        }
        $rate = $processed / $elapsed;
        if ($rate <= 0) {
            return null;
        }
        return (int) ceil($remaining / $rate);
    }

    private static function humanDuration(int $seconds): string
    {
        if ($seconds <= 0) {
            return '0s';
        }
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $secs = $seconds % 60;

        $parts = [];
        if ($hours > 0) {
            $parts[] = "{$hours}h";
        }
        if ($minutes > 0 || $hours > 0) {
            $parts[] = "{$minutes}m";
        }
        $parts[] = "{$secs}s";

        return implode(' ', $parts);
    }

    /**
     * @return array{
     *   processed: int,
     *   total: ?int,
     *   failed: int,
     *   percentage: ?int,
     *   elapsed_seconds: ?int,
     *   eta_seconds: ?int,
     *   eta_human: ?string,
     *   is_running: bool,
     *   status: string,
     *   should_poll: bool,
     * }
     */
    private static function emptyStats(string $status): array
    {
        return [
            'processed' => 0,
            'total' => null,
            'failed' => 0,
            'percentage' => null,
            'elapsed_seconds' => null,
            'eta_seconds' => null,
            'eta_human' => null,
            'is_running' => false,
            'status' => $status,
            'should_poll' => false,
        ];
    }
}
