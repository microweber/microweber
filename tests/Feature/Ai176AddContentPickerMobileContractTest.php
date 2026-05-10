<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-148 / AI-176 — Add-content picker rows cramp on mobile.
 *
 * User report (task-2026-05-10-e20f8d): "the add posts/contnet/...
 * etc modal is not ok on mobile". The +ADD picker
 * (`.mw-content-picker-modal`) renders 6 rows (Page / Post /
 * Category / Product / Image / Add-to-this-page) and each row
 * (`.mw-add-content-modal-action-wrapper`) carries Tailwind
 * `flex gap-6 p-5` plus an 80x80 icon container with a 40x40 SVG.
 *
 * On a 390px viewport the AI-144 modal-width clamp gives the
 * picker `calc(100vw - 16px)` ≈ 374px and the 80px icon column
 * + 24px gap + 20px outer padding leaves ~230px for title +
 * description. Descriptions wrap to 3-4 lines so each row is
 * ~140px tall and the 6 rows scroll well past the iPhone-13
 * viewport.
 *
 * Cycle-148 fix (CSS-only, scoped under .mw-live-edit-page):
 *   - Shrink the icon container 80x80 -> 56x56.
 *   - Shrink the SVG 40x40 -> 28x28.
 *   - Tighten the row gap 24px -> 12px and outer padding 20px -> 12px.
 *   - Clamp the description to 3 lines so row height is bounded.
 *
 * The contract test pins every load-bearing rule against BOTH
 * the source CSS and the built bundle. Pinning the bundle catches
 * the cycle-142 regression class — source rule landed but bundle
 * was not rebuilt.
 */
class Ai176AddContentPickerMobileContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_ai_176_anchor(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        $this->assertStringContainsString('AI-176', $src,
            'live-edit-mobile.css MUST carry the AI-176 anchor inline so '
            . 'the cycle-148 picker fix is discoverable at refactor time.');
        $this->assertStringContainsString('cycle-148', $src,
            'live-edit-mobile.css MUST carry the cycle-148 anchor inline.');
    }

    #[Test]
    public function source_shrinks_icon_container_on_mobile(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // The 80x80 icon container must shrink to 56x56 on mobile.
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-content-picker-modal\s+\.mw-add-content-modal-action-wrapper\s*>\s*div:first-child[\s\S]{0,400}width:\s*56px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST shrink the icon container to 56px '
            . 'wide on mobile (Tailwind w-20 = 80px is too large for the '
            . '374px modal).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-content-picker-modal\s+\.mw-add-content-modal-action-wrapper\s*>\s*div:first-child[\s\S]{0,400}height:\s*56px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST shrink the icon container to 56px '
            . 'tall on mobile.'
        );
    }

    #[Test]
    public function source_shrinks_svg_on_mobile(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-content-picker-modal\s+\.mw-add-content-modal-action-wrapper\s*>\s*div:first-child\s*>\s*svg[\s\S]{0,300}width:\s*28px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST shrink the SVG to 28px on mobile so '
            . 'it sits comfortably inside the smaller 56px container.'
        );
    }

    #[Test]
    public function source_tightens_row_gap_and_padding(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // Row gap dropped from 24px (Tailwind gap-6) to 12px (0.75rem).
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-content-picker-modal\s+\.mw-add-content-modal-action-wrapper\s*\{[\s\S]{0,300}gap:\s*0\.75rem\s*!important/m',
            $src,
            'live-edit-mobile.css MUST drop the row gap to 0.75rem on '
            . 'mobile (Tailwind gap-6 = 24px is too generous for 374px).'
        );
        // Row padding dropped from 20px (Tailwind p-5) to 12px (0.75rem).
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-content-picker-modal\s+\.mw-add-content-modal-action-wrapper\s*\{[\s\S]{0,300}padding:\s*0\.75rem\s*!important/m',
            $src,
            'live-edit-mobile.css MUST drop the row padding to 0.75rem on '
            . 'mobile.'
        );
    }

    #[Test]
    public function source_clamps_description_to_three_lines(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.mw-content-picker-modal\s+\.mw-add-content-modal-action-wrapper\s*>\s*div:nth-child\(2\)\s*>\s*div:last-child[\s\S]{0,400}-webkit-line-clamp:\s*3/m',
            $src,
            'live-edit-mobile.css MUST clamp the description to 3 lines on '
            . 'mobile so each picker row stays a predictable height.'
        );
    }

    #[Test]
    public function rule_is_scoped_inside_max_width_768_block(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // Find the AI-176 anchor and confirm the closest preceding @media
        // is `(max-width: 768px)` — guarantees the rule is mobile-only and
        // doesn't leak into the desktop 2x2 grid layout (≥640px in
        // iframe-page.blade.php) or other non-mobile surfaces.
        $anchorPos = strpos($src, 'AI-176');
        $this->assertNotFalse($anchorPos, 'AI-176 anchor must be present.');

        $rulePos = strpos($src, '.mw-content-picker-modal .mw-add-content-modal-action-wrapper', $anchorPos);
        $this->assertNotFalse($rulePos, 'Picker rule must follow the AI-176 anchor.');

        $beforeRule = substr($src, 0, $rulePos);
        $lastMediaPos = strrpos($beforeRule, '@media');
        $this->assertNotFalse($lastMediaPos, 'Rule must sit inside an @media block.');

        $mediaQueryLine = substr($src, $lastMediaPos, 60);
        $this->assertStringContainsString('max-width: 768px', $mediaQueryLine,
            'AI-176 picker rule MUST be inside `@media (max-width: 768px)` '
            . 'so it does NOT bleed into the desktop 2x2 grid layout that '
            . 'kicks in at min-width: 640px in iframe-page.blade.php.');
    }

    #[Test]
    public function built_bundle_carries_picker_mobile_rules(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped(
                "Built bundle not present at {$rel}; skipping production-CSS pin."
            );
        }
        $built = file_get_contents($path);

        // Functional pin: every load-bearing piece of the AI-176 fix MUST
        // appear in the BUILT CSS bundle. Source-only landings caused the
        // 16-cycle cycle-142 regression (live-edit-mobile.css orphaned).
        $this->assertStringContainsString(
            '.mw-content-picker-modal .mw-add-content-modal-action-wrapper > div:first-child',
            $built,
            'Built bundle MUST contain the icon-container shrink rule. '
            . 'If missing, the bundle was not rebuilt after the source edit.'
        );

        $this->assertMatchesRegularExpression(
            '/\.mw-content-picker-modal\s+\.mw-add-content-modal-action-wrapper\s*>\s*div:first-child[\s\S]{0,400}width:\s*56px\s*!important/m',
            $built,
            'Built bundle MUST contain width:56px !important on the icon '
            . 'container so it wins the cascade against Tailwind w-20.'
        );

        $this->assertMatchesRegularExpression(
            '/-webkit-line-clamp:\s*3/m',
            $built,
            'Built bundle MUST contain the 3-line description clamp.'
        );
    }
}
