<?php

namespace Modules\WordPressMigration\Services;

/**
 * Aggregated outcome of a {@see StagingCommitter::commit()} pass.
 *
 * The committer processes staging rows in small transactional batches;
 * this object accumulates the per-row outcomes so the caller (Filament
 * page, CLI runner) can surface a single "X committed, Y skipped, Z
 * failed" summary without walking the rows itself.
 *
 * Separate fields for `committed` (stagingId → contentId) and
 * `failed` (stagingId → error message) let callers link back to the
 * originating staging row — useful for retry UIs and log output.
 */
final class CommitReport
{
    /** @var array<int, int> stagingId => newly-written content id */
    public array $committed = [];

    /** @var array<int, string> stagingId => last-seen error message from its batch */
    public array $failed = [];

    /** Excluded rows are counted in bulk — their ids stay in staging as a receipt. */
    public int $skipped = 0;

    public function commit(int $stagingId, int $contentId): void
    {
        $this->committed[$stagingId] = $contentId;
    }

    public function fail(int $stagingId, string $message): void
    {
        $this->failed[$stagingId] = $message;
    }

    public function committedCount(): int
    {
        return count($this->committed);
    }

    public function failedCount(): int
    {
        return count($this->failed);
    }

    public function isSuccessful(): bool
    {
        return $this->failedCount() === 0;
    }
}
