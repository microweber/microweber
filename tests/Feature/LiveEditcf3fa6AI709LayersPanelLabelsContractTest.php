<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-cf3fa6 / AI-709 (P1, High) — Layers panel rows render
 * MDI glyphs only, no text labels.
 *
 * Designer-DOM-probed audit (Playwright, per SOUL #108 contract): every
 * Layers-tree row's text was geometric MDI glyphs alone — strings like
 * `󰚟 󰺰 󰠷 󰉫 󰉽` with NO element identity. The "Edit" button was the only
 * readable text per row. Cause: the row's primary label was rendered
 * via `getComponentLabel(item)` which for `.edit` elements returns the
 * literal string "Edit", and for the catch-all (other elements) returns
 * `getNodeLabel(node)` which combined `<span class="mdi mdi-format-
 * header-1 mdi-18px"></span> + " " + title`. In practice the
 * trailing title text was crowded out of the visible row by overflow
 * + ellipsis on the parent label span (`white-space: nowrap; text-
 * overflow: ellipsis; font-size: 14px; width: calc(100% - 20px)`).
 *
 * Fix (per designer spec):
 *
 *   1. Each row now renders THREE conceptual slots side by side:
 *        [icon] <tag>[.class][' preview'] ........... [Edit]
 *               └─── element identity ───┘            └ affordance
 *
 *   2. New `getRichElementLabel(node)` helper in `mw.DomTree`:
 *      - tag always (lowercased nodeName)
 *      - first non-system class as ".<class>" when present (skips
 *        edit, module, selected, active, mw-defaults, anything
 *        starting with "ui-" or "selectable-")
 *      - inner-text preview "'<first 24 chars>'" for elements that
 *        carry text (h1-h6, p, span, a, li, button, label)
 *
 *   3. `createItem` now inserts BOTH the existing
 *      .mw-domtree-item-label (the component affordance — Edit /
 *      module name) AND a new .mw-domtree-item-element-label (icon +
 *      rich label). Full plain-text label set as li.title for the
 *      truncation tooltip.
 *
 *   4. dom-tree.css gains a 3-column grid row layout, --ese-accent
 *      left-bar accent + --ese-surface-hover bg on .selected, indent
 *      via var(--space-sm) per nesting level, and overflow + text-
 *      ellipsis on the element-label so truncation kicks in at ~32
 *      chars + tooltip. ESE tokens resolve from :root via
 *      element-style-editor.css (defined in slice 1.1).
 *
 * Token-scoping note (per SOUL #108 designer's spec-doc-nit): the
 * dom-tree.css rules consume `var(--ese-accent)`, `var(--ese-surface-
 * hover)`, `var(--ese-text-muted)`, `var(--space-sm)`, `var(--font-
 * control)`, `var(--font-label)` — all :root-scoped tokens, so they
 * resolve correctly inside the Layers controlBox even though its DOM
 * lives OUTSIDE `.mw-live-edit-page`. Each `var()` carries a literal
 * fallback (e.g. `rgba(0, 0, 0, 0.04)`) for environments where the ESE
 * stylesheet hasn't loaded.
 */
class LiveEditcf3fa6AI709LayersPanelLabelsContractTest extends TestCase
{
    private string $domTreeJs;
    private string $domTreeCss;

    protected function setUp(): void
    {
        parent::setUp();
        $this->domTreeJs = (string) file_get_contents(base_path(
            'packages/frontend-assets-libs/resources/local-libs/api/domtree.js'
        ));
        $this->domTreeCss = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/dom-tree.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Rich label helper in domtree.js
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function rich_element_label_helper_declared(): void
    {
        $this->assertStringContainsString(
            'this.getRichElementLabel = function (node) {',
            $this->domTreeJs,
            'getRichElementLabel(node) helper must be declared on mw.DomTree.'
        );
    }

    #[Test]
    public function rich_label_uses_tag_class_and_preview_slots(): void
    {
        // Pin the three visible-label spans the helper emits — these
        // class names are the CSS hooks dom-tree.css styles.
        foreach (['mw-domtree-tag', 'mw-domtree-class', 'mw-domtree-preview'] as $cls) {
            $this->assertStringContainsString(
                'class="' . $cls . '"',
                $this->domTreeJs,
                "getRichElementLabel must emit a <span class=\"{$cls}\"> for that conceptual slot."
            );
        }
    }

    #[Test]
    public function preview_capped_at_24_chars_with_ellipsis(): void
    {
        // The designer spec: "first 24 chars" inner-text preview.
        $this->assertMatchesRegularExpression(
            '/rawText\.length\s*>\s*24\s*\?\s*rawText\.substr\(0,\s*24\)\s*\+\s*[\'"]\xe2\x80\xa6[\'"]/',
            $this->domTreeJs,
            'Preview text must cap at 24 chars + ellipsis character (designer spec).'
        );
    }

    #[Test]
    public function preview_only_for_text_carrying_tags(): void
    {
        // h1-h6, p, span, a, li, button, label — the tags allowed to
        // surface inner-text preview. Layout containers (div/section)
        // get NO preview to keep rows compact.
        $this->assertMatchesRegularExpression(
            "/previewTags\\s*=\\s*\\['h1',\\s*'h2',\\s*'h3',\\s*'h4',\\s*'h5',\\s*'h6',\\s*'p',\\s*'span',\\s*'a',\\s*'li',\\s*'button',\\s*'label'\\]/",
            $this->domTreeJs,
            'previewTags whitelist must list h1-h6, p, span, a, li, button, label — and NOT div/section/etc.'
        );
    }

    #[Test]
    public function system_classes_excluded_from_class_slot(): void
    {
        // .edit / .module / .selected / .active / .mw-defaults are
        // framework / system classes — they MUST NOT appear as the
        // ".class" identity slot. Otherwise every row reads ".edit"
        // and we're back to the bug.
        $this->assertMatchesRegularExpression(
            "/systemClasses\\s*=\\s*\\['edit',\\s*'module',\\s*'selected',\\s*'active',/",
            $this->domTreeJs,
            'systemClasses array must exclude edit/module/selected/active from the .class slot.'
        );
        // Also skip ui-* and selectable-* prefixes (jQuery UI / DomTree
        // chrome leftovers).
        $this->assertStringContainsString(
            "c.indexOf('ui-') === 0",
            $this->domTreeJs
        );
        $this->assertStringContainsString(
            "c.indexOf('selectable-') === 0",
            $this->domTreeJs
        );
    }

    #[Test]
    public function html_escaping_helper_present(): void
    {
        // Tag/class/preview content can come from user-authored HTML
        // (innerText of user content). Without escaping, a node with
        // an apostrophe or angle-bracket in its content would break
        // the row HTML or open an XSS surface inside the admin.
        $this->assertStringContainsString(
            'this._escapeHtml = function (s) {',
            $this->domTreeJs,
            '_escapeHtml helper must exist — user-derived strings go through it.'
        );
        foreach (['&amp;', '&lt;', '&gt;', '&quot;', '&#39;'] as $entity) {
            $this->assertStringContainsString($entity, $this->domTreeJs,
                "_escapeHtml must map to {$entity}.");
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — createItem wiring (element-label + affordance)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function create_item_inserts_element_label_span(): void
    {
        $this->assertStringContainsString(
            "dtElementLabel.className = 'mw-domtree-item-element-label'",
            $this->domTreeJs,
            'createItem must create a span with class "mw-domtree-item-element-label" alongside the existing label.'
        );
        // Both spans appended to the li.
        $this->assertStringContainsString(
            'li.appendChild(dtElementLabel)',
            $this->domTreeJs
        );
        $this->assertStringContainsString(
            'li.appendChild(dtLabel)',
            $this->domTreeJs,
            'The existing .mw-domtree-item-label (component affordance) must remain appended.'
        );
    }

    #[Test]
    public function create_item_consumes_rich_label_html_plus_icon(): void
    {
        // The rich label is composed as:
        //   <span class="mw-domtree-item-icon">[icon]</span> + rich.html
        $this->assertMatchesRegularExpression(
            "/<span class=\"mw-domtree-item-icon\">'\s*\+\s*iconHtml\s*\+\s*'<\/span>'\s*\+\s*rich\.html/",
            $this->domTreeJs,
            "element-label innerHTML must inject the icon span before the rich label HTML."
        );
    }

    #[Test]
    public function full_plain_label_set_as_li_title_tooltip(): void
    {
        // Designer spec — "Truncate at 32 chars + title= tooltip".
        $this->assertStringContainsString(
            'li.title = rich.text;',
            $this->domTreeJs,
            'createItem must set the full plain label as li.title (tooltip — the truncation reveal).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — CSS layout, accent, indent, tokens
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function css_uses_grid_row_layout(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-domtree li\s*\{[^}]*display:\s*grid[^}]*grid-template-columns:\s*auto\s+1fr\s+auto/s',
            $this->domTreeCss,
            'Row uses 3-col grid (auto 1fr auto) so the affordance can right-align cleanly.'
        );
    }

    #[Test]
    public function selected_row_uses_ese_accent_left_bar(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-domtree li\.selected\s*\{[^}]*border-inline-start-color:\s*var\(--ese-accent/s',
            $this->domTreeCss,
            'Selected row must carry the 2px --ese-accent left bar (designer spec).'
        );
        // The 2px bar comes from the slot reserved on every li.
        $this->assertMatchesRegularExpression(
            '/\.mw-domtree li\s*\{[^}]*border-inline-start:\s*2px\s+solid\s+transparent/s',
            $this->domTreeCss,
            'Every row reserves a 2px transparent border-inline-start so the selected accent does not shift layout.'
        );
    }

    #[Test]
    public function selected_row_uses_ese_surface_hover_bg(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-domtree li\.selected\s*\{[^}]*background-color:\s*var\(--ese-surface-hover/s',
            $this->domTreeCss
        );
    }

    #[Test]
    public function indent_uses_space_sm_token_per_nesting_level(): void
    {
        // Per-level indent uses var(--space-sm) with a literal fallback.
        // The fallback (8px) is necessary because the Layers controlBox
        // renders outside .mw-live-edit-page in some contexts.
        $this->assertMatchesRegularExpression(
            '/\.mw-domtree li > ul\s*\{[^}]*padding-inline-start:\s*var\(--space-sm,\s*8px\)/s',
            $this->domTreeCss,
            'Nested <ul> indent must use var(--space-sm, 8px) per spec.'
        );
    }

    #[Test]
    public function element_label_truncates_with_ellipsis(): void
    {
        // designer spec — 32-char-ish truncation. We pin the CSS that
        // produces it (overflow + text-overflow + nowrap).
        $this->assertMatchesRegularExpression(
            '/\.mw-domtree-item-element-label\s*\{[^}]*overflow:\s*hidden[^}]*text-overflow:\s*ellipsis[^}]*white-space:\s*nowrap/s',
            $this->domTreeCss,
            'Element-label must overflow:hidden + text-overflow:ellipsis + white-space:nowrap so it truncates and reveals via title tooltip.'
        );
    }

    #[Test]
    public function tag_class_preview_each_have_distinct_color(): void
    {
        // tag = --ese-text (default), class = --ese-accent, preview =
        // --ese-text-muted (italic). Pin each so the visual hierarchy
        // is regression-tested.
        $this->assertMatchesRegularExpression(
            '/\.mw-domtree-class\s*\{[^}]*color:\s*var\(--ese-accent/s',
            $this->domTreeCss
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-domtree-preview\s*\{[^}]*color:\s*var\(--ese-text-muted[^}]*font-style:\s*italic/s',
            $this->domTreeCss
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-domtree-item-icon\s*\{[^}]*color:\s*var\(--ese-text-muted/s',
            $this->domTreeCss,
            'Icon colour must use --ese-text-muted so the icon recedes vs the tag/class/preview content.'
        );
    }

    #[Test]
    public function component_affordance_right_aligns_after_element_label(): void
    {
        // The existing .mw-domtree-item-label is the right-side
        // affordance ("Edit" / module name). After AI-709 it sits in
        // the last grid column with width:auto + smaller font.
        $this->assertMatchesRegularExpression(
            '/\.mw-domtree-item-element-label \+ \.mw-domtree-item-label\s*\{[^}]*width:\s*auto[^}]*font-size:\s*var\(--font-label/s',
            $this->domTreeCss,
            'Component-affordance label must shrink (width: auto) + use --font-label so it reads as a secondary chip, not the primary row content.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Task-id markers + naming-hygiene
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('task-2026-05-16-cf3fa6', $this->domTreeJs);
        $this->assertStringContainsString('task-2026-05-16-cf3fa6', $this->domTreeCss);
    }

    #[Test]
    public function existing_label_helpers_preserved(): void
    {
        // Back-compat baseline: AI-709 must NOT remove the existing
        // getNodeIconAndTitle / getNodeLabel / getComponentLabel
        // helpers — other consumers may call them.
        $this->assertStringContainsString(
            'this.getNodeIconAndTitle = function (node) {',
            $this->domTreeJs,
            'getNodeIconAndTitle preserved for back-compat.'
        );
        $this->assertStringContainsString(
            'this.getNodeLabel = function (node) {',
            $this->domTreeJs,
            'getNodeLabel preserved.'
        );
        $this->assertStringContainsString(
            'this.getComponentLabel =  function (node) {',
            $this->domTreeJs,
            'getComponentLabel preserved.'
        );
    }
}
