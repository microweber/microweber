<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-175 / AI-236 (2026-05-10) — Live Edit mobile editing
 * chrome (P1 ROOT UNBLOCKER).
 *
 * agent-test reported (at /admin/live-edit?url=AI207-Test-Blog-
 * Post 390×844):
 *   - No visible cursor after double-click on editable element
 *   - No focus ring / highlight around element in edit mode
 *   - No inline/bottom-sheet toolbar surfaces
 *
 * This unblocks 3 other tickets:
 *   - AI-208 (Blog Live Edit audit)
 *   - AI-218 (Link Picker modal)
 *   - AI-222 (Link Picker UX audit)
 *
 * Root cause: the existing `.element-active` rule in
 * `frontend-assets-libs/css/liveedit.css` line 1697:
 *   `outline: 1px dotted #A6A6A6; box-shadow: inset 0 0 1px #fff`
 * is a barely-visible 1px gray dotted outline that disappears
 * against most backgrounds on mobile (especially in dark mode,
 * against high-contrast images, or colored sections). The
 * element IS getting `.element-active` class on dblclick (JS
 * edit-mode infrastructure works) but the visual signal is too
 * subtle for mobile users to perceive.
 *
 * Cycle-175 fix scoped to `public-touch.css` (Bootstrap template;
 * loaded INSIDE the iframe by the public master layout) so a
 * higher-specificity rule overrides the `cycle-N` 1px dotted
 * default without requiring a separate frontend-assets package
 * rebuild:
 *
 *   1. `.element-active` outline → 2px solid Bootstrap-blue with
 *      outline-offset + outer box-shadow halo for high-contrast
 *      focus ring on every background.
 *   2. `caret-color` on `.element-active *` so the blinking text
 *      cursor is visible.
 *   3. `#mw-text-editor` inline-toolbar bottom-sheet positioning
 *      with 44×44 button floor.
 *   4. `.plain-text` empty-state min-height 12 → 24 so empty
 *      paragraphs are tappable.
 *
 * Browser-verified at 390×844 inside the iframe:
 *   outline: rgb(13, 110, 253) solid 2px (Bootstrap blue)
 *   caret-color: rgb(13, 110, 253)
 *   box-shadow halo + inset white (preserved)
 */
class Ai236LiveEditMobileEditingChromeContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_175_anchor(): void
    {
        $src = $this->read('public/templates/bootstrap/css/public-touch.css');
        $this->assertMatchesRegularExpression('/[Cc]ycle-175/', $src,
            'public-touch.css MUST carry the cycle-175 anchor.');
        $this->assertStringContainsString('AI-236', $src,
            'public-touch.css MUST carry the AI-236 anchor.');
        $this->assertStringContainsString('ROOT UNBLOCKER', $src,
            'AI-236 anchor MUST mark this fix as the ROOT UNBLOCKER '
            . 'for AI-208 / AI-218 / AI-222 — that lineage is '
            . 'load-bearing context for future maintainers.');
    }

    #[Test]
    public function ai_236_element_active_high_contrast_focus_ring(): void
    {
        $src = $this->read('public/templates/bootstrap/css/public-touch.css');
        // The 2px solid blue outline with 2px offset.
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit\s+\.element-active[\s\S]{0,500}outline:\s*2px\s+solid\s+#0d6efd\s*!important/m',
            $src,
            'public-touch.css MUST set 2px solid Bootstrap-blue outline '
            . '!important on .mw-live-edit .element-active so the '
            . 'edit-mode focus ring is high-contrast on every '
            . 'background (was 1px dotted #A6A6A6 — invisible on '
            . 'mobile).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit\s+\.element-active[\s\S]{0,500}outline-offset:\s*2px\s*!important/m',
            $src,
            'public-touch.css MUST set 2px outline-offset on the '
            . 'edit-mode focus ring so the blue ring sits OUTSIDE '
            . 'the element border for visual clarity.'
        );
        // The box-shadow halo PLUS the original inset white shadow
        // (cycle-175 must preserve the existing inset white pin).
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit\s+\.element-active[\s\S]{0,500}box-shadow:[\s\S]{0,200}rgba\(13,\s*110,\s*253,\s*0\.25\)/m',
            $src,
            'public-touch.css MUST add outer box-shadow halo at '
            . 'rgba(13,110,253,0.25) for anti-aliased ring.'
        );
    }

    #[Test]
    public function ai_236_caret_color_on_active_element(): void
    {
        $src = $this->read('public/templates/bootstrap/css/public-touch.css');
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit\s+\.element-active\s*\*[\s\S]{0,400}caret-color:\s*#0d6efd\s*!important/m',
            $src,
            'public-touch.css MUST set caret-color on .element-active '
            . 'descendants so the blinking text cursor is visible '
            . '(tester reported "no visible cursor" — this is the '
            . 'fix).'
        );
    }

    #[Test]
    public function ai_236_inline_toolbar_bottom_sheet_44(): void
    {
        $src = $this->read('public/templates/bootstrap/css/public-touch.css');

        // Bottom-sheet positioning — fixed to viewport bottom.
        $this->assertMatchesRegularExpression(
            '/#mw-text-editor[\s\S]{0,400}position:\s*fixed\s*!important/m',
            $src,
            'public-touch.css MUST position #mw-text-editor fixed '
            . '!important so the inline WYSIWYG toolbar surfaces '
            . 'reliably on mobile regardless of where the editing '
            . 'element sits on the page.'
        );
        $this->assertMatchesRegularExpression(
            '/#mw-text-editor[\s\S]{0,400}bottom:\s*0\s*!important/m',
            $src,
            'public-touch.css MUST anchor #mw-text-editor to bottom: '
            . '0 !important so the toolbar acts as a bottom-sheet '
            . 'above the iOS keyboard.'
        );
        // Toolbar buttons floored to 44×44.
        $this->assertMatchesRegularExpression(
            '/#mw-text-editor[\s\S]{0,800}min-height:\s*44px\s*!important/m',
            $src,
            'public-touch.css MUST floor #mw-text-editor toolbar '
            . 'buttons (Bold / Italic / Link / Heading / etc.) to '
            . 'min-height: 44px !important so they meet the WCAG '
            . '2.5.5 / iOS HIG 44 floor.'
        );
    }

    #[Test]
    public function ai_236_empty_plain_text_24_min_height(): void
    {
        $src = $this->read('public/templates/bootstrap/css/public-touch.css');
        // 12px is too small to reliably tap into edit mode on touch
        // devices. Bumped to 24 so empty paragraphs have a usable
        // hit area.
        $this->assertMatchesRegularExpression(
            '/body\.mw-live-edit\s+\.plain-text[\s\S]{0,300}min-height:\s*24px/m',
            $src,
            'public-touch.css MUST bump body.mw-live-edit .plain-text '
            . 'min-height to 24px so empty paragraphs are tappable '
            . 'on mobile (was 12 — too small to reliably enter edit '
            . 'mode).'
        );
    }

    #[Test]
    public function cycle_175_inside_touch_media_query(): void
    {
        $src = $this->read('public/templates/bootstrap/css/public-touch.css');

        $anchorPos = strpos($src, 'cycle-175');
        $this->assertNotFalse($anchorPos, 'cycle-175 anchor must be present.');
        // Walk back to find the most recent enclosing @media block.
        $before = substr($src, 0, $anchorPos);
        $mediaPos = strrpos($before, '@media');
        $this->assertNotFalse($mediaPos, 'cycle-175 rules MUST sit inside an @media block.');
        $mediaLine = substr($src, $mediaPos, 100);
        $this->assertMatchesRegularExpression(
            '/max-width:\s*768px/',
            $mediaLine,
            'cycle-175 @media MUST include max-width: 768px (public '
            . 'mobile breakpoint, same as the cycle-N rules above).'
        );
        $this->assertStringContainsString('pointer: coarse', $mediaLine,
            'cycle-175 @media MUST include (pointer: coarse) so '
            . 'touch devices hit the rules regardless of width.');
    }
}
