<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-49266a / AI-710 CHANGE — Vue panel h3 still
 * served "Element Style Editor" at runtime even though source
 * + bundle were updated. Jira:
 *   https://microweber.atlassian.net/browse/AI-710
 *
 * Designer verified the original AI-710 ship: right-rail button
 * aria-label/title rename = clean; CSS hook preserved; click
 * toggles. But runtime DOM probe at
 *   #mw-element-style-editor-app-container > div > h3.fs-2.font-weight-bold
 * still served "Element Style Editor" — the old text.
 *
 * Root cause: this `<h3>` is rendered as a STATIC element inside
 * the iframe-page.blade.php template (line ~1924), not by any
 * Vue component. The Vue `StyleEditor.vue` h3 was updated by
 * the original AI-710 ship — but that Vue h3 renders inside its
 * own card component, NOT inside `#mw-element-style-editor-app-
 * container`. The Blade-side static h3 was missed.
 *
 * Fix: rename the static h3 in iframe-page.blade.php from
 * "Element Style Editor" to "Element styles" so the rendered DOM
 * + the Vue source agree.
 */
class LiveEdit49266aAI710ChangePanelH3RenameContractTest extends TestCase
{
    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/iframe-page.blade.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — renamed h3
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function static_h3_inside_ese_container_now_reads_element_styles(): void
    {
        // Slice the container block + assert the h3 inside reads
        // "Element styles" (post-CHANGE).
        $start = strpos($this->blade, 'id="mw-element-style-editor-app-container"');
        $this->assertNotFalse($start, '#mw-element-style-editor-app-container element must be present.');
        $end = strpos($this->blade, '</div>', $start + 1);
        // Walk 5 closing divs to escape the nested structure.
        for ($i = 0; $i < 5 && $end !== false; $i++) {
            $end = strpos($this->blade, '</div>', $end + 1);
        }
        $slice = substr($this->blade, $start, max(1, ($end ?: strlen($this->blade)) - $start));

        $this->assertMatchesRegularExpression(
            '/<h3[^>]*class="[^"]*\bfs-2\b[^"]*\bfont-weight-bold\b[^"]*">\s*Element styles\s*<\/h3>/',
            $slice,
            'Static h3 inside #mw-element-style-editor-app-container must read "Element styles" per AI-710 CHANGE.'
        );
    }

    #[Test]
    public function legacy_element_style_editor_h3_text_is_gone(): void
    {
        // Comment-stripped scan — Blade `{{-- … --}}` migration-
        // rationale comment legitimately mentions the OLD text.
        // LESSONS selector-self-match guard, hit 7+ times this
        // session.
        $rules = preg_replace('/\{\{--.*?--\}\}/s', '', $this->blade);
        $this->assertDoesNotMatchRegularExpression(
            '/<h3[^>]*>\s*Element Style Editor\s*<\/h3>/',
            $rules,
            'No <h3> may render the literal text "Element Style Editor" in the rendered Blade output.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — structural preservation (container + close button)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function container_id_and_layout_classes_preserved(): void
    {
        // The wrapper div + the flex layout for the h3 + close-X
        // span must remain — only the h3 text changed.
        $this->assertStringContainsString(
            'id="mw-element-style-editor-app-container"',
            $this->blade,
            'Container ID must remain.'
        );
        $this->assertStringContainsString(
            'class="d-flex align-items-center justify-content-between mb-3"',
            $this->blade,
            'Header row layout classes must remain.'
        );
        $this->assertStringContainsString(
            'x-close-modal-link',
            $this->blade,
            'Close-X span (.x-close-modal-link) must remain.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_change_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-17-49266a', $this->blade);
        $this->assertStringContainsString('AI-710 CHANGE', $this->blade);
    }
}
