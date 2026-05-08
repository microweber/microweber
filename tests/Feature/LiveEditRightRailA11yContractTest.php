<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-78 / AI-87 / TICKET-M — live-edit right-rail + ESE 4-icon
 * strip a11y regression coverage.
 *
 * Pins:
 *   - All 7 right-rail tool buttons in `ToolsButtons.vue` are real
 *     <button type="button"> elements (not <a> with click handlers
 *     and no href) carrying BOTH aria-label AND title attributes
 *     so screen readers AND sighted users hovering with keyboard
 *     focus get the same label.
 *   - Every <li> wrapping a menuitem button uses role="none" so the
 *     parent <ul role="menu"> structure is preserved correctly
 *     (without role=none, the implicit list semantics nest under
 *     the menu role and confuse SR navigation).
 *   - Every SVG inside a tool button carries aria-hidden="true"
 *     (decorative — the button's aria-label is the accessible name).
 *   - All 15 panel-toggle wrappers in ElementStyleEditorApp.vue
 *     gain aria-label + title alongside the cycle-76 role="button"
 *     + tabindex + keyboard handlers + aria-expanded — completes
 *     the accessible-button pattern.
 *
 * Style after the cycle-52..77 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class LiveEditRightRailA11yContractTest extends TestCase
{
    private string $toolsSrc;
    private string $eseAppSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->toolsSrc = file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/RightSidebar/ToolsButtons.vue'
        ));
        $this->eseAppSrc = file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorApp.vue'
        ));
    }

    #[Test]
    public function right_rail_uses_real_buttons_not_anchors_for_seven_tools(): void
    {
        // Every menuitem MUST be a real <button> — keyboard activates
        // it natively, screen readers announce "button" instead of
        // "link with no href", and form-submit semantics behave
        // correctly via type="button".
        $required = [
            'Setup wizard',
            'Code editor',
            'Reset content',
            'Layers',
            'Layout settings',
            'More settings',
            'Clear cache',
        ];
        foreach ($required as $label) {
            // Each must appear as the aria-label on a <button>.
            $this->assertMatchesRegularExpression(
                '/<button\\b[^>]*aria-label="' . preg_quote($label, '/') . '"/s',
                $this->toolsSrc,
                "ToolsButtons.vue: '{$label}' tool must be a real <button> with matching aria-label"
            );

            // AND must carry title="..." so sighted users hovering
            // with keyboard focus see the same label before the
            // v-tooltip activator catches up.
            $this->assertMatchesRegularExpression(
                '/<button\\b[^>]*title="' . preg_quote($label, '/') . '"/s',
                $this->toolsSrc,
                "ToolsButtons.vue: '{$label}' tool must carry title=\"{$label}\""
            );
        }

        // type="button" on every <button> — without it the default is
        // "submit" which would submit any wrapping form on click.
        // Strip HTML comments first so the AI-87 doc-comment block at
        // the top of the file (which mentions <button type="button">
        // in prose) doesn't inflate the count.
        $strippedTools = preg_replace('/<!--[\\s\\S]*?-->/', '', $this->toolsSrc);
        $this->assertSame(
            7,
            substr_count($strippedTools, 'type="button"'),
            'ToolsButtons.vue: must have exactly 7 type="button" attributes (one per tool)'
        );

        // Negative: no anchors with click handlers + no href remain.
        $this->assertDoesNotMatchRegularExpression(
            '/<a\\s+class="mw-live-edit-advanced-settings-popup"[^>]*v-on:click=/s',
            $this->toolsSrc,
            'ToolsButtons.vue: <a> + v-on:click pattern must NOT remain (was keyboard-inaccessible without href)'
        );
    }

    #[Test]
    public function right_rail_lis_use_role_none_to_preserve_menu_semantics(): void
    {
        // <ul role="menu"> + <li role="none"> + <button role="menuitem">
        // is the canonical ARIA Menu pattern — without role="none" on
        // the <li>, screen readers would announce "list item, button"
        // which breaks the menu navigation model (arrow keys etc).
        $this->assertSame(
            7,
            substr_count($this->toolsSrc, 'role="none"'),
            'ToolsButtons.vue: must have exactly 7 <li role="none"> wrappers (one per menuitem)'
        );
        $this->assertSame(
            7,
            substr_count($this->toolsSrc, 'role="menuitem"'),
            'ToolsButtons.vue: must have exactly 7 role="menuitem" buttons'
        );
    }

    #[Test]
    public function right_rail_decorative_svgs_are_aria_hidden(): void
    {
        // Each tool button has an SVG icon — must carry aria-hidden="true"
        // so the SR doesn't double-announce ("Setup wizard, settings icon").
        $totalSvgs = preg_match_all('/<svg\\b/', $this->toolsSrc);
        $hiddenSvgs = preg_match_all('/<svg[^>]*\\baria-hidden="true"/', $this->toolsSrc);
        $this->assertSame(
            $totalSvgs,
            $hiddenSvgs,
            "ToolsButtons.vue: every <svg> must carry aria-hidden=\"true\" — got {$hiddenSvgs} of {$totalSvgs}"
        );
    }

    #[Test]
    public function ese_panel_toggle_wrappers_all_carry_aria_label(): void
    {
        // Cycle-76 added role="button" + tabindex="0" + keyboard
        // handlers + aria-expanded but did NOT add aria-label/title.
        // Cycle-78 closes the gap: every wrapper must carry both.
        $wrapperCount = preg_match_all(
            '/element-style-editor-toggle-wrapper[^>]*role="button"/',
            $this->eseAppSrc
        );
        $labelledCount = preg_match_all(
            '/element-style-editor-toggle-wrapper[^>]*aria-label="[^"]+"/',
            $this->eseAppSrc
        );
        $titledCount = preg_match_all(
            '/element-style-editor-toggle-wrapper[^>]*title="[^"]+"/',
            $this->eseAppSrc
        );

        $this->assertGreaterThanOrEqual(
            14,
            $wrapperCount,
            "ESE app: must have at least 14 panel-toggle wrappers — got {$wrapperCount}"
        );
        $this->assertSame(
            $wrapperCount,
            $labelledCount,
            "ESE app: every toggle wrapper must carry aria-label — {$labelledCount} of {$wrapperCount}"
        );
        $this->assertSame(
            $wrapperCount,
            $titledCount,
            "ESE app: every toggle wrapper must carry title — {$titledCount} of {$wrapperCount}"
        );

        // Spot-check specific labels — the migration uses the same
        // label string for both attributes.
        $expected = [
            'Typography',
            'Background',
            'Spacing',
            'Container',
            'Grid',
            'Border',
            'Rounded corners',
            'Animations',
            'Shadow',
            'CSS class applier',
            'List style editor',
            'Layout settings',
            'Predefined styles',
        ];
        foreach ($expected as $label) {
            $this->assertMatchesRegularExpression(
                '/element-style-editor-toggle-wrapper[^>]*aria-label="' . preg_quote($label, '/') . '"/',
                $this->eseAppSrc,
                "ESE app: panel-toggle wrapper for '{$label}' must carry the matching aria-label"
            );
        }
    }
}
