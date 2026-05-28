<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-28-4a2e1b / AI-1151 — Tools dropdown ARIA menu semantics
 * + keyboard nav. Jira: https://microweber.atlassian.net/browse/AI-1151
 *
 * Pre-fix: the Live Edit toolbar 3-dots Tools dropdown rendered as a
 * plain UL of LI with no ARIA semantics. Screen reader users heard "list
 * item" / "button" rather than "menu" / "menuitem", and keyboard users
 * could not navigate the items with arrow keys — the only way to reach
 * an item was to Tab through every button on the toolbar.
 *
 * Fix: WAI-ARIA Authoring Practices Guide Menu pattern.
 *  - Trigger anchor carries id="mw-tools-dropdown-trigger" +
 *    aria-haspopup="menu" + Enter/Space/ArrowDown/ArrowUp openers.
 *  - Outer UL carries role="menu" + aria-labelledby="mw-tools-dropdown-trigger"
 *    + @keydown="handleMenuKeydown" for roving-tabindex nav.
 *  - Every interactive LI carries role="none"; the inner button/anchor
 *    carries role="menuitem" + tabindex="-1".
 *  - Separator LIs carry role="separator".
 *  - More-Settings submenu carries role="menu" + nested aria-labelledby
 *    chain; its parent button carries aria-haspopup="menu" +
 *    aria-controls + :aria-expanded.
 *  - Methods: handleMenuKeydown (ArrowDown/Up/Home/End/Escape/Tab),
 *    focusFirstMenuItem, focusLastMenuItem, openAndFocusFirstMenuItem,
 *    openAndFocusLastMenuItem, getMenuItems (offsetParent visibility
 *    filter so collapsed submenu items are skipped).
 *  - Focus return: Escape closes + focuses the trigger anchor per APG.
 *
 * Selector-self-match guard: setUp() pre-strips HTML+JS comments before
 * any absence assertion in case docblock prose mentions a literal
 * pre-state token (uniformity-rule from task-7aa48a).
 */
class LiveEdit4a2e1bAI1151ToolsDropdownAriaMenuContractTest extends TestCase
{
    private const TOOLS_VUE = 'packages/frontend-assets/resources/assets/ui/components/Toolbar/ToolbarToolsDropdown.vue';

    private string $source;

