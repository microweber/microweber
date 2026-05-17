<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-551f7e / AI-775 Slice A — Layers panel meaningful-layers
 * filter. Jira: https://microweber.atlassian.net/browse/AI-775
 *
 * Designer's Round-9 audit caught 1,787 `mw-domtree-item` rows for a
 * 6-section demo page. Root cause: `mw.DomTree` defaults to walking
 * every `.edit` element via `targetDocument.querySelectorAll(this.settings.selector)`
 * — Microweber marks every editable HTML fragment with `.edit`, so the
 * Layers panel sees inline spans, text bites, every paragraph, etc.
 * Acceptance for Slice A alone (per designer): <=100 rows.
 *
 * Slice A approach: add an opt-in `meaningfulOnly` setting + a
 * `meaningfulSelector` that whitelists structural containers only
 * (`.main-content, .module-layouts, .module, [data-type]`). When the
 * Layers panel consumer (LiveEditDOMTree) sets `meaningfulOnly: true`,
 * the walker uses meaningfulSelector for both the initial
 * querySelectorAll AND the recursive descent (createChildren +
 * createChildrenInto). Non-meaningful wrappers collapse — meaningful
 * descendants attach to the nearest enclosing list, preserving the
 * structural hierarchy without rendering pure-markup intermediate
 * <div>s.
 *
 * Slices B (per-item show/hide/lock/drag) + C (label spacing polish)
 * are deferred per designer's Slice A acceptance criteria.
 *
 * Back-compat: meaningfulOnly defaults to false. Any DomTree consumer
 * that didn't opt in stays on the broad `.edit` walk — no behaviour
 * change unless meaningfulOnly is explicitly set.
 */
class LiveEdit551f7eAI775LayersMeaningfulOnlyContractTest extends TestCase
{
    private string $domtreeSource;
    private string $consumerSource;
    private string $servedDomtree;
    private string $servedAdminJs;

