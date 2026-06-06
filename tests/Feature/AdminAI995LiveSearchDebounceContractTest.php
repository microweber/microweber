<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-06-AI995 — live-search focus loss.
 *
 * Browser reproduction (Livewire v4.2.2, /admin) found the MAIN list-page
 * searches (Filament tables + the bespoke /admin/legacy/users list) already
 * retain focus because they use wire:model.live.debounce — so the literal
 * "list page" symptom no longer reproduces there.
 *
 * The residual instances of the anti-pattern were three live-search inputs
 * wired with a bare wire:model.live (no debounce): the font picker and the
 * module select-tags / select-page option pickers. Without debounce every
 * keystroke fires its own round-trip + DOM re-patch, which races Livewire's
 * focus restoration and drops characters on fast typing. They now carry
 * .debounce.300ms.
 *
 * This pins the fix and guards against the anti-pattern reappearing on these
 * surfaces.
 */
class AdminAI995LiveSearchDebounceContractTest extends TestCase
{
    public static function liveSearchViews(): array
    {
        return [
            'font picker'  => ['src/MicroweberPackages/MicroweberUI/resources/views/livewire/modals/font-picker-modal.blade.php'],
            'select tags'  => ['src/MicroweberPackages/Module/resources/views/admin/option/select-tags.blade.php'],
            'select page'  => ['src/MicroweberPackages/Module/resources/views/admin/option/select-page.blade.php'],
        ];
    }

    #[Test]
    #[DataProvider('liveSearchViews')]
    public function live_search_input_is_debounced(string $relativePath): void
    {
        $src = (string) file_get_contents(base_path($relativePath));

        // The search input must use a debounced live model.
        $this->assertMatchesRegularExpression(
            '/wire:model\.live\.debounce\.\d+ms="search"/',
            $src,
            "The live search in {$relativePath} must be debounced to avoid focus loss."
        );

        // Regression guard: the bare (non-debounced) live model must be gone.
        // Strip Blade comments first so a task-note mentioning the legacy shape
        // can't self-match.
        $stripped = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $src);
        $this->assertDoesNotMatchRegularExpression(
            '/wire:model\.live="search"/',
            $stripped,
            "The bare wire:model.live (no debounce) must not remain in {$relativePath}."
        );
    }
}
