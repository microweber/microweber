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
        $this->blade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php'
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
    public function secondary_grid_uses_responsive_2col_to_3col(): void
    {
        // Spec §6: 2-col mobile (default), 3-col at sm: breakpoint
        $this->assertMatchesRegularExpression(
            '/mw-add-content-group__items\s+grid\s+grid-cols-2\s+sm:grid-cols-3\s+gap-3/',
            $this->blade,
            'Secondary grid must be grid-cols-2 sm:grid-cols-3 per spec §6.'
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
    public function comment_block_uses_single_line_comments_only_in_xdata(): void
    {
        // Regression guard for the bug that killed the first attempt
        // at this slice — block comments with embedded double-quotes
        // INSIDE the x-data attribute terminate the HTML attribute
        // at the first `"` and corrupt the entire x-data evaluation.
        // The helper docblock now uses // single-line comments and
        // contains no embedded double-quotes.
        //
        // We verify by checking that within the x-data attribute
        // (delimited by `x-data="` opening to the closing `"`)
        // there is no `/*` opener. The Alpine helper's surrounding
        // comments are all `//` style now.
        $xdataStart = strpos($this->blade, 'x-data="');
        $this->assertNotFalse($xdataStart, 'x-data attribute must exist.');
        // Find the closing quote of the x-data attribute. The closing
        // is the `"` that's followed by either `>` or whitespace and
        // the next attribute. The block ends with `x-init="..."` so
        // we look for `}"\n` after the last `},`.
        $xdataChunk = substr($this->blade, $xdataStart, 3000);
        $this->assertStringNotContainsString(
            '/*',
            $xdataChunk,
            'No /* block comment may appear inside the x-data attribute — embedded "..." in prose would terminate the HTML attribute.'
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
