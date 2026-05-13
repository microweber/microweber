<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-513 — Live Edit element selection visibility.
 *
 * Audit task 2.1.1 surfaced four selection-visualisation problems
 * in `packages/frontend-assets/.../liveedit.css`. This contract test
 * pins the shape of the AI-513 override block that lands at the end
 * of liveedit.css:
 *
 *   - .element-active uses `outline: 2px solid #0d6efd` (the project-
 *     canonical primary blue), upgrading from the prior 1px dotted
 *     #a6a6a6 that was the audit's primary complaint.
 *   - .moveit-hover renders with a distinct 1px dashed #6b7280 so
 *     hover is visually distinguishable from selection at a glance.
 *   - .mw-sorthandle-parent-outline renders with 1px dashed half-
 *     opacity primary blue so the container relationship is hinted
 *     via colour-family rather than the prior identical-to-hover
 *     solid gray.
 *   - .element-active:focus-visible adds an outline-offset: 4px so
 *     keyboard focus produces a "double ring" effect outside the
 *     selection outline.
 *
 * Out of scope (deferred to AI-513a/b per the CSS comment):
 *   - Breadcrumb path UI for nested element selection.
 *   - Subtle background tint on the selected element.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Ai513LiveEditSelectionContractTest extends TestCase
{
    /**
     * `frontend-assets-libs` is the served-source-of-truth: its
     * build.mjs copies `local-libs/css/*` directly to
     * `public/vendor/microweber-packages/frontend-assets-libs/css/`.
     * The canvas iframe consumes the served file, so this is the
     * copy that MUST carry the AI-513 block.
     *
     * `frontend-assets` is the SCSS-pipeline package; the legacy
     * `microweber/css/liveedit.css` inside it is the historical
     * mirror copy of the same content. We keep both source copies
     * in sync so future code archaeologists don't have to choose.
     */
    private const LIVEEDIT_CSS_SERVED  = 'packages/frontend-assets-libs/resources/local-libs/css/liveedit.css';
    private const LIVEEDIT_CSS_MIRROR  = 'packages/frontend-assets/resources/assets/css/microweber/css/liveedit.css';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    private function ai513Block(string $rel = null): string
    {
        $rel = $rel ?? self::LIVEEDIT_CSS_SERVED;
        $css = $this->read($rel);
        $start = strpos($css, 'AI-513');
        $this->assertNotFalse($start, "{$rel} must contain the AI-513 marker comment.");
        return substr($css, $start);
    }

    #[Test]
    public function ai513_marker_comment_is_present_and_traceable(): void
    {
        $block = $this->ai513Block();
        $this->assertStringContainsString(
            'AI-513 — Live Edit element selection visibility',
            $block,
            'The marker comment must include the human-readable subject line so future readers can grep for it.'
        );
    }

    /**
     * Shape facts the AI-513 block must contain. Each row pins one
     * specific selector+declaration pair so future overrides can't
     * silently regress the visibility upgrade.
     */
    public static function shapeFactsProvider(): array
    {
        return [
            // Selected element — strong solid 2px blue outline.
            'selected outline width + style + color' => ['outline: 2px solid #0d6efd'],
            'selected outline-offset 0'              => ['outline-offset: 0'],
            'selected selector'                      => ['.element-active'],

            // Hover — distinct 1px dashed gray.
            'hover dashed gray outline'   => ['outline: 1px dashed #6b7280'],
            'hover selector'              => ['.moveit-hover'],
            'hover :hover sibling'        => ['.moveit-hover:hover'],

            // Parent outline — translucent blue dashed.
            'parent translucent blue dashed' => ['outline: 1px dashed rgba(13, 110, 253, 0.5)'],
            'parent selector'                => ['.mw-sorthandle-parent-outline'],

            // Keyboard focus — outline-offset 4px stacked atop selection.
            'focus-visible pseudo-class'  => ['.element-active:focus-visible'],
            'focus offset 4px'            => ['outline-offset: 4px'],
        ];
    }

    #[Test]
    #[DataProvider('shapeFactsProvider')]
    public function ai513_block_contains_each_shape_fact(string $needle): void
    {
        $block = $this->ai513Block();
        $this->assertStringContainsString(
            $needle,
            $block,
            "AI-513 block must contain `{$needle}`."
        );
    }

    #[Test]
    public function ai513_selected_color_matches_project_primary_blue(): void
    {
        $block = $this->ai513Block();

        // Bootstrap #0d6efd is the MwColors::Blue 500 anchor (RGB 13,110,253).
        // The rgba() variant in the parent rule must use the same triplet
        // so the colour family stays unified across selection states.
        $this->assertStringContainsString('#0d6efd', $block);
        $this->assertMatchesRegularExpression(
            '/rgba\(\s*13\s*,\s*110\s*,\s*253\s*,\s*0\.5\s*\)/',
            $block,
            'Parent-outline translucent rgba must use the same 13,110,253 triplet as MwColors::Blue 500.'
        );
    }

    #[Test]
    public function ai513_block_is_at_end_of_served_liveedit_css(): void
    {
        // The block must be appended at the end of the served
        // liveedit.css so its later-in-cascade position overrides
        // the earlier .element-active / .moveit-hover / .mw-sorthandle-
        // parent-outline definitions without needing !important.
        $css = $this->read(self::LIVEEDIT_CSS_SERVED);
        $markerPos = strpos($css, 'AI-513');
        $this->assertNotFalse($markerPos);

        // The earlier (upstream) `.element-active { outline: 1px
        // dotted #A6A6A6` rule must exist BEFORE the AI-513 marker
        // so cascade ordering is correct. Match case-insensitive
        // because the two source copies use different hex casing.
        $this->assertMatchesRegularExpression(
            '/outline:\s*1px\s+dotted\s+#a6a6a6;/i',
            $css,
            'Upstream .element-active definition with 1px dotted #A6A6A6 must still exist (we override via cascade, not by editing in place).'
        );
        // Use a case-insensitive search for the upstream position too.
        $upstreamPos = stripos($css, 'outline: 1px dotted #a6a6a6');
        $this->assertLessThan(
            $markerPos,
            $upstreamPos,
            'AI-513 override block must come AFTER the upstream definitions for cascade to take effect.'
        );
    }

    #[Test]
    public function ai513_block_is_mirrored_in_frontend_assets_copy(): void
    {
        // The same AI-513 override block must exist in both source
        // copies so the two stay in sync. The frontend-assets copy
        // is the SCSS-pipeline package's legacy plain-CSS mirror;
        // future maintainers reading either file should see the
        // same shape.
        $mirrorBlock = $this->ai513Block(self::LIVEEDIT_CSS_MIRROR);
        $this->assertStringContainsString('.element-active', $mirrorBlock);
        $this->assertStringContainsString('outline: 2px solid #0d6efd', $mirrorBlock);
        $this->assertStringContainsString('outline-offset: 4px', $mirrorBlock);
    }

    #[Test]
    public function ai513_does_not_use_important(): void
    {
        $block = $this->ai513Block();

        // !important is a code-smell when cascade ordering already
        // gives us the win. Pin that none of the four AI-513 rules
        // resort to !important — if a future reader adds it, the
        // test fails and forces them to fix cascade instead.
        $this->assertStringNotContainsString(
            '!important',
            $block,
            'AI-513 rules must rely on cascade order, not !important.'
        );
    }
}
