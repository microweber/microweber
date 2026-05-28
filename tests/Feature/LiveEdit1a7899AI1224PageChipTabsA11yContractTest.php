<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1224 / task-2026-05-28-1a7899.
 *
 * Scope (verbatim from agent-pm dispatch):
 *   "PageChip popover ARIA tabs + touch targets (P2, queued after Batch 2)"
 *
 * Acceptance criteria (verbatim):
 *   - aria-label="Page picker" on tablist
 *   - aria-controls + role="tabpanel" + aria-labelledby pairs for each tab
 *   - Roving tabindex + LeftRight keyboard handler on tablist
 *   - Replace empty-href anchor with button aria-current="page"
 *   - min-height: 44px on tabs, rows, and CTA
 *
 * Pinned files:
 *   - packages/frontend-assets/resources/assets/ui/components/Toolbar/
 *     PageChip.vue (tablist/tabpanel template + onTabKeydown method)
 *   - packages/microweber-filament-theme/resources/assets/css/microweber/
 *     general-styles.css (min-height: 44px on tab, link, and new-page CTA;
 *     new --current button variant)
 *
 * Selector-self-match guard: setUp() pre-strips comments per the uniformity
 * rule (task-7aa48a) so absence assertions don't false-match against prose
 * inside docblocks / line comments / CSS comments.
 */
class LiveEdit1a7899AI1224PageChipTabsA11yContractTest extends TestCase
{
    private string $pageChipVue;

    private string $pageChipVueStripped;

    private string $generalStylesCss;

    private string $generalStylesCssStripped;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->pageChipVue = (string) file_get_contents(
            self::projectRoot()
            . '/packages/frontend-assets/resources/assets/ui/components/Toolbar/PageChip.vue'
        );
        $this->pageChipVueStripped = $this->stripVueComments($this->pageChipVue);

        $this->generalStylesCss = (string) file_get_contents(
            self::projectRoot()
            . '/packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        );
        $this->generalStylesCssStripped = $this->stripCssComments($this->generalStylesCss);
    }

