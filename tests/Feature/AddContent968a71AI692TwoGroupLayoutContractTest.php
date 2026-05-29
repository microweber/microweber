<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-968a71 / AI-692 — Add-Content Modal Slice 2:
 * Two-group layout per designer spec §A2 + §A3 (the two action
 * classes have different mental models — in-place vs go-elsewhere
 * — and must not read as visually equal).
 *
 * Changes shipped:
 *
 *   1. Each action in AdminLiveEditPage::addContentAction now
 *      carries a `'group' => 'primary'|'secondary'` key.
 *      `addToCurrentPageAction` is the sole 'primary' (inline-
 *      insertion intent); the 5 add-X actions are 'secondary'
 *      (navigate-to-admin-form intent).
 *
 *   2. add-content-modal.blade.php now renders TWO `<section>`
 *      wrappers instead of one grid. Each section has its own
 *      `<h3>` header ("On this page" / "New content"), its own
 *      foreach loop with the appropriate group filter, and its
 *      own layout (primary = flex-col full-width; secondary =
 *      2-col mobile / 3-col desktop grid).
 *
 *   3. New Alpine helper `hasVisibleCardsInGroup(group)` queries
 *      `[data-mw-add-content-card][data-mw-add-content-group=X]`
 *      and respects the search `q` — so typing a keyword hides
 *      the group header when its cards no longer match.
 *
 *   4. Each card now carries `data-mw-add-content-group="primary"`
 *      or `"secondary"` so the helper can partition.
 *
 *   5. Empty state stays outside both groups, fires when no card
 *      in EITHER group matches.
 *
 * IMPORTANT BUG FIXED DURING SHIP: the original implementation
 * had a slash-star ... double-quoted "page" ... star-slash style
 * block comment INSIDE the x-data attribute, which terminated
 * the HTML attribute at the first embedded `"` and corrupted
 * the entire x-data block. Switched to double-slash single-line
 * comments with no embedded double-quotes. The Alpine
 * `JSON.stringify(group)` inside `hasVisibleCardsInGroup` is the
 * safe way to interpolate a string into a CSS attribute-selector
 * inside an HTML attribute inside a JS expression — keeps every
 * layer of escaping correct.
 */
