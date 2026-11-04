<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Support;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\TemplateCustomCss\Contracts\SnapshotStoreInterface;

/**
 * Database-backed undo history for Live-Edit CSS/JS. Each change records a
 * snapshot of the PREVIOUS content in `live_edit_style_snapshots`; undo pops the
 * most recent one. History is capped per (template, type) — default 1000 — and
 * the oldest rows are pruned, so a user can step back up to that many changes.
 *
 * Every DB access is wrapped so a storage failure can never break a CSS/JS save.
 */
class DbSnapshotStore implements SnapshotStoreInterface
{
    public const TABLE = 'live_edit_style_snapshots';

    public function __construct(private int $max = 1000) {}

    public function snapshot(string $template, string $type, string $content): void
    {
        try {
            DB::table(self::TABLE)->insert([
                'template' => $template,
                'type' => $type,
                'content' => $content,
                'user_id' => $this->currentUserId(),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $this->prune($template, $type);
        } catch (\Throwable $e) {
            // Non-fatal: undo history is best-effort and must never block a save.
        }
    }

    public function undo(string $template, string $type): ?string
    {
        try {
            $row = DB::table(self::TABLE)
                ->where('template', $template)->where('type', $type)
                ->orderByDesc('id')->first();
            if (!$row) {
                return null;
            }
            DB::table(self::TABLE)->where('id', $row->id)->delete();

            return (string) $row->content;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function count(string $template, string $type): int
    {
        try {
            return (int) DB::table(self::TABLE)
                ->where('template', $template)->where('type', $type)->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /** Keep only the newest $max rows per (template, type). */
    private function prune(string $template, string $type): void
    {
        try {
            $keepFromId = DB::table(self::TABLE)
                ->where('template', $template)->where('type', $type)
                ->orderByDesc('id')->offset($this->max - 1)->limit(1)->value('id');
            if ($keepFromId) {
                DB::table(self::TABLE)
                    ->where('template', $template)->where('type', $type)
                    ->where('id', '<', $keepFromId)->delete();
            }
        } catch (\Throwable $e) {
        }
    }

    private function currentUserId(): ?int
    {
        try {
            if (function_exists('auth') && auth()->check()) {
                return (int) auth()->id();
            }
        } catch (\Throwable $e) {
        }

        return null;
    }
}
