<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Contracts;

/**
 * Stores point-in-time snapshots of the Live-Edit custom CSS / JS so a user can
 * undo recent changes. Implementations keep at most a bounded number of
 * snapshots per (template, type) — the oldest are pruned.
 */
interface SnapshotStoreInterface
{
    /** Record a snapshot of $content for a template + type ('css'|'js'). */
    public function snapshot(string $template, string $type, string $content): void;

    /**
     * Pop (return and remove) the most recent snapshot for a template + type,
     * so the caller can restore it. Returns null when there is nothing to undo.
     */
    public function undo(string $template, string $type): ?string;

    /** How many undo snapshots are currently stored for a template + type. */
    public function count(string $template, string $type): int;
}