class AddContent968a71AI692TwoGroupLayoutContractTest extends TestCase
{
    private string $page;
    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->page = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php'
        ));
        // task-2026-05-18-76a360 pin-evolution: the Alpine.data() factory
        // body was moved out of add-content-modal.blade.php into
        // live-edit.blade.php @push('scripts') so scripts execute at
        // INITIAL page load (Filament/Livewire-morph-inserted modal HTML
        // does NOT execute embedded <script> tags). Concatenate both
        // files so existing AI-692 factory-body assertions
        // (hasVisibleCardsInGroup, JSON.stringify(group)) still find
        // their targets in the union. Structural assertions on x-data
        // attributes still match the modal blade contents.
        $this->blade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php'
        )) . "\n" . (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit.blade.php'
        ));
    }

    #[Test]
    public function actions_carry_group_key_in_php(): void
    {
        // Primary = single Add a block action
        $this->assertMatchesRegularExpression(
            '/\'action\'\s*=>\s*\'addToCurrentPageAction\'[\s\S]*?\'group\'\s*=>\s*\'primary\'/',
            $this->page,
            "addToCurrentPageAction must carry 'group' => 'primary'."
        );
        // Secondary = 5 add-X actions
        $secondaryActions = ['addPageAction', 'addPostAction', 'addProductAction', 'addImageAction', 'addCategoryAction'];
        foreach ($secondaryActions as $action) {
            $this->assertMatchesRegularExpression(
                '/\'action\'\s*=>\s*\'' . preg_quote($action, '/') . '\'[\s\S]*?\'group\'\s*=>\s*\'secondary\'/',
                $this->page,
                "{$action} must carry 'group' => 'secondary'."
            );
        }
    }

    #[Test]
    public function blade_partitions_actions_into_primary_and_secondary(): void
    {
        // Partition logic must split $actions by 'group' key
        // Use multiline-friendly regex — array_filter lambda body
        // contains parens that a [^)]* class can't cross. Anchor on
        // the variable assignment + array_filter($actions + the
        // 'primary' / 'secondary' literal anywhere in the same line.
        $this->assertMatchesRegularExpression(
            '/\$mwAddContentPrimary\s*=\s*array_values\(array_filter\(\$actions.*?\'primary\'/s',
            $this->blade
        );
        $this->assertMatchesRegularExpression(
            '/\$mwAddContentSecondary\s*=\s*array_values\(array_filter\(\$actions.*?\'secondary\'/s',
            $this->blade
        );
    }

    #[Test]
    public function two_section_wrappers_with_headers_are_rendered(): void
    {
        // Primary section
        $this->assertMatchesRegularExpression(
            '/<section\s+class="mw-add-content-group\s+mw-add-content-group--primary"[\s\S]*?>\s*\n[\s\S]*?<h3\s+id="mw-add-content-group-primary-heading"[^>]*>\s*On this page\s*<\/h3>/s',
            $this->blade,
            'Primary section must wrap a <section> with <h3>On this page</h3>.'
        );
        // Secondary section
        $this->assertMatchesRegularExpression(
            '/<section\s+class="mw-add-content-group\s+mw-add-content-group--secondary[^"]*"[\s\S]*?>\s*\n[\s\S]*?<h3\s+id="mw-add-content-group-secondary-heading"[^>]*>\s*New content\s*<\/h3>/s',
            $this->blade,
            'Secondary section must wrap a <section> with <h3>New content</h3>.'
        );
    }

    #[Test]
    public function secondary_grid_always_three_columns(): void
    {
        // task-2026-05-21-30040a / AI-870 PIN-EVOLUTION (2nd):
        // Original AI-692 spec shipped `grid-cols-2 sm:grid-cols-3`.
        // AI-870 found the 2→3 col transition at sm: (640px) caused
        // a Z-flow reflow artefact; always-3 col was the fix.
        // task-2026-05-27-4b1344 / AI-1139 PIN-EVOLUTION: mobile
        // bottom-sheet redesign needs a responsive grid — changed
        // from always-3 to `grid-cols-2 md:grid-cols-3`. The md:
        // breakpoint (768px) is safe — the reflow trigger was sm:
        // (640px) specifically. Updated in place per pin-evolution rule.
        $this->assertMatchesRegularExpression(
            '/mw-add-content-group__items\s+grid\s+grid-cols-2\s+md:grid-cols-3\s+gap-3/',
            $this->blade,
            'Secondary grid must be grid-cols-2 md:grid-cols-3 gap-3 (responsive: 2-col mobile, 3-col desktop ≥md, per AI-1139).'
        );
        // Safety: md: breakpoint (768px) is safe; sm: (640px) is NOT.
        $this->assertStringNotContainsString(
            'sm:grid-cols-3',
            $this->blade,
            'Secondary grid must NOT use sm:grid-cols-3 — the 640px sm: breakpoint caused the original AI-870 Z-flow artefact.'
        );
    }

    #[Test]
    public function primary_uses_flex_col_full_width_not_grid(): void
    {
        // Primary section: single full-width card (no grid)
        $this->assertMatchesRegularExpression(
            '/mw-add-content-group--primary[\s\S]*?mw-add-content-group__items\s+flex\s+flex-col\s+gap-3/',
            $this->blade,
            'Primary section uses flex-col (full-width single card), not a grid.'
        );
    }

    #[Test]
    public function cards_carry_data_group_attribute(): void
    {
        // Both groups must add data-mw-add-content-group="X" so
        // the Alpine helper can partition cards by group at query time.
        $this->assertStringContainsString(
            'data-mw-add-content-group="primary"',
            $this->blade
        );
        $this->assertStringContainsString(
            'data-mw-add-content-group="secondary"',
            $this->blade
        );
    }

    #[Test]
    public function alpine_has_visible_cards_in_group_helper_is_declared(): void
    {
        $this->assertStringContainsString(
            'hasVisibleCardsInGroup(group) {',
            $this->blade,
            'x-data must declare hasVisibleCardsInGroup(group) method.'
        );
        // It must read this.q and short-circuit on empty
        $this->assertMatchesRegularExpression(
            "/hasVisibleCardsInGroup\\(group\\)\\s*\\{[\\s\\S]*?if\\s*\\(this\\.q\\s*===\\s*''\\)\\s*return\\s+true/s",
            $this->blade
        );
        // Must use JSON.stringify(group) to safely build the attribute-
        // selector inside an HTML attribute inside a JS expression.
        // This keeps every escape layer correct (vs concatenating
        // double-quoted strings into the selector — which would
        // require triple-escaped quotes and was the breakage that
        // killed the first attempt at this slice).
        $this->assertStringContainsString(
            'JSON.stringify(group)',
            $this->blade,
            'hasVisibleCardsInGroup must use JSON.stringify(group) for safe attribute-selector interpolation.'
        );
    }

    #[Test]
    public function group_sections_use_x_show_with_helper(): void
    {
        // Each section's x-show must call the helper
        $this->assertStringContainsString(
            "x-show=\"hasVisibleCardsInGroup('primary')\"",
            $this->blade
        );
        $this->assertStringContainsString(
            "x-show=\"hasVisibleCardsInGroup('secondary')\"",
            $this->blade
        );
    }

    #[Test]
    public function single_outer_grid_wrapper_is_removed(): void
    {
        // Regression guard — the OLD single 2-col grid wrapper
        // ('<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">'
        // immediately after the announcement div) must be gone.
        // The new layout uses two <section> wrappers instead.
        $this->assertDoesNotMatchRegularExpression(
            '/<div\s+class="grid\s+grid-cols-1\s+sm:grid-cols-2\s+gap-3"\s*>\s*\n\s*\n\s*@foreach\s*\(\s*\$actions\s+as/',
            $this->blade,
            'The old single grid-cols-1 sm:grid-cols-2 wrapper around @foreach($actions) must be removed.'
        );
    }

    #[Test]
    public function xdata_is_named_alpine_data_reference_not_inline_object(): void
    {
        // Pin-evolution pattern: AI-790 (task-2026-05-17-255d24) lifted
        // the inline x-data object into a named Alpine.data() registration
        // because `//` line comments inside the JS contained literal `"`
        // characters (e.g. `// primary "Add a block" card`) and the HTML
        // attribute parser terminated at the first embedded `"`, dumping
        // the rest of the JS as visible body text. Original AI-692
        // assertion (guard against `/*` block comments) is now subsumed
        // by this stronger assertion: the x-data attribute on the modal
        // root must be a bare identifier reference, not an inline `{...}`
        // expression. Any future code that adds inline JS to x-data
        // re-introduces the AI-790 defect class and fails this test.
        $this->assertMatchesRegularExpression(
            '/x-data="addContentModal"/',
            $this->blade,
            'Modal root x-data must be `x-data="addContentModal"` — bare named-reference (per AI-790 / task-255d24 escape-leak fix).'
        );
        // Negative regression-guard: NO inline `x-data="{` object on
        // .mw-add-content-modal-root. Any inline JS in x-data
        // re-introduces the AI-790 defect class. Strip Blade
        // `{{-- ... --}}` comments first (selector-self-match guard
        // family — the AI-790 docblock legitimately mentions
        // `x-data="{...}"` as the BEFORE state).
        $bladeStripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $this->blade);
        $this->assertDoesNotMatchRegularExpression(
            '/mw-add-content-modal-root[\s\S]{0,200}x-data="\{/',
            $bladeStripped,
            'Modal root x-data MUST NOT use inline `{ ... }` object — extract to Alpine.data() registration per AI-790.'
        );
    }

    #[Test]
    public function task_id_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('task-2026-05-16-968a71', $this->page);
        $this->assertStringContainsString('task-2026-05-16-968a71', $this->blade);
    }

    #[Test]
    public function ai691_carry_over_titles_still_intact(): void
    {
        // Regression guard — AI-691's title changes ("New X" → "X")
        // must survive AI-692's group-key addition.
        $this->assertStringContainsString("'title' => 'Page',", $this->page);
        $this->assertStringContainsString("'title' => 'Post',", $this->page);
        $this->assertStringContainsString("'title' => 'Product',", $this->page);
        $this->assertStringContainsString("'title' => 'Image',", $this->page);
        $this->assertStringContainsString("'title' => 'Category',", $this->page);
        $this->assertStringContainsString("'title' => 'Add a block to this page',", $this->page);
    }
}