    protected function setUp(): void
    {
        parent::setUp();
        $this->domtreeSource = (string) file_get_contents(base_path(
            'packages/frontend-assets-libs/resources/local-libs/api/domtree.js'
        ));
        $this->consumerSource = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/api-core/services/components/live-edit/live-edit-dom-tree.js'
        ));
        $this->servedDomtree = file_exists(base_path(
            'public/vendor/microweber-packages/frontend-assets-libs/api/domtree.js'
        )) ? (string) file_get_contents(base_path(
            'public/vendor/microweber-packages/frontend-assets-libs/api/domtree.js'
        )) : '';
        // LiveEditDOMTree consumer is bundled into live-edit-app.js
        // (the Vue live-edit chunk), NOT admin.js. The DomTree library
        // default lands in admin.js separately. Probe live-edit-app.js
        // for the consumer's opt-in.
        $this->servedAdminJs = file_exists(base_path(
            'public/vendor/microweber-packages/frontend-assets/build/live-edit-app.js'
        )) ? (string) file_get_contents(base_path(
            'public/vendor/microweber-packages/frontend-assets/build/live-edit-app.js'
        )) : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — new DomTree defaults present in source
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function domtree_defaults_include_meaningful_only_false(): void
    {
        // The default is false so back-compat is preserved for any
        // consumer that hasn't opted in.
        $this->assertMatchesRegularExpression(
            '/meaningfulOnly:\s*false/',
            $this->domtreeSource,
            'DomTree defaults must declare `meaningfulOnly: false` to preserve back-compat for non-opted-in consumers.'
        );
    }

    #[Test]
    public function domtree_defaults_include_meaningful_selector_whitelist(): void
    {
        // The whitelist covers the canonical Microweber structural
        // containers. Module modules + module-layouts + main-content +
        // anything with data-type (covers element modules). NOT .edit
        // (that's the broad inline-text default).
        $this->assertMatchesRegularExpression(
            "/meaningfulSelector:\s*'\.main-content,\s*\.module-layouts,\s*\.module,\s*\[data-type\]'/",
            $this->domtreeSource,
            'DomTree meaningfulSelector default must whitelist `.main-content, .module-layouts, .module, [data-type]`.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — walker switches on meaningfulOnly
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function create_picks_walker_selector_via_meaningful_only_flag(): void
    {
        // The initial walk must consult settings.meaningfulOnly and
        // use meaningfulSelector when true, selector when false.
        $this->assertMatchesRegularExpression(
            '/walkSelector\s*=\s*this\.settings\.meaningfulOnly\s*\?\s*this\.settings\.meaningfulSelector\s*:\s*this\.settings\.selector/',
            $this->domtreeSource,
            'create() must branch the walker selector on settings.meaningfulOnly.'
        );
        // querySelectorAll uses the chosen variable, not the hardcoded
        // settings.selector.
        $this->assertMatchesRegularExpression(
            '/querySelectorAll\(walkSelector\)/',
            $this->domtreeSource,
            'create() must call querySelectorAll(walkSelector), not settings.selector directly, so meaningfulOnly is honoured.'
        );
    }

    #[Test]
    public function create_children_filters_descendants_under_meaningful_only(): void
    {
        // createChildren must check curr.matches(meaningfulSelector)
        // when meaningfulOnly is active, and skip non-matching nodes
        // without breaking recursion into their meaningful descendants.
        $this->assertMatchesRegularExpression(
            '/var\s+meaningfulOnly\s*=\s*this\.settings\.meaningfulOnly/',
            $this->domtreeSource,
            'createChildren must hoist settings.meaningfulOnly into a local for the recursive walk.'
        );
        $this->assertMatchesRegularExpression(
            '/isMeaningful\s*=\s*!meaningfulOnly\s*\|\|\s*\(typeof\s+curr\.matches\s*===\s*[\'"]function[\'"][^)]*\.matches\(meaningfulSelector\)\)/',
            $this->domtreeSource,
            'createChildren must compute isMeaningful = !meaningfulOnly || curr.matches(meaningfulSelector).'
        );
    }

    #[Test]
    public function create_children_into_collapses_wrappers(): void
    {
        // createChildrenInto is the helper that walks past pure-markup
        // wrappers without creating a new sub-list. Meaningful
        // descendants attach to the existing parent list.
        $this->assertStringContainsString(
            'this.createChildrenInto = function (node, parentList)',
            $this->domtreeSource,
            'createChildrenInto helper must exist for collapsing non-meaningful wrappers.'
        );
        // Inside the helper, meaningful descendants attach to parentList
        // (NOT a new sub-list), preserving depth without rendering the
        // intermediate wrapper.
        $start = strpos($this->domtreeSource, 'this.createChildrenInto = function (node, parentList)');
        $this->assertNotFalse($start);
        $slice = substr($this->domtreeSource, $start, 1400);
        $this->assertStringContainsString(
            'parentList.appendChild(item)',
            $slice,
            'createChildrenInto must append meaningful descendants to the existing parentList.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — LiveEditDOMTree consumer opts in
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function live_edit_dom_tree_consumer_passes_meaningful_only_true(): void
    {
        // The Layers panel consumer (the only out-of-the-box DomTree
        // user that needs the cardinality cap) must set the flag.
        $this->assertMatchesRegularExpression(
            '/meaningfulOnly:\s*true/',
            $this->consumerSource,
            'LiveEditDOMTree.buildTree() must pass `meaningfulOnly: true` to new DomTree({...}).'
        );
    }

    #[Test]
    public function live_edit_dom_tree_consumer_does_not_override_selector(): void
    {
        // Consumer relies on the meaningfulSelector default — must not
        // pass its own `selector:` override (would silently bypass the
        // meaningful filter).
        $start = strpos($this->consumerSource, 'new this.settings.target.DomTree({');
        $this->assertNotFalse($start);
        $end = strpos($this->consumerSource, '});', $start);
        $this->assertNotFalse($end);
        $constructorBlock = substr($this->consumerSource, $start, $end - $start);
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*selector:/m',
            $constructorBlock,
            'LiveEditDOMTree consumer must not pass its own `selector:` override — relies on the meaningfulSelector default.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — built bundles carry the change (runtime probe per task-bc28fd lineage)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function served_domtree_lib_carries_meaningful_only_default(): void
    {
        if ($this->servedDomtree === '') {
            $this->markTestSkipped('Served frontend-assets-libs/api/domtree.js absent — run `cd packages/frontend-assets-libs && npm run build`.');
        }
        $this->assertStringContainsString(
            'meaningfulOnly',
            $this->servedDomtree,
            'Served domtree.js must carry the meaningfulOnly setting.'
        );
        $this->assertStringContainsString(
            'meaningfulSelector',
            $this->servedDomtree,
            'Served domtree.js must carry the meaningfulSelector default.'
        );
    }

    #[Test]
    public function served_live_edit_app_js_carries_consumer_opt_in(): void
    {
        if ($this->servedAdminJs === '') {
            $this->markTestSkipped('Served frontend-assets/build/live-edit-app.js absent — run `cd packages/frontend-assets && npm run build`.');
        }
        // Vite/Rollup will minify but the property name + value pair
        // survives as `meaningfulOnly:!0` or `meaningfulOnly: true`.
        // LiveEditDOMTree consumer bundles into live-edit-app.js (the
        // Vue live-edit chunk) — not admin.js.
        $this->assertMatchesRegularExpression(
            '/meaningfulOnly\s*:\s*(true|!0)/',
            $this->servedAdminJs,
            'Served live-edit-app.js must carry `meaningfulOnly: true` (or minified `!0`) for the LiveEditDOMTree consumer.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — markers + back-compat preservation
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai775_markers_present_in_source(): void
    {
        $this->assertStringContainsString('task-2026-05-17-551f7e', $this->domtreeSource);
        $this->assertStringContainsString('AI-775', $this->domtreeSource);
        $this->assertStringContainsString('task-2026-05-17-551f7e', $this->consumerSource);
        $this->assertStringContainsString('AI-775', $this->consumerSource);
    }

    #[Test]
    public function legacy_dot_edit_selector_default_preserved(): void
    {
        // Back-compat: the existing default `selector: '.edit'` is
        // untouched. Consumers that didn't opt in to meaningfulOnly
        // walk the broad selector exactly as before.
        $this->assertMatchesRegularExpression(
            "/selector:\s*'\.edit'/",
            $this->domtreeSource,
            'Legacy `selector: .edit` default must remain untouched for back-compat.'
        );
    }
}
