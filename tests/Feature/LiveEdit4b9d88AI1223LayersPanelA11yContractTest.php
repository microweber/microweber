<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1223 / task-2026-05-28-4b9d88.
 *
 * Scope (verbatim from agent-pm dispatch):
 *   "Layers panel keyboard a11y + ARIA tree + touch targets (P2)"
 *
 * Acceptance criteria (verbatim):
 *   - ARIA Tree pattern: role='tree', role='treeitem'
 *   - Arrow-key navigation (Up/Down/Left/Right)
 *   - aria-expanded mirror on collapsible nodes
 *   - 44px min-height on rows
 *   - aria-label on tree-opener icons or aria-hidden='true'
 *   - aria-labelledby on <ul> pointing to <h3>Layers</h3>
 *
 * Pinned files:
 *   - packages/frontend-assets-libs/resources/local-libs/api/domtree.js
 *     (core DomTree class — applies ARIA + roving tabindex + keydown nav)
 *   - packages/frontend-assets/resources/assets/api-core/services/components/
 *     live-edit/live-edit-dom-tree.js
 *     (wires the controlBox <h3> id="mw-domtree-layers-heading")
 *   - packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/
 *     dom-tree.css
 *     (44px min-height + opener 44x44 hit area + focus ring)
 *
 * Selector-self-match guard: setUp() pre-strips comments per the uniformity
 * rule (task-7aa48a) so absence assertions don't false-match against prose
 * inside docblocks / line comments / CSS comments.
 */
class LiveEdit4b9d88AI1223LayersPanelA11yContractTest extends TestCase
{
    private string $domtreeJs;

    private string $domtreeJsStripped;

    private string $liveEditDomTreeJs;

    private string $liveEditDomTreeJsStripped;

    private string $domTreeCss;

    private string $domTreeCssStripped;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->domtreeJs = (string) file_get_contents(
            self::projectRoot()
            . '/packages/frontend-assets-libs/resources/local-libs/api/domtree.js'
        );
        $this->domtreeJsStripped = $this->stripJsComments($this->domtreeJs);

        $this->liveEditDomTreeJs = (string) file_get_contents(
            self::projectRoot()
            . '/packages/frontend-assets/resources/assets/api-core/services/components/live-edit/live-edit-dom-tree.js'
        );
        $this->liveEditDomTreeJsStripped = $this->stripJsComments($this->liveEditDomTreeJs);

