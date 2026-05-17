<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-439f34 / AI-811 — ResolutionSwitch.vue semantic
 * upgrade: <span role="button" tabindex="0"> → <button type="button"
 * role="radio">. WCAG 2.1.1 Level A keyboard fail closed.
 *
 * Jira: https://microweber.atlassian.net/browse/AI-811
 * Lineage: AI-687 (MwSegmented refactor — same component)
 *          AI-810 (clip fix shipped to same #preview-nav surface)
 *
 * Pre-fix:
 *   <nav id="preview-nav" aria-label="Device preview">
 *     <span class="..." role="button" tabindex="0"
 *           aria-label="Desktop view" :aria-pressed="..."
 *           v-on:click v-on:keydown.enter.prevent v-on:keydown.space.prevent>
 *       <svg/>
 *     </span>
 *     ... (tablet + phone same shape)
 *   </nav>
 *
 * Three a11y defects from one element choice (designer email
 * 2026-05-17T10:21:03Z):
 *   1. <span tabindex="0"> requires manual keyboard activation
 *      handlers (Enter + Space keydown.prevent) — fragile vs
 *      native <button>'s built-in keyboard handling.
 *   2. role="button" announces as generic button; role="radio"
 *      inside role="radiogroup" parent communicates the
 *      mutually-exclusive choice semantic to AT.
 *   3. Browsers paint default focus rings on <button> but may
 *      suppress them on <span tabindex="0"> in some UA defaults.
 *
 * Post-fix:
 *   <nav id="preview-nav" role="radiogroup" aria-label="Device preview">
 *     <button type="button" class="..." role="radio"
 *             aria-label="Desktop view" :aria-checked="..."
 *             v-on:click>
 *       <svg/>
 *     </button>
 *     ... (tablet + phone same shape)
 *   </nav>
 *
 * Native <button> brings Tab-focusable by default + native
 * Enter/Space activation. type="button" is defence-in-depth
 * against accidental form-submit if the buttons end up inside
 * a <form> ancestor.
 *
 * CSS paired update (element-style-editor.css):
 *   1. .mw-segmented__cell base rule gains `font-family: inherit`
 *      so <button>'s UA-default font (Arial/Helvetica) doesn't
 *      fight the live-edit page font.
 *   2. Active-state selector extends from
 *      `.mw-segmented__cell.is-active, [aria-pressed="true"]`
 *      to ALSO match `[aria-checked="true"]` (the new ARIA
 *      shape).
 *
 * Designer's tier-3 probe:
 *   const items = document.querySelectorAll(
 *     '#preview-nav [aria-label*="view"]'
 *   );
 *   items.forEach(item => {
 *     expect(['BUTTON', 'INPUT', 'A']).toContain(item.tagName);
 *     expect(item.tabIndex).not.toBe(-1);
 *   });
 */
class LiveEdit439f34AI811ResolutionSwitchSemanticsContractTest extends TestCase
{
    private string $vue;
    private string $vueExecutable;
    private string $css;
    private string $cssExecutable;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vue = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/ResolutionSwitch.vue'
        ));
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css'
        ));

        // Selector-self-match guard family (17+ session-recurrences):
        // strip HTML comments + Blade-comment fences + CSS comments
        // before absence assertions. The Vue template's <!-- ... -->
        // docblock legitimately mentions the legacy shape strings
        // (role="button", tabindex="0", aria-pressed) as the
        // pre-fix reference; without stripping, the absence asserts
        // false-fail on the docblock prose.
        $this->vueExecutable = preg_replace('~<!--[\s\S]*?-->~', '', $this->vue);
        $this->vueExecutable = preg_replace('~/\*[\s\S]*?\*/~', '', $this->vueExecutable);
        $this->cssExecutable = preg_replace('~/\*[\s\S]*?\*/~', '', $this->css);
    }

    public static function modeLabelProvider(): array
    {
        return [
            'desktop' => ['desktop', 'Desktop view'],
            'tablet'  => ['tablet',  'Tablet view'],
            'phone'   => ['phone',   'Mobile view'],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  <nav> parent is a radiogroup
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function nav_parent_carries_radiogroup_role(): void
    {
        $this->assertMatchesRegularExpression(
            '/<nav\s+id="preview-nav"[^>]*\brole="radiogroup"[^>]*\baria-label="Device preview"/',
            $this->vueExecutable,
            'AI-811: <nav id="preview-nav"> MUST carry role="radiogroup" + aria-label="Device preview" per designer spec.'
        );
    }

    #[Test]
    public function nav_keeps_back_compat_classes(): void
    {
        // Preserve legacy CSS hooks per task-5fe1f9 / AI-698b lineage.
        foreach (['toolbar-nav', 'mw-live-edit-resolutions-wrapper', 'toolbar-nav-hover', 'mw-segmented'] as $cls) {
            $this->assertMatchesRegularExpression(
                "/<nav\\s+id=\"preview-nav\"[^>]*class=\"[^\"]*\\b{$cls}\\b/",
                $this->vueExecutable,
                "AI-811: <nav> MUST preserve legacy class `{$cls}` (back-compat with external CSS/JS targeting these hooks)."
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  each cell is a native <button type="button"> with role="radio"
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('modeLabelProvider')]
    public function each_cell_uses_native_button_element(string $mode, string $label): void
    {
        // Pin one <button type="button"> per mode with the canonical
        // attribute set. Whitespace-tolerant regex.
        $pattern = sprintf(
            '/<button\s+type="button"\s+class="[^"]*\bmw-segmented__cell\b[^"]*"\s+role="radio"\s+aria-label="%s"/',
            preg_quote($label, '/')
        );
        $this->assertMatchesRegularExpression(
            $pattern,
            $this->vueExecutable,
            "AI-811: cell for `{$mode}` MUST be a native <button type=\"button\" role=\"radio\" aria-label=\"{$label}\">."
        );
    }

    #[Test]
    #[DataProvider('modeLabelProvider')]
    public function each_cell_uses_aria_checked_not_aria_pressed(string $mode, string $label): void
    {
        // Positive: cell has :aria-checked binding driven by previewMode comparison.
        $this->assertMatchesRegularExpression(
            "/:aria-checked=\"previewMode=='{$mode}'\\s*\\?\\s*'true'\\s*:\\s*'false'\"/",
            $this->vueExecutable,
            "AI-811: cell for `{$mode}` MUST carry :aria-checked binding (radio APG semantics), NOT :aria-pressed (toggle-button semantics)."
        );
    }

    #[Test]
    public function no_cell_carries_legacy_aria_pressed_binding(): void
    {
        // After comment-strip, NO :aria-pressed binding may appear
        // on any cell. The active-state CSS rule keeps
        // [aria-pressed="true"] as legacy alias for OTHER consumers
        // — but ResolutionSwitch cells specifically must use
        // aria-checked.
        $this->assertDoesNotMatchRegularExpression(
            '/:aria-pressed=/',
            $this->vueExecutable,
            'AI-811: NO cell in ResolutionSwitch.vue may carry :aria-pressed (post-comment-strip) — radio APG uses aria-checked.'
        );
    }

    #[Test]
    public function no_cell_carries_legacy_tabindex_attribute(): void
    {
        // Native <button> is naturally focusable; explicit
        // tabindex="0" is redundant and was a fragile shim for
        // the legacy <span> shape.
        $this->assertDoesNotMatchRegularExpression(
            '/tabindex="0"/',
            $this->vueExecutable,
            'AI-811: NO cell in ResolutionSwitch.vue may carry tabindex="0" — native <button> is naturally focusable (drop the legacy shim).'
        );
    }

    #[Test]
    public function no_cell_carries_role_button_attribute(): void
    {
        // role="button" was the legacy <span> shim. Native
        // <button> has implicit button role; cells now use
        // role="radio" for the radio APG.
        $this->assertDoesNotMatchRegularExpression(
            '/\brole="button"/',
            $this->vueExecutable,
            'AI-811: NO cell in ResolutionSwitch.vue may carry role="button" — radio APG uses role="radio" inside role="radiogroup" parent.'
        );
    }

    #[Test]
    public function no_cell_carries_manual_keydown_handlers(): void
    {
        // Native <button> handles Enter + Space activation; the
        // legacy v-on:keydown.enter.prevent + v-on:keydown.space.
        // prevent handlers were shims for the <span> shape. Drop
        // them to reduce surface area.
        $this->assertDoesNotMatchRegularExpression(
            '/v-on:keydown\.(enter|space)\.prevent/',
            $this->vueExecutable,
            'AI-811: NO cell in ResolutionSwitch.vue may carry v-on:keydown.enter.prevent or v-on:keydown.space.prevent — native <button> handles both natively.'
        );
    }

    #[Test]
    public function no_cell_uses_legacy_span_element(): void
    {
        // Pin via Vue template scan: no <span> element MAY exist
        // inside ResolutionSwitch.vue's <template> block (cells
        // were the only spans in the template). Slice to template
        // body only to avoid the docblock's prose mentioning the
        // legacy <span> shape.
        if (! preg_match('/<template>([\s\S]+)<\/template>/', $this->vueExecutable, $m)) {
            $this->fail('AI-811: could not slice <template> body for span-absence guard.');
        }
        $templateBody = $m[1];
        $this->assertDoesNotMatchRegularExpression(
            '/<span\b/',
            $templateBody,
            'AI-811: NO <span> element may appear inside ResolutionSwitch.vue <template> (cells are now native <button>).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  click handler + back-compat classes preserved
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('modeLabelProvider')]
    public function each_cell_preserves_click_handler(string $mode, string $label): void
    {
        // The click handler is the ONLY remaining activation surface.
        $this->assertMatchesRegularExpression(
            "/v-on:click=\"setPreviewMode\\('{$mode}'\\)\"/",
            $this->vueExecutable,
            "AI-811: cell for `{$mode}` MUST keep v-on:click=\"setPreviewMode('{$mode}')\" (only activation path after dropping keydown handlers)."
        );
    }

    #[Test]
    #[DataProvider('modeLabelProvider')]
    public function each_cell_preserves_back_compat_active_classes(string $mode, string $label): void
    {
        // task-5fe1f9 / AI-698b lineage: legacy
        // .live-edit-resolution-active AND .is-active classes
        // remain in the :class binding for the active cell.
        $this->assertMatchesRegularExpression(
            "/:class=\"\\[previewMode=='{$mode}'\\s*\\?\\s*'live-edit-resolution-active\\s+is-active'\\s*:\\s*''\\]\"/",
            $this->vueExecutable,
            "AI-811: cell for `{$mode}` MUST preserve :class binding with `live-edit-resolution-active is-active` (back-compat hooks)."
        );
    }

    #[Test]
    #[DataProvider('modeLabelProvider')]
    public function each_cell_preserves_data_preview_attribute(string $mode, string $label): void
    {
        // data-preview="<mode>" is an external selector hook
        // (analytics / external CSS may target it).
        $this->assertMatchesRegularExpression(
            "/data-preview=\"{$mode}\"/",
            $this->vueExecutable,
            "AI-811: cell for `{$mode}` MUST preserve data-preview=\"{$mode}\" attribute."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  CSS paired updates
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function mw_segmented_cell_base_rule_has_font_family_inherit(): void
    {
        // Load-bearing add when cells switch to native <button>.
        // Slice the .mw-segmented__cell rule body (specifically
        // the .mw-live-edit-page-scoped variant, not the active
        // state).
        if (! preg_match('/\.mw-live-edit-page \.mw-segmented__cell\s*\{([^}]+)\}/', $this->cssExecutable, $m)) {
            $this->fail('AI-811: could not slice .mw-live-edit-page .mw-segmented__cell base rule body.');
        }
        $body = $m[1];
        $this->assertMatchesRegularExpression(
            '/font-family:\s*inherit/',
            $body,
            'AI-811: .mw-segmented__cell base rule MUST declare `font-family: inherit` so native <button> UA-default font (Arial/Helvetica) does not fight the live-edit page font.'
        );
    }

    #[Test]
    public function active_state_selector_includes_aria_checked(): void
    {
        // The active-state rule MUST match [aria-checked="true"]
        // (new ARIA shape from AI-811). Legacy [aria-pressed="true"]
        // stays as alias for other MwSegmented consumers — verify
        // both are in the same selector list.
        $this->assertMatchesRegularExpression(
            '/\.mw-segmented__cell[^,]*\[aria-checked="true"\]\s*\{/',
            $this->cssExecutable,
            'AI-811: active-state rule MUST include `.mw-segmented__cell[aria-checked="true"]` selector.'
        );

        // Belt-and-braces: .is-active class binding stays.
        $this->assertMatchesRegularExpression(
            '/\.mw-segmented__cell\.is-active/',
            $this->cssExecutable,
            'AI-811: active-state rule MUST keep `.mw-segmented__cell.is-active` selector (Vue class binding drives this).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E  task-id markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai811_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-439f34', $this->vue);
        $this->assertStringContainsString('AI-811', $this->vue);
        $this->assertStringContainsString('task-2026-05-17-439f34', $this->css);
        $this->assertStringContainsString('AI-811', $this->css);
    }
}