    private function stripVueComments(string $source): string
    {
        // HTML comments (Vue template); use ~ delimiter so the < of <!-- does
        // not collide with PCRE modifier flags.
        $stripped = (string) preg_replace('~<!--[\s\S]*?-->~', '', $source);
        // JS block comments in <script>
        $stripped = (string) preg_replace('~/\*[\s\S]*?\*/~', '', $stripped);
        // JS line comments
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);
        return $stripped;
    }

    private function stripCssComments(string $source): string
    {
        return (string) preg_replace('~/\*[\s\S]*?\*/~', '', $source);
    }

    public function test_task_id_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-1a7899 / AI-1224',
            $this->pageChipVue,
            'AI-1224: task-id marker must be present in PageChip.vue'
        );
        $this->assertStringContainsString(
            'task-2026-05-28-1a7899 / AI-1224',
            $this->generalStylesCss,
            'AI-1224: task-id marker must be present in general-styles.css'
        );
    }

    public function test_tablist_declares_aria_label_page_picker(): void
    {
        $this->assertMatchesRegularExpression(
            '/<div\s+class="mw-page-chip-popover__tabs"\s+role="tablist"\s+aria-label="Page picker"/',
            $this->pageChipVueStripped,
            'AI-1224: tablist must declare aria-label="Page picker"'
        );
    }

    public function test_each_tab_carries_id_and_aria_controls(): void
    {
        $this->assertMatchesRegularExpression(
            "/:id=\"'mw-page-chip-tab-'\s*\+\s*tab\.key\"/",
            $this->pageChipVueStripped,
            'AI-1224: tab button must bind id="mw-page-chip-tab-<key>"'
        );
        $this->assertMatchesRegularExpression(
            "/:aria-controls=\"'mw-page-chip-tabpanel-'\s*\+\s*tab\.key\"/",
            $this->pageChipVueStripped,
            'AI-1224: tab button must bind aria-controls="mw-page-chip-tabpanel-<key>"'
        );
    }

    public function test_tab_carries_roving_tabindex(): void
    {
        $this->assertMatchesRegularExpression(
            '/:tabindex="activeTab === tab\.key \? 0 : -1"/',
            $this->pageChipVueStripped,
            'AI-1224: tab button must use roving tabindex (0 on active, -1 on others)'
        );
    }

    public function test_tab_has_ref_for_focus_management(): void
    {
        $this->assertMatchesRegularExpression(
            '/ref="tabRefs"/',
            $this->pageChipVueStripped,
            'AI-1224: tab button must carry ref="tabRefs" so onTabKeydown can focus the next tab'
        );
    }

    public function test_tab_wires_keydown_handler(): void
    {
        $this->assertMatchesRegularExpression(
            '/@keydown="onTabKeydown\(\$event,\s*idx\)"/',
            $this->pageChipVueStripped,
            'AI-1224: tab button must wire @keydown to onTabKeydown($event, idx)'
        );
    }

    public function test_tabpanel_wrapper_present_with_aria_labelledby(): void
    {
        $this->assertMatchesRegularExpression(
            '/role="tabpanel"/',
            $this->pageChipVueStripped,
            'AI-1224: search + results + footer block must be a role="tabpanel"'
        );
        $this->assertMatchesRegularExpression(
            "/:id=\"'mw-page-chip-tabpanel-'\s*\+\s*activeTab\"/",
            $this->pageChipVueStripped,
            'AI-1224: tabpanel id must bind "mw-page-chip-tabpanel-<activeTab>"'
        );
        $this->assertMatchesRegularExpression(
            "/:aria-labelledby=\"'mw-page-chip-tab-'\s*\+\s*activeTab\"/",
            $this->pageChipVueStripped,
            'AI-1224: tabpanel must be labelled by the active tab via aria-labelledby'
        );
    }

    public function test_onTabKeydown_method_handles_all_required_keys(): void
    {
        $this->assertStringContainsString(
            'onTabKeydown(event, idx)',
            $this->pageChipVueStripped,
            'AI-1224: onTabKeydown(event, idx) method must be defined'
        );
        foreach (['ArrowLeft', 'ArrowRight', 'Home', 'End'] as $key) {
            $this->assertStringContainsString(
                "key === '" . $key . "'",
                $this->pageChipVueStripped,
                'AI-1224: onTabKeydown must branch on ' . $key
            );
        }
    }

    public function test_onTabKeydown_calls_preventDefault(): void
    {
        $this->assertMatchesRegularExpression(
            '/onTabKeydown\(event,\s*idx\)\s*\{[\s\S]*?event\.preventDefault\(\)/',
            $this->pageChipVueStripped,
            'AI-1224: onTabKeydown must call preventDefault so arrow keys do not scroll the popover'
        );
    }

    public function test_onTabKeydown_wraps_on_arrow_left_and_right(): void
    {
        $this->assertMatchesRegularExpression(
            "/key === 'ArrowLeft'[\\s\\S]*?idx === 0 \\? last : idx - 1/",
            $this->pageChipVueStripped,
            'AI-1224: ArrowLeft must wrap from index 0 to the last tab'
        );
        $this->assertMatchesRegularExpression(
            "/key === 'ArrowRight'[\\s\\S]*?idx === last \\? 0 : idx \\+ 1/",
            $this->pageChipVueStripped,
            'AI-1224: ArrowRight must wrap from the last tab to index 0'
        );
    }

    public function test_current_page_row_renders_as_button_with_aria_current(): void
    {
        $this->assertMatchesRegularExpression(
            '/<button\s+v-if="!item\.edit_link"[\s\S]*?aria-current="page"/',
            $this->pageChipVueStripped,
            'AI-1224: rows with empty edit_link must render as <button aria-current="page">'
        );
        $this->assertMatchesRegularExpression(
            '/mw-page-chip-popover__link--current/',
            $this->pageChipVueStripped,
            'AI-1224: current-page button must carry .mw-page-chip-popover__link--current modifier class'
        );
    }

    public function test_non_current_row_still_renders_as_anchor(): void
    {
        $this->assertMatchesRegularExpression(
            '/<a\s+v-else\s+:href="item\.edit_link"\s+class="mw-page-chip-popover__link"/',
            $this->pageChipVueStripped,
            'AI-1224: rows with edit_link must still render as <a :href=...> via v-else'
        );
    }

    public function test_css_tab_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-page-chip-popover__tab\s*\{[^}]*min-height:\s*44px/',
            $this->generalStylesCssStripped,
            'AI-1224: .mw-page-chip-popover__tab must declare min-height: 44px'
        );
    }

    public function test_css_link_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-page-chip-popover__link\s*\{[^}]*min-height:\s*44px/',
            $this->generalStylesCssStripped,
            'AI-1224: .mw-page-chip-popover__link must declare min-height: 44px'
        );
    }

    public function test_css_new_page_cta_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-page-chip-popover__new-page\s*\{[^}]*min-height:\s*44px/',
            $this->generalStylesCssStripped,
            'AI-1224: .mw-page-chip-popover__new-page must declare min-height: 44px'
        );
    }

    public function test_css_current_link_variant_styled(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-page-chip-popover__link--current\s*\{[^}]*color:\s*var\(--ese-accent/',
            $this->generalStylesCssStripped,
            'AI-1224: .mw-page-chip-popover__link--current must consume var(--ese-accent) for the active-row colour'
        );
    }

    public function test_css_tab_focus_visible_ring(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-page-chip-popover__tab:focus-visible\s*\{[^}]*outline:\s*2px solid/',
            $this->generalStylesCssStripped,
            'AI-1224: .mw-page-chip-popover__tab:focus-visible must render a focus ring'
        );
    }

    public function test_legacy_empty_href_anchor_pattern_absent(): void
    {
        // The pre-fix shape had ONE <a :href="item.edit_link"> with NO v-if /
        // v-else split — the empty-href footgun. Post-fix MUST split: a
        // button v-if branch + an anchor v-else branch. Pin the dead-pattern
        // signature: an anchor that is NOT preceded by v-else.
        $this->assertDoesNotMatchRegularExpression(
            '/<a\s+:href="item\.edit_link"\s+class="mw-page-chip-popover__link"\s+@click="close\(\)">/',
            $this->pageChipVueStripped,
            'AI-1224: legacy unconditional <a :href="item.edit_link"> without v-else must be gone — the new shape always pairs the anchor with a v-if button branch'
        );
    }
}
