<?php

namespace Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource;
use Modules\WordPressMigration\Models\StagingContent;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use Modules\WordPressMigration\Services\WordPressContentMapper;

/**
 * Phase 9 logs view — per-item success/fail for one migration job.
 *
 * Log rows come from two sources, unified into one view:
 *
 *   staged   — rows in `wp_migration_staging_content` scoped by
 *              `job_id` = current record. These are the items that
 *              have been probed and are sitting in the staging
 *              snapshot, pending commit.
 *   excluded — same table, but with `excluded=true`. The operator
 *              explicitly flagged these so they won't land on live
 *              content during commit.
 *   imported — `content` rows whose `content_data` carries an
 *              `(import_source=wordpress, source_guid=<guid>)`
 *              marker pair, filtered down to the job's own
 *              `source_host` (the host is embedded in the guid
 *              when the source is a WordPress URL, so a
 *              substring match is a safe proxy without adding a
 *              new foreign key to `content_data`).
 *
 * We deliberately do not denormalise onto a dedicated logs table:
 * content_data + staging_content already record what landed and
 * what is still pending. A third table would drift.
 */
class WordPressMigrationLogsPage extends Page
{
    protected static string $resource = WordPressMigrationResource::class;

    protected string $view = 'microweber-module-wordpressmigration::pages.resource-logs';

    public ?int $recordId = null;

    public int $perPage = 50;

    public function mount(int|string $record): void
    {
        $this->recordId = (int) $record;
        // Fail fast if the caller asked for a record that doesn't exist.
        WordPressMigrationJob::query()->findOrFail($this->recordId);
    }

    public function getRecord(): ?WordPressMigrationJob
    {
        if ($this->recordId === null) {
            return null;
        }
        return WordPressMigrationJob::query()->find($this->recordId);
    }

    public function getTitle(): string
    {
        return 'Logs — ' . ($this->getRecord()?->source_host ?? 'WordPress import');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to job')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn (): string => WordPressMigrationResource::getUrl('view', ['record' => $this->recordId])),
            Actions\Action::make('preview')
                ->label('Preview staging')
                ->icon('heroicon-o-eye')
                ->color('primary')
                ->url(fn (): string => '/admin/word-press-migration-preview-page?job=' . $this->recordId),
        ];
    }

    /**
     * @return LengthAwarePaginator<int, array{kind: string, guid: string, title: string, detail: string}>
     */
    public function getLogsProperty(): LengthAwarePaginator
    {
        $merged = $this->importedRows()
            ->concat($this->stagedRows())
            ->values();

        $page = max(1, (int) request()->query('page', '1'));
        $total = $merged->count();
        $offset = ($page - 1) * $this->perPage;
        $items = $merged->slice($offset, $this->perPage)->values()->all();

        return new Paginator(
            $items,
            $total,
            $this->perPage,
            $page,
            ['path' => request()->url(), 'pageName' => 'page']
        );
    }

    public function getStatsProperty(): array
    {
        $staged = StagingContent::query()->where('job_id', $this->recordId ?? 0);

        return [
            'imported' => $this->importedRowsQuery()->count(),
            'staged' => (clone $staged)->where('excluded', false)->count(),
            'excluded' => (clone $staged)->where('excluded', true)->count(),
        ];
    }

    /**
     * @return Collection<int, array{kind: string, guid: string, title: string, detail: string}>
     */
    private function stagedRows(): Collection
    {
        return StagingContent::query()
            ->where('job_id', $this->recordId ?? 0)
            ->orderByDesc('id')
            ->limit(500)
            ->get(['id', 'source_guid', 'title', 'excluded'])
            ->map(fn (StagingContent $row): array => [
                'kind' => $row->excluded ? 'excluded' : 'staged',
                'guid' => (string) $row->source_guid,
                'title' => (string) ($row->title ?? ''),
                'detail' => $row->excluded
                    ? 'Excluded by operator — will not commit'
                    : 'In staging snapshot — pending commit',
            ]);
    }

    /**
     * @return Collection<int, array{kind: string, guid: string, title: string, detail: string}>
     */
    private function importedRows(): Collection
    {
        return $this->importedRowsQuery()
            ->select([
                'content_data.field_value as guid',
                'content.title as title',
                'content.id as content_id',
            ])
            ->orderByDesc('content.id')
            ->limit(500)
            ->get()
            ->map(fn ($row): array => [
                'kind' => 'imported',
                'guid' => (string) $row->guid,
                'title' => (string) ($row->title ?? ''),
                'detail' => 'Committed to live content #' . $row->content_id,
            ]);
    }

    private function importedRowsQuery(): \Illuminate\Database\Query\Builder
    {
        $host = $this->getRecord()?->source_host;

        return DB::table('content_data')
            ->join('content', 'content.id', '=', 'content_data.rel_id')
            ->where('content_data.rel_type', 'Modules\\Content\\Models\\Content')
            ->where('content_data.field_name', WordPressContentMapper::META_SOURCE_GUID)
            ->when($host, fn ($q) => $q->where('content_data.field_value', 'like', '%' . $host . '%'));
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return false;
    }
}