    private string $sourceStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->source = (string) file_get_contents(base_path(self::TOOLS_VUE));
        $this->sourceStripped = $this->stripComments($this->source);
    }

    private function stripComments(string $source): string
    {
        $stripped = (string) preg_replace('~<!--[\s\S]*?-->~', '', $source);
        $stripped = (string) preg_replace('~/\*[\s\S]*?\*/~', '', $stripped);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);
        return $stripped;
    }

    // ---------------------------------------------------------------------
    // Group A -- role="menu" + aria-labelledby chain on outer + nested UL
    // ---------------------------------------------------------------------

    #[Test]
    public function outer_dropdown_ul_carries_role_menu_and_aria_labelledby(): void
    {
        $this->assertMatchesRegularExpression(
            '/<ul[^>]*class="dropdown-content"[^>]*role="menu"[^>]*aria-labelledby="mw-tools-dropdown-trigger"/',
            $this->sourceStripped,
            'AI-1151: outer .dropdown-content UL MUST carry role="menu" + aria-labelledby="mw-tools-dropdown-trigger" per WAI-ARIA APG Menu pattern.'
        );
    }

    #[Test]
    public function nested_more_settings_submenu_ul_carries_role_menu(): void
    {
        $this->assertMatchesRegularExpression(
            '/<ul[^>]*class="submenu"[^>]*id="mw-tools-more-settings"[^>]*role="menu"[^>]*aria-labelledby="mw-tools-dropdown-trigger"/',
            $this->sourceStripped,
            'AI-1151: nested "More settings" UL MUST carry id="mw-tools-more-settings" + role="menu" + aria-labelledby pointing at the trigger anchor.'
        );
    }

    #[Test]
    public function trigger_anchor_carries_id_and_aria_haspopup_menu(): void
    {
        $this->assertMatchesRegularExpression(
            '/<a[^>]*id="mw-tools-dropdown-trigger"[^>]*ref="toolsDropdownTrigger"[^>]*aria-haspopup="menu"/s',
            $this->sourceStripped,
            'AI-1151: trigger anchor MUST carry id="mw-tools-dropdown-trigger" + ref="toolsDropdownTrigger" + aria-haspopup="menu" so the menu/trigger relationship resolves for AT.'
        );
    }

    // ---------------------------------------------------------------------
    // Group B -- role="menuitem" count + tabindex="-1" on every item
    // ---------------------------------------------------------------------

    #[Test]
    public function dropdown_content_contains_at_least_ten_menuitems(): void
    {
        preg_match_all('/role="menuitem"/', $this->sourceStripped, $matches);
        $count = count($matches[0]);

        $this->assertGreaterThanOrEqual(
            10,
            $count,
            sprintf(
                'AI-1151: expected ≥ 10 role="menuitem" tags (Module Settings + Templates + Design + Quick AI Edit + Layout Settings + Setup Wizard + More Settings parent + Dark mode + Layers + Code Editor + Reset Content + Clear Cache + user-menu items), got %d.',
                $count
            )
        );
    }

    #[Test]
    public function every_menuitem_carries_tabindex_minus_one(): void
    {
        // Roving-tabindex pattern: every menuitem in source carries
        // tabindex="-1". The focused item becomes tabbable at runtime
        // via element.focus() inside handleMenuKeydown. Count-comparison
        // shape (Layer-1 alternative from the selector-self-match guard
        // family): count menuitems vs menuitems-with-adjacent-tabindex
        // (within 400 chars window covers multi-line attribute tags
        // like the v-for user-menu anchor). Strip script blocks first
        // so JS selectors like '[role="menuitem"]' inside getMenuItems()
        // don't inflate the count.
        $markupOnly = (string) preg_replace('~<script\b[\s\S]*?</script>~i', '', $this->sourceStripped);
        preg_match_all('/role="menuitem"/', $markupOnly, $allMenuitems);
        preg_match_all('/role="menuitem"[\s\S]{0,400}?tabindex="-1"/', $markupOnly, $withTabindex);

        $this->assertSame(
            count($allMenuitems[0]),
            count($withTabindex[0]),
            sprintf(
                'AI-1151: every role="menuitem" MUST carry tabindex="-1" within 400 chars for roving-tabindex per WAI-ARIA APG Menu pattern. Got %d menuitems but only %d with tabindex.',
                count($allMenuitems[0]),
                count($withTabindex[0])
            )
        );
    }

    #[Test]
    public function interactive_li_elements_carry_role_none(): void
    {
        // Spot-check 3 known menu items have <li role="none"> wrappers.
        $this->assertStringContainsString('<li role="none">', $this->sourceStripped, 'AI-1151: interactive <li> wrappers MUST carry role="none" so AT treats the LI as presentational and the inner role="menuitem" as the keyboard target.');

        preg_match_all('/<li[^>]*role="none"/', $this->sourceStripped, $matches);
        $this->assertGreaterThanOrEqual(
            10,
            count($matches[0]),
            'AI-1151: expected ≥ 10 <li role="none"> wrappers; got ' . count($matches[0]) . '.'
        );
    }

    #[Test]
    public function separators_carry_role_separator(): void
    {
        $this->assertMatchesRegularExpression(
            '/<li[^>]*class="separator"[^>]*role="separator"/',
            $this->sourceStripped,
            'AI-1151: separator <li> elements MUST carry role="separator" per WAI-ARIA APG Menu pattern.'
        );
    }

    #[Test]
    public function more_settings_parent_carries_aria_haspopup_aria_controls_and_aria_expanded(): void
    {
        $this->assertMatchesRegularExpression(
            '/aria-haspopup="menu"[^>]*aria-controls="mw-tools-more-settings"[^>]*:aria-expanded="moreSettingsExpanded\.toString\(\)"/',
            $this->sourceStripped,
            'AI-1151: More-Settings parent button MUST carry aria-haspopup="menu" + aria-controls="mw-tools-more-settings" + :aria-expanded="moreSettingsExpanded.toString()" so AT can announce submenu state.'
        );
    }

    // ---------------------------------------------------------------------
    // Group C -- keydown handler wired on outer UL + trigger
    // ---------------------------------------------------------------------

    #[Test]
    public function outer_dropdown_ul_wires_handlemenukeydown(): void
    {
        $this->assertMatchesRegularExpression(
            '/<ul[^>]*class="dropdown-content"[^>]*@keydown="handleMenuKeydown"/',
            $this->sourceStripped,
            'AI-1151: outer .dropdown-content UL MUST wire @keydown="handleMenuKeydown" so arrow/home/end/escape/tab navigate the menu.'
        );
    }

    #[Test]
    public function trigger_anchor_wires_keyboard_openers(): void
    {
        // Enter/Space defer to toggleDropdown which $nextTick-focuses the
        // first menuitem on open. ArrowDown/ArrowUp open + focus first/last
        // directly per WAI-ARIA APG Menu pattern.
        $this->assertMatchesRegularExpression(
            '/@keydown\.enter\.prevent="(toggleDropdown|openAndFocusFirstMenuItem)"/',
            $this->sourceStripped,
            'AI-1151: trigger MUST wire @keydown.enter.prevent to either toggleDropdown (which focuses first on open) or openAndFocusFirstMenuItem.'
        );
        $this->assertMatchesRegularExpression(
            '/@keydown\.space\.prevent="(toggleDropdown|openAndFocusFirstMenuItem)"/',
            $this->sourceStripped,
            'AI-1151: trigger MUST wire @keydown.space.prevent to either toggleDropdown or openAndFocusFirstMenuItem.'
        );
        $this->assertMatchesRegularExpression(
            '/@keydown\.down\.prevent="openAndFocusFirstMenuItem"/',
            $this->sourceStripped,
            'AI-1151: trigger MUST wire @keydown.down.prevent="openAndFocusFirstMenuItem".'
        );
        $this->assertMatchesRegularExpression(
            '/@keydown\.up\.prevent="openAndFocusLastMenuItem"/',
            $this->sourceStripped,
            'AI-1151: trigger MUST wire @keydown.up.prevent="openAndFocusLastMenuItem".'
        );
    }

    #[Test]
    public function handle_menu_keydown_handles_all_six_keys(): void
    {
        foreach (['ArrowDown', 'ArrowUp', 'Home', 'End', 'Escape', 'Tab'] as $key) {
            $this->assertMatchesRegularExpression(
                '/case\s+\'' . $key . '\'\s*:/',
                $this->sourceStripped,
                'AI-1151: handleMenuKeydown MUST handle the ' . $key . ' key per WAI-ARIA APG Menu pattern.'
            );
        }
    }

    // ---------------------------------------------------------------------
    // Group D -- focus return on close + roving-tabindex helper methods
    // ---------------------------------------------------------------------

    #[Test]
    public function hidetoolsdropdown_accepts_focustrigger_param_and_focuses_trigger(): void
    {
        $this->assertMatchesRegularExpression(
            '/hideToolsDropdown\(focusTrigger\s*=\s*false\)\s*\{[\s\S]*?this\.\$refs\.toolsDropdownTrigger\.focus\(\)/',
            $this->sourceStripped,
            'AI-1151: hideToolsDropdown MUST accept a focusTrigger param and, when true, focus this.$refs.toolsDropdownTrigger (focus-return contract per WAI-ARIA APG Menu pattern Escape behaviour).'
        );
    }

    #[Test]
    public function hidetoolsdropdown_resets_more_settings_expanded(): void
    {
        $this->assertMatchesRegularExpression(
            '/hideToolsDropdown\([^)]*\)\s*\{[\s\S]*?this\.moreSettingsExpanded\s*=\s*false/',
            $this->sourceStripped,
            'AI-1151: hideToolsDropdown MUST reset this.moreSettingsExpanded = false so the next open starts with the submenu collapsed.'
        );
    }

    #[Test]
    public function toggle_dropdown_focuses_first_menu_item_on_open(): void
    {
        $this->assertMatchesRegularExpression(
            '/toggleDropdown\(\)\s*\{[\s\S]*?this\.\$nextTick\(\(\)\s*=>\s*this\.focusFirstMenuItem\(\)\)/',
            $this->sourceStripped,
            'AI-1151: toggleDropdown MUST $nextTick → focusFirstMenuItem on open so mouse-click users and keyboard users land on the first menuitem.'
        );
    }

    #[Test]
    public function getmenuitems_filters_invisible_items(): void
    {
        // Hidden submenu items (More Settings collapsed) must be filtered
        // out so ArrowDown does not skip into the invisible submenu.
        $this->assertMatchesRegularExpression(
            '/getMenuItems\(\)\s*\{[\s\S]*?querySelectorAll\(\'\[role="menuitem"\]\'\)[\s\S]*?\.filter\([\s\S]*?offsetParent\s*!==\s*null/',
            $this->sourceStripped,
            'AI-1151: getMenuItems MUST querySelectorAll(\'[role="menuitem"]\') AND filter by el.offsetParent !== null so hidden submenu items are skipped.'
        );
    }

    #[Test]
    public function helper_methods_present(): void
    {
        foreach ([
            'openAndFocusFirstMenuItem',
            'openAndFocusLastMenuItem',
            'getMenuItems',
            'focusFirstMenuItem',
            'focusLastMenuItem',
            'handleMenuKeydown',
        ] as $method) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($method, '/') . '\s*\(/',
                $this->sourceStripped,
                'AI-1151: ' . $method . '() helper method MUST be defined on the component.'
            );
        }
    }

    // ---------------------------------------------------------------------
    // Group E -- task-id + AI-1151 markers (audit grep contract)
    // ---------------------------------------------------------------------

    #[Test]
    public function task_id_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-4a2e1b',
            $this->source,
            'AI-1151: ToolbarToolsDropdown.vue MUST carry the AI-1151 task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-1151',
            $this->source,
            'AI-1151: ToolbarToolsDropdown.vue MUST cite the AI-1151 ticket ID.'
        );
    }

    // ---------------------------------------------------------------------
    // Group F -- WAI-ARIA APG citation (justification + audit trail)
    // ---------------------------------------------------------------------

    #[Test]
    public function wai_aria_apg_citation_documented(): void
    {
        $this->assertMatchesRegularExpression(
            '/(WAI-ARIA|APG|Authoring Practices)/i',
            $this->source,
            'AI-1151: comment block MUST cite the WAI-ARIA Authoring Practices Guide (APG) Menu pattern as the justification.'
        );
    }
}
