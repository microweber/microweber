<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-b0b48f / AI-711 (Medium) — Quick AI Edit panel:
 * distinguish headings from paragraphs in row labels.
 *
 * Designer dispatch (DESIGN_AUDIT.md L2.4, per-ticket email
 * 2026-05-16T13:40): the Quick AI Edit panel groups ALL text under
 * one heading-number label (every editable text row shows the same
 * verbose `Heading N`), so users can't tell at a glance which input
 * affects which DOM element until they edit and watch the canvas.
 *
 * Fix: differentiate row labels by element type with per-section
 * counters:
 *   - Heading rows: H1 / H2 / H3 (the tag name itself).
 *   - Paragraph rows: P1 / P2 / … (resets per heading-bounded
 *     subsection within each card).
 *   - Links: LINK 1 / LINK 2 / …
 *   - Buttons: BUTTON 1 / BUTTON 2 / …
 *   - Images: IMAGE ALT 1 / IMAGE ALT 2 / …
 *
 * Number sequence is per-subsection — encountering a new heading
 * resets the P / LINK / BUTTON / IMAGE-ALT counters so labels
 * read `H1 / P1 / P2 / H2 / P1` naturally.
 *
 * All labels use ESE metadata typography: --font-label /
 * --weight-label / --letter-label.
 *
 * Two-surface implementation:
 *
 *   1. quick-ai-edit.js
 *      - New top-level helper `computeQuickAiEditLabel(obj, sectionId,
 *        state)` that mutates a caller-owned state map. Returns the
 *        per-section label string. Heading branch resets all
 *        non-heading counters within the section.
 *      - `_text(obj)` builder updated to prefer `obj.label` over
 *        the legacy `tagMap[obj.tag] || obj.tag` fallback.
 *      - `editor()` method updated to compute `obj.label` AFTER
 *        sectionId is known BEFORE the gui.build() call, using a
 *        closure-scoped `quickAiEditLabelState` map.
 *
 *   2. general-styles.css
 *      - `.quick-ai-card .form-control-live-edit-label-wrapper
 *        .live-edit-label` rule consuming --font-label /
 *        --weight-label / --letter-label / --ese-text-muted per
 *        spec, with literal fallbacks on every var().
 *
 * Token-scoping note (per SOUL #108 spec-doc-nit): every var() in
 * the AI-711 CSS slice carries a literal fallback because the
 * Quick AI Edit panel renders via `mw.top().dialog(...)` which
 * may render outside the .mw-live-edit-page scope where some ESE
 * tokens are root-scoped. Literal fallbacks protect against
 * stylesheet load-order regressions.
 */
class LiveEditB0b48fAI711QuickAiEditLabelsContractTest extends TestCase
{
    private string $quickAiEditJs;
    private string $generalStyles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->quickAiEditJs = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/components/quick-ai-edit.js'
        ));
        $this->generalStyles = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Label-computation helper
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function compute_label_helper_is_defined(): void
    {
        $this->assertMatchesRegularExpression(
            '/function\s+computeQuickAiEditLabel\s*\(\s*obj\s*,\s*sectionId\s*,\s*state\s*\)/',
            $this->quickAiEditJs,
            'computeQuickAiEditLabel(obj, sectionId, state) helper must be defined as the per-section label generator.'
        );
    }

    #[Test]
    public function helper_heading_branch_returns_tag_and_resets_counters(): void
    {
        // The H1..H6 branch must (a) return the tag name verbatim
        // ("H1", "H2", etc.) AND (b) reset the section counter
        // state so paragraph numbering restarts under the heading
        // per spec `H1 / P1 / P2 / H2 / P1`.
        $this->assertMatchesRegularExpression(
            "/if\\s*\\(\\s*\\/\\^H\\[1-6\\]\\\$\\/\\.test\\(\\s*tag\\s*\\)\\s*\\)/",
            $this->quickAiEditJs,
            'Heading branch must match /^H[1-6]$/.test(tag) to cover H1..H6.'
        );
        // The branch body must reset counters (assign a fresh bag).
        $this->assertMatchesRegularExpression(
            "/state\\[sectionId\\]\\s*=\\s*\\{\\s*p:\\s*0[\\s\\S]{0,200}link:\\s*0[\\s\\S]{0,200}button:\\s*0[\\s\\S]{0,200}imgAlt:\\s*0/",
            $this->quickAiEditJs,
            'Heading branch must reset state[sectionId] to a fresh {p:0, link:0, button:0, imgAlt:0} counter bag.'
        );
    }

    #[Test]
    public function helper_paragraph_branch_returns_p_prefixed_number(): void
    {
        // Paragraphs include P / SPAN / DIV / LI / BLOCKQUOTE per
        // sensible interpretation of "paragraph rows" — all body
        // text containers. Returns "P" + counter value.
        $this->assertMatchesRegularExpression(
            "/tag\\s*===\\s*[\"']P[\"'][\\s\\S]{0,200}return\\s+[\"']P[\"']\\s*\\+\\s*counters\\.p/",
            $this->quickAiEditJs,
            'P branch must increment counters.p and return "P" + counters.p.'
        );
    }

    #[Test]
    public function helper_link_branch_returns_link_prefixed_number(): void
    {
        $this->assertMatchesRegularExpression(
            "/tag\\s*===\\s*[\"']A[\"'][\\s\\S]{0,200}return\\s+[\"']LINK\\s+[\"']\\s*\\+\\s*counters\\.link/",
            $this->quickAiEditJs,
            'A (anchor) branch must increment counters.link and return "LINK " + counters.link.'
        );
    }

    #[Test]
    public function helper_button_branch_returns_button_prefixed_number(): void
    {
        $this->assertMatchesRegularExpression(
            "/tag\\s*===\\s*[\"']BUTTON[\"'][\\s\\S]{0,200}return\\s+[\"']BUTTON\\s+[\"']\\s*\\+\\s*counters\\.button/",
            $this->quickAiEditJs,
            'BUTTON branch must increment counters.button and return "BUTTON " + counters.button.'
        );
    }

    #[Test]
    public function helper_image_branch_returns_image_alt_prefixed_number(): void
    {
        $this->assertMatchesRegularExpression(
            "/tag\\s*===\\s*[\"']IMG[\"'][\\s\\S]{0,200}return\\s+[\"']IMAGE ALT\\s+[\"']\\s*\\+\\s*counters\\.imgAlt/",
            $this->quickAiEditJs,
            'IMG branch must increment counters.imgAlt and return "IMAGE ALT " + counters.imgAlt.'
        );
    }

    #[Test]
    public function helper_initialises_section_state_lazily(): void
    {
        // First time we see a sectionId, the state map must seed
        // it with the four-counter bag.
        $this->assertMatchesRegularExpression(
            "/if\\s*\\(\\s*!state\\[sectionId\\]\\s*\\)\\s*\\{[\\s\\S]{0,300}state\\[sectionId\\]\\s*=\\s*\\{\\s*p:\\s*0[\\s\\S]{0,200}link:\\s*0[\\s\\S]{0,200}button:\\s*0[\\s\\S]{0,200}imgAlt:\\s*0/",
            $this->quickAiEditJs,
            'Helper must lazily initialise state[sectionId] with a {p, link, button, imgAlt} bag.'
        );
    }

    #[Test]
    public function helper_fallback_returns_raw_tag(): void
    {
        // Unknown tags (TABLE, FIGURE, etc.) — fallback to raw tag.
        // Pin the `return tag;` line at the END of the helper as
        // the catch-all.
        $this->assertMatchesRegularExpression(
            '/function\s+computeQuickAiEditLabel[\s\S]+?return\s+tag;\s*\n\s*\}/',
            $this->quickAiEditJs,
            'Helper must end with `return tag;` as the catch-all fallback for unmapped element types.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Builder consumes computed label
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function text_builder_prefers_obj_label(): void
    {
        // `_text(obj)` must prefer `obj.label` over the legacy
        // tagMap lookup so the new per-section labels actually
        // render in the UI.
        $this->assertMatchesRegularExpression(
            '/obj\.label\s*\|\|\s*tagMap\[obj\.tag\]\s*\|\|\s*obj\.tag/',
            $this->quickAiEditJs,
            "`_text(obj)` must render `obj.label || tagMap[obj.tag] || obj.tag` so the per-section label wins when set."
        );
    }

    #[Test]
    public function editor_loop_assigns_label_before_build(): void
    {
        // The editor() method must compute the label AFTER sectionId
        // is known but BEFORE gui.build() is called — otherwise the
        // builder wouldn't see `obj.label`.
        $this->assertMatchesRegularExpression(
            '/obj\.label\s*=\s*computeQuickAiEditLabel\(\s*obj,\s*sectionId,\s*quickAiEditLabelState\s*\)[\s\S]{0,200}this\.gui\.build\(obj/',
            $this->quickAiEditJs,
            'editor() must call obj.label = computeQuickAiEditLabel(obj, sectionId, quickAiEditLabelState) BEFORE this.gui.build(obj, type).'
        );
    }

    #[Test]
    public function editor_loop_uses_closure_scoped_label_state(): void
    {
        // The state map must be defined INSIDE editor() (closure
        // scope) so it resets on every editor() call — otherwise
        // counters would accumulate across panel rebuilds.
        $this->assertMatchesRegularExpression(
            '/const\s+quickAiEditLabelState\s*=\s*\{\s*\}\s*;[\s\S]{0,500}this\.api\.collect/',
            $this->quickAiEditJs,
            'editor() must define `const quickAiEditLabelState = {};` BEFORE this.api.collect(...) so counters reset on each panel rebuild.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — CSS metadata typography
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function live_edit_label_uses_font_label_token(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.quick-ai-card\s+\.form-control-live-edit-label-wrapper\s+\.live-edit-label\s*\{[^}]*font-size:\s*var\(--font-label,\s*11px\)/s',
            $this->generalStyles,
            'AI-711 label rule must consume var(--font-label, 11px) per spec.'
        );
    }

    #[Test]
    public function live_edit_label_uses_weight_label_token(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.quick-ai-card[\s\S]*?\.live-edit-label\s*\{[^}]*font-weight:\s*var\(--weight-label,\s*500\)/s',
            $this->generalStyles,
            'AI-711 label rule must consume var(--weight-label, 500) per spec.'
        );
    }

    #[Test]
    public function live_edit_label_uses_letter_label_token(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.quick-ai-card[\s\S]*?\.live-edit-label\s*\{[^}]*letter-spacing:\s*var\(--letter-label,\s*0\.01em\)/s',
            $this->generalStyles,
            'AI-711 label rule must consume var(--letter-label, 0.01em) per spec.'
        );
    }

    #[Test]
    public function live_edit_label_uses_muted_text_colour(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.quick-ai-card[\s\S]*?\.live-edit-label\s*\{[^}]*color:\s*var\(--ese-text-muted/s',
            $this->generalStyles,
            'AI-711 label rule must use --ese-text-muted so labels read as metadata, not primary text.'
        );
    }

    #[Test]
    public function live_edit_label_scope_uses_quick_ai_card_prefix(): void
    {
        // Scope verification: AI-711 must NOT touch the bare
        // `.live-edit-label` selector globally — that would affect
        // unrelated panels (mw-dialog-skin-default, etc.).
        //
        // Slice from the END of the AI-711 docblock (`*/` after the
        // marker line) to either the next AI-block marker or EOF —
        // we only want to inspect the actual CSS rules, NOT the
        // docblock prose which legitimately mentions the selector
        // chain. LESSONS selector-self-match guard reapplied.
        $marker = strpos($this->generalStyles, 'AI-711 — Quick AI Edit row labels');
        $this->assertNotFalse($marker, 'AI-711 task marker must be present in general-styles.css.');
        $docblockEnd = strpos($this->generalStyles, '*/', $marker);
        $this->assertNotFalse($docblockEnd, 'AI-711 docblock close `*/` must follow the marker.');
        $sliceStart = $docblockEnd + 2;
        $nextBlock = strpos($this->generalStyles, '/* ──', $sliceStart);
        $sliceEnd = $nextBlock !== false ? $nextBlock : strlen($this->generalStyles);
        $slice = substr($this->generalStyles, $sliceStart, $sliceEnd - $sliceStart);

        $this->assertStringContainsString(
            '.quick-ai-card .form-control-live-edit-label-wrapper .live-edit-label',
            $slice,
            'AI-711 CSS rule must scope to `.quick-ai-card .form-control-live-edit-label-wrapper .live-edit-label` so other label surfaces are unaffected.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Markers + token-fallback hygiene
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('task-2026-05-16-b0b48f', $this->quickAiEditJs);
        $this->assertStringContainsString('task-2026-05-16-b0b48f', $this->generalStyles);
    }

    #[Test]
    public function ai711_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('AI-711', $this->quickAiEditJs);
        $this->assertStringContainsString('AI-711', $this->generalStyles);
    }

    #[Test]
    public function legacy_tag_map_kept_as_back_compat_fallback(): void
    {
        // The pre-AI-711 `tagMap` constant MUST remain as the
        // back-compat fallback for callers that haven't migrated
        // to the per-section pattern. Pin its presence so a future
        // cleanup pass doesn't delete it without an explicit migration.
        $this->assertMatchesRegularExpression(
            '/const\s+tagMap\s*=\s*\{[\s\S]+?H1:\s*mw\.lang\("Heading"\)\s*\+\s*" 1"/',
            $this->quickAiEditJs,
            'Legacy `tagMap` constant must remain as back-compat fallback for `_text(obj)`.'
        );
    }
}
