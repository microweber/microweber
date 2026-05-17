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
 * Designer's ACK on AI-780 (task-6d65de) flagged that the home demo's
 * posts module uses `template="skin-1"`, not `default.blade.php`, so
 * the new type-aware empty state didn't render where the audit
 * actually fired. Designer dispatched the AI-780a companion rollout
 * inline: apply the same `@php $mwAi780Type = ... @endphp` +
 * `.mw-canvas-empty-state` wrapper to 5 sibling templates:
 *
 *   - templates/skin-1.blade.php (home demo target)
 *   - templates/masonry.blade.php
 *   - templates/dictionary.blade.php
 *   - templates/search.blade.php
 *   - templates/sidebar.blade.php
 *
 * Mechanical copy slice — same `@php` block, same markup, only the
 * host template differs. Each template carries an `AI-780a` marker
 * comment so `git grep AI-780a` finds all 5 rollout sites in one
 * pass. Original AI-780 references at task-2026-05-17-6d65de.
 *
 * Companion CSS (`packages/frontend-assets/resources/assets/css/microweber/css/default.css`
 * `.mw-canvas-empty-state` rules) is unchanged — already serves all
 * 6 templates via the AI-771 cross-package @import architecture.
 */
class Canvas4c289eAI780aCompanionTemplatesContractTest extends TestCase
{
    // ─────────────────────────────────────────────────────────────────────
    // Group A — every companion template carries the empty-state wrapper
    // ─────────────────────────────────────────────────────────────────────

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

    #[Test]
    #[DataProvider('companionTemplates')]
    public function companion_template_carries_empty_state_wrapper(string $path): void
    {
        $blade = (string) file_get_contents(base_path($path));
        $this->assertStringContainsString(
            'class="mw-canvas-empty-state"',
            $blade,
            "{$path} must carry the .mw-canvas-empty-state wrapper."
        );
        $this->assertStringContainsString(
            'data-mw-ai780-content-type=',
            $blade,
            "{$path} must carry the data-mw-ai780-content-type attribute."
        );
        $this->assertStringContainsString('mw-canvas-empty-state__title', $blade);
        $this->assertStringContainsString('mw-canvas-empty-state__body', $blade);
        $this->assertStringContainsString('mw-canvas-empty-state__cta', $blade);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — type-aware copy preserved (post / page / fallback)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('companionTemplates')]
    public function companion_template_carries_post_type_copy(string $path): void
    {
        $blade = (string) file_get_contents(base_path($path));
        $this->assertStringContainsString("__('No posts yet')", $blade);
        $this->assertStringContainsString("__('+ Add post')", $blade);
        $this->assertStringContainsString("admin_url('content/create?content_type=post')", $blade);
    }

    #[Test]
    #[DataProvider('companionTemplates')]
    public function companion_template_carries_page_type_copy(string $path): void
    {
        $blade = (string) file_get_contents(base_path($path));
        $this->assertStringContainsString("__('No pages yet')", $blade);
        $this->assertStringContainsString("__('+ Add page')", $blade);
        $this->assertStringContainsString("admin_url('content/create?content_type=page')", $blade);
    }

    #[Test]
    #[DataProvider('companionTemplates')]
    public function companion_template_carries_fallback_type_copy(string $path): void
    {
        $blade = (string) file_get_contents(base_path($path));
        $this->assertStringContainsString("__('No content yet')", $blade);
        $this->assertStringContainsString("__('+ Add content')", $blade);
        $this->assertStringContainsString("admin_url('content/create')", $blade);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — legacy placeholder gone + AI-104 is_admin gate preserved
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('companionTemplates')]
    public function companion_template_drops_legacy_no_content_added_copy(string $path): void
    {
        // Strip Blade `{{-- ... --}}` comments first — the AI-104
        // explanatory comment may mention "empty-content placeholder"
        // but the LEGACY user-facing string "No content added. Please
        // add content to the module." must NOT appear.
        $blade = (string) file_get_contents(base_path($path));
        $stripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $blade);
        $this->assertStringNotContainsString(
            'No content added. Please add content to the module.',
            $stripped,
            "{$path} must NOT carry the legacy placeholder string after AI-780a rollout."
        );
    }

    #[Test]
    #[DataProvider('companionTemplates')]
    public function companion_template_preserves_ai104_is_admin_gate(string $path): void
    {
        $blade = (string) file_get_contents(base_path($path));
        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*empty\s*\(\s*\$data\s*\)\s*\)[\s\S]*?@if\s*\(\s*is_admin\s*\(\s*\)\s*\)[\s\S]*?mw-canvas-empty-state/s',
            $blade,
            "{$path} empty-state must remain wrapped inside the AI-104 `@if(is_admin())` gate (no public-facing leak)."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — markers + lineage cross-reference to original AI-780
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('companionTemplates')]
    public function companion_template_carries_ai780a_marker_with_lineage(string $path): void
    {
        $blade = (string) file_get_contents(base_path($path));
        $this->assertStringContainsString(
            'AI-780a (task-2026-05-17-4c289e)',
            $blade,
            "{$path} must carry the AI-780a task-id marker so git-grep finds the rollout site."
        );
        // Lineage reference back to original AI-780 ship so future
        // audits can trace pattern origin.
        $this->assertStringContainsString(
            'task-2026-05-17-6d65de',
            $blade,
            "{$path} must reference the original AI-780 task-id (lineage)."
        );
    }
}