        $this->domTreeCss = (string) file_get_contents(
            self::projectRoot()
            . '/packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/dom-tree.css'
        );
        $this->domTreeCssStripped = $this->stripCssComments($this->domTreeCss);
    }

    private function stripJsComments(string $source): string
    {
        $stripped = (string) preg_replace('~/\*[\s\S]*?\*/~', '', $source);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);
        return $stripped;
    }

    private function stripCssComments(string $source): string
    {
        return (string) preg_replace('~/\*[\s\S]*?\*/~', '', $source);
    }

    public function test_task_id_marker_present_in_all_three_files(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-4b9d88 / AI-1223',
            $this->domtreeJs,
            'AI-1223: task-id marker must be present in domtree.js'
        );
        $this->assertStringContainsString(
            'task-2026-05-28-4b9d88 / AI-1223',
            $this->liveEditDomTreeJs,
            'AI-1223: task-id marker must be present in live-edit-dom-tree.js'
        );
        $this->assertStringContainsString(
            'task-2026-05-28-4b9d88 / AI-1223',
            $this->domTreeCss,
            'AI-1223: task-id marker must be present in dom-tree.css'
        );
    }

    public function test_root_ul_declares_role_tree_and_aria_labelledby(): void
    {
        $this->assertMatchesRegularExpression(
            "/this\\.root\\.setAttribute\\(\\s*['\"]role['\"]\\s*,\\s*['\"]tree['\"]\\s*\\)/",
            $this->domtreeJsStripped,
            'AI-1223: createRoot must set role="tree" on the root <ul>'
        );
        $this->assertMatchesRegularExpression(
            "/this\\.root\\.setAttribute\\(\\s*['\"]aria-labelledby['\"]\\s*,\\s*['\"]mw-domtree-layers-heading['\"]\\s*\\)/",
            $this->domtreeJsStripped,
            'AI-1223: createRoot must set aria-labelledby="mw-domtree-layers-heading" on the root <ul>'
        );
    }

    public function test_treeitem_role_and_roving_tabindex_set_on_createItem(): void
    {
        $this->assertMatchesRegularExpression(
            "/li\\.setAttribute\\(\\s*['\"]role['\"]\\s*,\\s*['\"]treeitem['\"]\\s*\\)/",
            $this->domtreeJsStripped,
            'AI-1223: createItem must set role="treeitem" on every <li>'
        );
        $this->assertMatchesRegularExpression(
            "/li\\.setAttribute\\(\\s*['\"]tabindex['\"]\\s*,\\s*['\"]-1['\"]\\s*\\)/",
            $this->domtreeJsStripped,
            'AI-1223: createItem must set initial tabindex="-1" for roving focus'
        );
    }

    public function test_aria_expanded_initial_false_on_collapsible_items(): void
    {
        $this->assertMatchesRegularExpression(
            "/if\\s*\\(\\s*item\\.children\\.length\\s*\\)\\s*\\{[\\s\\S]*?li\\.setAttribute\\(\\s*['\"]aria-expanded['\"]\\s*,\\s*['\"]false['\"]\\s*\\)/",
            $this->domtreeJsStripped,
            'AI-1223: createItem must set aria-expanded="false" on items that have children'
        );
    }

    public function test_opener_icon_is_aria_hidden_true(): void
    {
        $this->assertStringContainsString(
            'mw-domtree-item-opener" aria-hidden="true"',
            $this->domtreeJsStripped,
            'AI-1223: opener <i> must carry aria-hidden="true" so AT consumes the row label'
        );
        $this->assertStringNotContainsString(
            '<i class="mw-domtree-item-opener"></i>',
            $this->domtreeJsStripped,
            'AI-1223: legacy bare opener (no aria-hidden) must be gone'
        );
    }

    public function test_open_mirrors_aria_expanded_true(): void
    {
        $this->assertMatchesRegularExpression(
            "/this\\.open\\s*=\\s*function[\\s\\S]*?li\\.setAttribute\\(\\s*['\"]aria-expanded['\"]\\s*,\\s*['\"]true['\"]\\s*\\)/",
            $this->domtreeJsStripped,
            'AI-1223: open() must mirror aria-expanded="true" on the expanded li'
        );
    }

    public function test_close_mirrors_aria_expanded_false(): void
    {
        $this->assertMatchesRegularExpression(
            "/this\\.close\\s*=\\s*function[\\s\\S]*?li\\.setAttribute\\(\\s*['\"]aria-expanded['\"]\\s*,\\s*['\"]false['\"]\\s*\\)/",
            $this->domtreeJsStripped,
            'AI-1223: close() must mirror aria-expanded="false" on the collapsed li'
        );
    }

    public function test_createChildren_sets_role_group_on_sublist(): void
    {
        $this->assertMatchesRegularExpression(
            "/this\\.createChildren\\s*=\\s*function[\\s\\S]*?list\\.setAttribute\\(\\s*['\"]role['\"]\\s*,\\s*['\"]group['\"]\\s*\\)/",
            $this->domtreeJsStripped,
            'AI-1223: createChildren must set role="group" on the sublist <ul>'
        );
    }

    public function test_selected_manages_roving_tabindex(): void
    {
        $this->assertMatchesRegularExpression(
            "/this\\.selected\\s*=\\s*function[\\s\\S]*?li\\[tabindex=\"0\"\\][\\s\\S]*?attr\\(\\s*['\"]tabindex['\"]\\s*,\\s*['\"]-1['\"]\\s*\\)[\\s\\S]*?node\\.setAttribute\\(\\s*['\"]tabindex['\"]\\s*,\\s*['\"]0['\"]\\s*\\)/",
            $this->domtreeJsStripped,
            'AI-1223: selected() must clear tabindex=0 from prior + set tabindex=0 on new node'
        );
    }

    public function test_keydown_handler_registered_with_arrow_key_branches(): void
    {
        $this->assertStringContainsString(
            ".on('keydown'",
            $this->domtreeJsStripped,
            'AI-1223: keydown listener must be registered in createItemEvents'
        );
        foreach (['ArrowDown', 'ArrowUp', 'ArrowRight', 'ArrowLeft', 'Home', 'End'] as $key) {
            $this->assertStringContainsString(
                "key === '" . $key . "'",
                $this->domtreeJsStripped,
                'AI-1223: keydown handler must branch on ' . $key
            );
        }
    }

    public function test_keydown_handler_calls_preventDefault_for_arrow_keys(): void
    {
        $this->assertMatchesRegularExpression(
            "/key === 'ArrowDown'[\\s\\S]*?e\\.preventDefault\\(\\)/",
            $this->domtreeJsStripped,
            'AI-1223: ArrowDown branch must call preventDefault so the page does not scroll'
        );
    }

    public function test_create_sets_first_treeitem_tabindex_zero(): void
    {
        $this->assertMatchesRegularExpression(
            "/this\\.root\\.querySelector\\(\\s*['\"]li\\[role=\"treeitem\"\\]['\"]\\s*\\)[\\s\\S]*?setAttribute\\(\\s*['\"]tabindex['\"]\\s*,\\s*['\"]0['\"]\\s*\\)/",
            $this->domtreeJsStripped,
            'AI-1223: create() must set tabindex="0" on the first treeitem for initial roving focus'
        );
    }

    public function test_live_edit_dom_tree_wires_h3_id(): void
    {
        $this->assertMatchesRegularExpression(
            "/querySelector\\(\\s*['\"]\\.mw-control-box-title['\"]\\s*\\)[\\s\\S]*?heading\\.id\\s*=\\s*['\"]mw-domtree-layers-heading['\"]/",
            $this->liveEditDomTreeJsStripped,
            'AI-1223: live-edit-dom-tree.js buildBox() must wire id="mw-domtree-layers-heading" on the controlBox <h3>'
        );
    }

    public function test_css_44px_min_height_on_treeitem_rows(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-domtree li\[role="treeitem"\]\s*\{[^}]*min-height:\s*44px/',
            $this->domTreeCssStripped,
            'AI-1223: dom-tree.css must declare min-height: 44px on .mw-domtree li[role="treeitem"]'
        );
    }

    public function test_css_opener_44x44_hit_area(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-domtree-item-opener\s*\{[^}]*width:\s*44px[^}]*height:\s*44px/s',
            $this->domTreeCssStripped,
            'AI-1223: dom-tree.css must give the opener a 44x44 hit area (visual chevron preserved via ::after)'
        );
    }

    public function test_css_focus_ring_on_treeitem(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-domtree li\[role="treeitem"\]:focus(-visible)?\s*\{[^}]*outline:\s*2px solid/',
            $this->domTreeCssStripped,
            'AI-1223: dom-tree.css must render a focus ring on the focused treeitem'
        );
    }

    public function test_legacy_unconditional_tabindex_zero_absent(): void
    {
        $this->assertStringNotContainsString(
            "li.setAttribute('tabindex', '0')",
            $this->domtreeJsStripped,
            'AI-1223: createItem must NOT unconditionally set tabindex="0" on every row — only the create() entrypoint sets it on the FIRST treeitem'
        );
    }
}
