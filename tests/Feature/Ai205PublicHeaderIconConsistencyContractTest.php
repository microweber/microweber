<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-164 / AI-205 (2026-05-10) — Public header icon consistency.
 *
 * UX-audit P3 finding: header icons (search/user/cart/menu) had three
 * mismatch dimensions:
 *   1. Source: search used `<i class="mdi mdi-magnify mdi-20px">`
 *      (MDI font icon at 20px) while user/cart used inline SVG at
 *      24px / 28px with `fill="currentColor"`. Search inherited the
 *      theme's link color (orange) instead of the white the SVGs
 *      rendered as.
 *   2. Source: inline-SVG height/width attributes drifted across
 *      buttons (user 24, cart 28).
 *   3. Compiled CSS: cycle-N main.scss `@media (max-width: 576px)`
 *      pinned search/user 20×20 and cart 22×22.
 *
 * Cycle-164 fixes:
 *   - Replaced the MDI font icon with an inline outlined "search" SVG
 *     matching the user/cart pattern (fill=currentColor, height/width
 *     28). Bumped user from 24→28 to match cart.
 *   - Added `Templates/Bootstrap/resources/assets/css/public-touch.css`
 *     unification rule lifting the 44×44 tap floor onto the parent
 *     `<a class="nav-link">` and pinning the inner SVG to 28×28. This
 *     overrides BOTH the cycle-N main.scss size pin AND the
 *     `.main a > svg:only-child` 44×44 floor that was inadvertently
 *     stretching the search/user SVGs to 44×44 (cart escaped because
 *     its sibling badge made it not-:only-child — that's why
 *     pre-cycle-164 cart was 22 while search/user were 44).
 *   - Mirror-copied the source public-touch.css to the served
 *     `public/templates/bootstrap/css/public-touch.css` since they
 *     are separate files.
 */
class Ai205PublicHeaderIconConsistencyContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_ai_205_anchor(): void
    {
        $skin = $this->read('Templates/Bootstrap/resources/views/modules/layouts/templates/menus/skin-1.blade.php');
        $touch = $this->read('Templates/Bootstrap/resources/assets/css/public-touch.css');

        $this->assertStringContainsString('AI-205', $skin,
            'menus/skin-1.blade.php MUST carry the AI-205 anchor inline.');
        $this->assertStringContainsString('AI-205', $touch,
            'public-touch.css MUST carry the AI-205 anchor inline.');
        $this->assertStringContainsString('cycle-164', $touch,
            'public-touch.css MUST carry the cycle-164 anchor inline.');
    }

    #[Test]
    public function search_icon_replaced_with_inline_svg(): void
    {
        $skin = $this->read('Templates/Bootstrap/resources/views/modules/layouts/templates/menus/skin-1.blade.php');

        // The .btn-search nav-link MUST contain an inline <svg> with
        // fill=currentColor + width/height=28 (matching user/cart).
        $this->assertMatchesRegularExpression(
            '/<li class="nav-item dropdown btn-search">[\s\S]{0,1500}<svg fill="currentColor"[^>]*height="28"[^>]*width="28"/m',
            $skin,
            '.btn-search MUST embed an inline <svg> with '
            . 'fill="currentColor" + width/height=28 (replaces the '
            . 'cycle-N <i class="mdi mdi-magnify"> font icon that '
            . 'inherited orange link color + was sized 20px).'
        );
        // The MDI icon MUST be gone (or only present in comments)
        $strippedComments = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $skin);
        $this->assertDoesNotMatchRegularExpression(
            '/<i class="mdi mdi-magnify[^"]*"\s*>/m',
            $strippedComments,
            'menus/skin-1.blade.php MUST NOT carry the cycle-N '
            . '`<i class="mdi mdi-magnify">` icon outside of comments — '
            . 'replaced by inline SVG in cycle-164.'
        );
    }

    #[Test]
    public function user_icon_uses_28x28(): void
    {
        $skin = $this->read('Templates/Bootstrap/resources/views/modules/layouts/templates/menus/skin-1.blade.php');
        // The .btn-member SVG MUST be sized 28×28 (was 24 in cycle-N).
        $this->assertMatchesRegularExpression(
            '/<li class="dropdown btn-member[^"]*">[\s\S]{0,1500}<svg fill="currentColor"[^>]*height="28"[^>]*width="28"/m',
            $skin,
            '.btn-member SVG MUST be sized 28×28 to match cart + new '
            . 'search SVG (was 24×24 in cycle-N).'
        );
    }

    #[Test]
    public function public_touch_css_unifies_header_icons_to_28(): void
    {
        $touch = $this->read('Templates/Bootstrap/resources/assets/css/public-touch.css');

        // The @media must include both narrow-viewport AND coarse-pointer
        // so the rule applies on real touch devices regardless of width.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*768px\)\s*,\s*\(pointer:\s*coarse\)/',
            $touch,
            'public-touch.css MUST gate the AI-205 unification on '
            . '`(max-width: 768px), (pointer: coarse)` so desktop '
            . 'density is preserved.'
        );

        // The SVG must be pinned to 28×28 with !important
        $this->assertMatchesRegularExpression(
            '/\.btn-search \.nav-link svg[\s\S]{0,500}width:\s*28px\s*!important/m',
            $touch,
            'public-touch.css MUST pin the search SVG to 28×28 !important '
            . 'so the cycle-N 20px main.scss rule + `.main a > svg:only-'
            . 'child` 44×44 floor are both beaten.'
        );
        $this->assertMatchesRegularExpression(
            '/\.btn-shopping-cart \.nav-link svg[\s\S]{0,500}width:\s*28px\s*!important/m',
            $touch,
            'public-touch.css MUST pin the cart SVG to 28×28 !important.'
        );
    }

    #[Test]
    public function public_touch_css_lifts_44_floor_to_parent_anchor(): void
    {
        $touch = $this->read('Templates/Bootstrap/resources/assets/css/public-touch.css');

        // Tap-area floor MUST be applied to the parent `<a class="nav-
        // link">` (not the inner SVG) so the SVG can render at 28×28
        // visual while the tappable area stays 44×44.
        $this->assertMatchesRegularExpression(
            '/\.btn-search \.nav-link\s*,[\s\S]{0,400}\.btn-member \.nav-link\s*,[\s\S]{0,400}\.btn-shopping-cart \.nav-link\s*\{[\s\S]{0,400}min-height:\s*44px/m',
            $touch,
            'public-touch.css MUST lift the 44×44 floor onto the parent '
            . '`<a class="nav-link">` so the tappable area stays 44×44 '
            . 'while the inner SVG renders at 28×28 visual size.'
        );
    }

    #[Test]
    public function served_public_touch_css_mirrors_source(): void
    {
        $source = base_path('Templates/Bootstrap/resources/assets/css/public-touch.css');
        $served = base_path('public/templates/bootstrap/css/public-touch.css');

        if (!file_exists($source) || !file_exists($served)) {
            $this->markTestSkipped('source or served public-touch.css missing.');
        }

        // The served public-touch.css and the source MUST have identical
        // contents — they are separate files, not symlinks. cycle-164
        // mirror-copied the source to the served path; future edits
        // must do the same or the browser-serve drifts from source.
        $this->assertSame(
            file_get_contents($source),
            file_get_contents($served),
            'public/templates/bootstrap/css/public-touch.css MUST be '
            . 'identical to Templates/Bootstrap/resources/assets/css/'
            . 'public-touch.css (separate files; cycle-164 mirror-'
            . 'copied them in sync).'
        );
    }
}
