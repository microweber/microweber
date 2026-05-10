<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-171 / AI-229 + AI-230 (2026-05-10) — admin form tabs +
 * category tree radio touch-target floors batch.
 *
 *   AI-229 — `.fi-tabs-item` ([role="tab"]) measured 36×31 in
 *            agent-test's audit (Content/Product Details/Variants/
 *            Custom Fields/SEO/Advanced tabs on /admin/products/
 *            create). Filament's base padding gives ~31px height;
 *            below the 44 floor on the smaller axis. Widths vary
 *            by label length; only the height is the failing axis.
 *            Floor `min-height: 44px` and bump padding so longer
 *            translated labels still fit.
 *
 *   AI-230 — Native `<input type="radio">` (16×16) in the category
 *            tree at /admin/products/create. The radio sits inside
 *            `<tree-label class="form-check">`. Same pattern as
 *            AI-221 bulk-checkbox: floor the parent label wrapper
 *            to 44×44 so the tap area meets the floor on BOTH
 *            axes (height was 16, width was ~20 with no
 *            min-width). Native radio stays browser-rendered.
 *
 * Both rules scoped to `body.fi-panel-admin` so they don't bleed
 * to checkout panel or any other Filament panel. Same media query
 * as cycle-168/169/170.
 */
class Ai229Ai230AdminTabsAndCategoryRadiosContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_171_anchor(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        $this->assertMatchesRegularExpression('/[Cc]ycle-171/', $src,
            'mobile-touch.css MUST carry the cycle-171 anchor.');
        $this->assertStringContainsString('AI-229', $src,
            'mobile-touch.css MUST carry the AI-229 anchor.');
        $this->assertStringContainsString('AI-230', $src,
            'mobile-touch.css MUST carry the AI-230 anchor.');
    }

    #[Test]
    public function ai_229_admin_tabs_floored_to_44(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-tabs-item[\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST floor body.fi-panel-admin '
            . '.fi-tabs-item to min-height:44px !important so admin '
            . 'form tabs (Content, Product Details, etc.) meet the '
            . 'WCAG 2.5.5 / iOS HIG 44 floor (was 36×31).'
        );
        // Defensive sibling selector — bites if Filament restructures
        // .fi-tabs-item but keeps role="tab" on the buttons.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-tabs\s+button\[role="tab"\][\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST also floor body.fi-panel-admin '
            . '.fi-tabs button[role="tab"] (defensive duplicate).'
        );
    }

    #[Test]
    public function ai_230_category_tree_radio_label_floored(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+tree-label\.form-check[\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST floor body.fi-panel-admin '
            . 'tree-label.form-check to min-height:44px !important so '
            . 'category-tree radio rows meet the floor (was 16×16).'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+tree-label\.form-check[\s\S]{0,400}min-width:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST also floor min-width:44px !important '
            . 'on tree-label.form-check so the wrapper meets the floor '
            . 'on both axes.'
        );
        // :has() selector for any form-check wrapping a radio
        // (defensive — covers other category-tree-style components).
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.form-check:has\(>\s*input\[type="radio"\]\)[\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST floor body.fi-panel-admin '
            . '.form-check:has(> input[type="radio"]) so any radio '
            . 'wrapper using the form-check pattern meets the floor.'
        );
    }

    #[Test]
    public function cycle_171_inside_touch_media_query(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');

        $anchorPos = strpos($src, 'cycle-171');
        $this->assertNotFalse($anchorPos, 'cycle-171 anchor must be present.');
        $mediaPos = strpos($src, '@media', $anchorPos);
        $this->assertNotFalse($mediaPos, 'cycle-171 rules MUST sit inside an @media.');
        $mediaLine = substr($src, $mediaPos, 100);
        $this->assertMatchesRegularExpression(
            '/max-width:\s*1023\.98px/',
            $mediaLine,
            'cycle-171 @media MUST include max-width: 1023.98px.'
        );
        $this->assertStringContainsString('pointer: coarse', $mediaLine,
            'cycle-171 @media MUST include (pointer: coarse).');
    }

    #[Test]
    public function built_bundle_carries_admin_tab_and_radio_floors(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built filament-theme bundle missing.");
        }
        $built = file_get_contents($path);

        $this->assertStringContainsString(
            '.fi-panel-admin .fi-tabs-item',
            $built,
            'Built bundle MUST contain the AI-229 admin tabs floor rule.'
        );
        $this->assertStringContainsString(
            '.fi-panel-admin tree-label.form-check',
            $built,
            'Built bundle MUST contain the AI-230 category-tree radio '
            . 'label floor rule.'
        );
    }
}
