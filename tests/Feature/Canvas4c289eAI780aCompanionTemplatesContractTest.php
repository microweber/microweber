<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-4c289e / AI-780a — companion-template rollout of the
 * AI-780 type-aware empty state. Jira: https://microweber.atlassian.net/browse/AI-780
 *
 * Original AI-780a applied the same inline `@php $mwEmptyType = … @endphp` +
 * `.mw-canvas-empty-state` markup to 5 sibling list templates (skin-1,
 * masonry, dictionary, search, sidebar) so the home demo's skin-1 module
 * got the type-aware empty state too.
 *
 * **task-2026-06-07-pmprod (CHANGE)** — that inline block is exactly the
 * duplication this refactor removed. The logic now lives in
 * \Modules\Content\Services\ContentModuleEmptyState and the markup in the
 * shared modules.content::partials.module-empty-state; all 5 companions (and
 * default) just `@include` the partial. This test was rewritten in place
 * (pin-evolution) to pin the DELEGATION — each companion includes the shared
 * partial and no longer carries its own inline copy/markup — which is the
 * regression that would re-introduce per-template drift. The behavioural
 * copy/CTA coverage moved to the service-level tests in
 * Canvas6d65deAI780ContentModuleEmptyStateContractTest +
 * ContentEmptyStatePmprodContractTest.
 */
class Canvas4c289eAI780aCompanionTemplatesContractTest extends TestCase
{
    public static function companionTemplates(): array
    {
        return [
            'skin-1 (home demo target)' => ['Modules/Content/resources/views/templates/skin-1.blade.php'],
            'masonry' => ['Modules/Content/resources/views/templates/masonry.blade.php'],
            'dictionary' => ['Modules/Content/resources/views/templates/dictionary.blade.php'],
            'search' => ['Modules/Content/resources/views/templates/search.blade.php'],
            'sidebar' => ['Modules/Content/resources/views/templates/sidebar.blade.php'],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — every companion delegates to the shared empty-state partial
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('companionTemplates')]
    public function companion_template_delegates_to_shared_partial(string $path): void
    {
        $blade = (string) file_get_contents(base_path($path));
        $this->assertStringContainsString(
            "@include('modules.content::partials.module-empty-state'",
            $blade,
            "{$path} must render the shared empty-state partial."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — the inline copy/logic/markup is GONE (de-duplication)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('companionTemplates')]
    public function companion_template_no_longer_carries_inline_logic_or_markup(string $path): void
    {
        $blade = (string) file_get_contents(base_path($path));

        $this->assertStringNotContainsString(
            'mwEmptyType = match',
            $blade,
            "{$path} must NOT carry the inline empty-state inference block anymore."
        );
        $this->assertStringNotContainsString(
            'mw-canvas-empty-state__title',
            $blade,
            "{$path} must NOT carry the inline empty-state markup anymore."
        );
        // The Laravel __() copy keys moved to the service as _e($s, true).
        $this->assertStringNotContainsString(
            "__('No posts yet')",
            $blade,
            "{$path} must NOT carry inline copy keys — they moved to ContentModuleEmptyState."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — legacy placeholder string never resurfaces
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('companionTemplates')]
    public function companion_template_drops_legacy_no_content_added_copy(string $path): void
    {
        $blade = (string) file_get_contents(base_path($path));
        $stripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $blade);
        $this->assertStringNotContainsString(
            'No content added. Please add content to the module.',
            $stripped,
            "{$path} must NOT carry the legacy placeholder string."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — the AI-104 is_admin gate is preserved (now in the partial)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function shared_partial_preserves_ai104_is_admin_gate(): void
    {
        // The is_admin() gate that kept the editor hint off public pages now
        // lives once in the shared partial, covering all companions at once.
        $partial = (string) file_get_contents(base_path(
            'Modules/Content/resources/views/partials/module-empty-state.blade.php'
        ));
        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*is_admin\s*\(\s*\)\s*\)[\s\S]*?mw-canvas-empty-state/s',
            $partial,
            'The shared partial must keep the AI-104 `@if(is_admin())` gate (no public-facing leak).'
        );
    }
}
