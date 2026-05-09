<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-112 / AI-120 / TICKET-BO — wire:model.live debounce sweep
 * regression coverage.
 *
 * Pins:
 *   - Zero `wire:model.live="..."` occurrences (without a debounce
 *     modifier) remain in `Modules/...star-star.../*.blade.php`. Every
 *     live-binding site must carry `.debounce.500ms` so typing into
 *     a search box doesn't fire one Livewire round-trip per keystroke.
 *
 * Note: the brief allows `Modules/` only (zero exceptions). Other
 * directories (`src/`, `Templates/`, `packages/`) follow the same
 * convention but are not gated by this contract test (their per-
 * package conventions may differ).
 *
 * Style after the cycle-52..111 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class WireModelDebounceSweepContractTest extends TestCase
{
    #[Test]
    public function no_bare_wire_model_live_remains_in_modules(): void
    {
        $modulesDir = base_path('Modules');
        if (!is_dir($modulesDir)) {
            $this->markTestSkipped('Modules/ not present');
        }

        $offenders = [];
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($modulesDir));
        foreach ($rii as $file) {
            if (!$file->isFile() || !str_ends_with($file->getPathname(), '.blade.php')) {
                continue;
            }
            $src = file_get_contents($file->getPathname());

            // Match bare `wire:model.live="..."` — the negative-lookahead
            // ensures we don't false-match the post-fix
            // `wire:model.live.debounce.500ms="..."` shape.
            if (preg_match_all(
                '/wire:model\\.live(?!\\.[a-z0-9]+)\\s*=\\s*"[^"]+"/',
                $src,
                $matches,
                PREG_OFFSET_CAPTURE
            )) {
                foreach ($matches[0] as $hit) {
                    [$matched, $offset] = $hit;
                    $line = substr_count($src, "\n", 0, $offset) + 1;
                    $rel = str_replace(base_path() . '/', '', $file->getPathname());
                    $offenders[] = "{$rel}:{$line}  {$matched}";
                }
            }
        }

        $this->assertEmpty(
            $offenders,
            "AI-120 / TICKET-BO: every `wire:model.live` in Modules/ must carry `.debounce.500ms`. Offenders:\n  "
            . implode("\n  ", $offenders)
        );
    }
}
