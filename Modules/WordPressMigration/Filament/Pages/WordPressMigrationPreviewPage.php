<?php

namespace Modules\WordPressMigration\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Modules\WordPressMigration\Models\StagingContent;
use Modules\WordPressMigration\Models\WordPressMigrationJob;

/**
 * Preview page for Phase 8 "preview before commit".
 *
 * After a dry-run pass has populated `wp_migration_staging_content`
 * and `wp_migration_staging_media`, the operator lands here to
 * review what will land if they commit. The page deliberately does
 * NOT show any live-content state — it's a snapshot of the staging
 * tables, paginated so a 5000-post import is still tractable.
 *
 * What the operator can do here:
 *   - Browse the staged posts/pages in a paginated list, with title,
 *     source URL, and per-item exclusion checkbox.
 *   - Preview the rendered body of a single item in a modal (uses a
 *     sandboxed iframe with `srcdoc` — no request to the frontend,
 *     no CSRF round-trip, no risk of the preview accidentally
 *     committing).
 *   - Toggle a single item's `excluded` flag via the row checkbox.
 *   - Bulk exclude / bulk include everything on the current page via
 *     dedicated actions (Phase 8 spec calls this out explicitly).
 *
 * What happens when they click Commit lives in the next task — this
 * page only mutates the `excluded` column. Crossing the staging →
 * live boundary is a separate service call so the preview UI stays
 * side-effect-free.
 */
class WordPressMigrationPreviewPage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-eye';
    protected static ?string $title = 'Preview WordPress import';
    protected static ?string $navigationLabel = 'Preview WordPress import';
    protected static string | \UnitEnum | null $navigationGroup = 'Tools';
    protected string $view = 'microweber-module-wordpressmigration::pages.preview';

    /**
     * The staging snapshot belongs to one job at a time. `jobId` is
     * taken from the query string (`?job=<id>`) on mount so the
     * import page can hand off cleanly with a link.
     */
    public ?int $jobId = null;

    public int $perPage = 25;

    /**
     * Single-item preview target. When non-null the view renders
     * the rendered-HTML modal over the list.
     */
    public ?int $previewStagingId = null;

    public function mount(?int $job = null): void
    {
        $this->jobId = $job !== null
            ? (int)$job
            : (int)(request()->query('job') ?? 0);

        if ($this->jobId === 0) {
            $latest = WordPressMigrationJob::query()
                ->orderByDesc('id')
                ->value('id');
            $this->jobId = $latest !== null ? (int)$latest : null;
        }
    }

    /**
     * Toggle a single row's exclusion via the row checkbox.
     */
    public function toggleExcluded(int $stagingId): void
    {
        $row = StagingContent::query()
            ->where('job_id', $this->jobId)
            ->where('id', $stagingId)
            ->first();

        if ($row === null) {
            $this->dangerNotification('That row is no longer in the staging snapshot.');
            return;
        }

        $row->excluded = ! $row->excluded;
        $row->save();
    }

    /**
     * Flip every staged row for this job to `excluded=true`. The
     * operator can then un-exclude the few they want to keep,
     * which is faster than ticking hundreds of boxes manually.
     */
    public function bulkExcludeAll(): void
    {
        if ($this->jobId === null) {
            return;
        }
        $n = StagingContent::query()
            ->where('job_id', $this->jobId)
            ->update(['excluded' => true]);

        Notification::make()
            ->title('Bulk exclude')
            ->body("Marked {$n} staged items as excluded. They won't be committed.")
            ->success()
            ->send();
    }

    public function bulkIncludeAll(): void
    {
        if ($this->jobId === null) {
            return;
        }
        $n = StagingContent::query()
            ->where('job_id', $this->jobId)
            ->update(['excluded' => false]);

        Notification::make()
            ->title('Bulk include')
            ->body("Re-included {$n} staged items for the commit batch.")
            ->success()
            ->send();
    }

    public function openPreview(int $stagingId): void
    {
        $this->previewStagingId = $stagingId;
    }

    public function closePreview(): void
    {
        $this->previewStagingId = null;
    }

    /**
     * Paginated slice of the staging snapshot for the view.
     * Rendered by the blade template; kept as a method rather than
     * a public property so it re-queries on every Livewire render
     * (cheap: staging is narrow and index-scanned).
     */
    public function getRowsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return StagingContent::query()
            ->where('job_id', $this->jobId ?? 0)
            ->orderBy('id')
            ->paginate($this->perPage);
    }

    public function getPreviewRowProperty(): ?StagingContent
    {
        if ($this->previewStagingId === null || $this->jobId === null) {
            return null;
        }
        return StagingContent::query()
            ->where('job_id', $this->jobId)
            ->where('id', $this->previewStagingId)
            ->first();
    }

    public function getStatsProperty(): array
    {
        $base = StagingContent::query()->where('job_id', $this->jobId ?? 0);
        return [
            'total' => (clone $base)->count(),
            'excluded' => (clone $base)->where('excluded', true)->count(),
        ];
    }

    private function dangerNotification(string $message): void
    {
        Notification::make()
            ->title('Preview error')
            ->body($message)
            ->danger()
            ->send();
    }
}
