<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-158 / AI-184 — Big2 ecommerce product titles + pagination
 * below WCAG 2.5.5 / iOS HIG 44×44 touch-target floor.
 *
 * UX-audit P2 finding (agent-test Big2 demo scan at 390×844):
 *   - Product title links inside `<h3 class="mw-products-title">`
 *     measured 132–248 wide × 34 tall — height below floor.
 *   - Pagination links `.page-link` (Bootstrap pagination) measured
 *     33–35 wide × 45 tall — width below floor.
 *
 * Cycle-158 fix (CSS-only) — appended to the existing `@media (max-
 * width: 768px)` AI-166/183 mobile touch-target block in
 * `packages/frontend-assets/.../microweber/css/default.css` (the
 * cross-template public CSS loaded on every public page via
 * TemplateManager / FrontendController).
 *
 * Targets:
 *   - `.mw-products-title a` — Big2/products-module namespaced h3
 *     wrapping a bare `<a>` (the link itself has no class). Defensive
 *     duplicates `.module-shop-products .post-holder h3 a` and
 *     `.module-shop-products h3.mw-products-title a` cover refactors.
 *   - `.page-link` (Bootstrap pagination) — also covered as
 *     `.pagination .page-item > .page-link` for higher specificity.
 *
 * Use min-width/min-height (not width/height) so titles still wrap
 * naturally and pagination chips still grow with content; we just
 * guarantee a minimum tappable area.
 */
class Ai184EcommercePaginationTouchTargetContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_ai_184_anchor(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/default.css');
        $this->assertStringContainsString('AI-184', $src,
            'default.css MUST carry the AI-184 anchor inline so the '
            . 'cycle-158 ecommerce/pagination floor is discoverable at '
            . 'refactor time.');
        $this->assertStringContainsString('cycle-158', $src,
            'default.css MUST carry the cycle-158 anchor inline.');
    }

    #[Test]
    public function source_pins_product_title_44_min(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/default.css');

        // The product-title link must hit min-width/min-height: 44px.
        $this->assertMatchesRegularExpression(
            '/\.mw-products-title\s+a[\s\S]{0,800}min-height:\s*44px\s*!important/m',
            $src,
            'default.css MUST pin min-height:44px !important on '
            . '.mw-products-title a so the cycle-158 floor wins (the '
            . 'agent-test reported 34px height was the failing dimension).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-products-title\s+a[\s\S]{0,800}min-width:\s*44px\s*!important/m',
            $src,
            'default.css MUST pin min-width:44px !important on '
            . '.mw-products-title a too.'
        );
    }

    #[Test]
    public function source_pins_pagination_44_min(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/default.css');

        // The Bootstrap pagination .page-link must hit min-width:44px
        // since the agent-test report flagged 33-35px width.
        $this->assertMatchesRegularExpression(
            '/\.page-link[\s\S]{0,800}min-width:\s*44px\s*!important/m',
            $src,
            'default.css MUST pin min-width:44px !important on '
            . '.page-link so the narrow numeric pagination chips '
            . '("1", "2") meet the touch-target floor.'
        );
        $this->assertMatchesRegularExpression(
            '/\.page-link[\s\S]{0,800}min-height:\s*44px\s*!important/m',
            $src,
            'default.css MUST pin min-height:44px !important on '
            . '.page-link.'
        );
    }

    #[Test]
    public function pagination_rule_includes_high_specificity_fallback(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/default.css');
        // .pagination .page-item > .page-link is the higher-specificity
        // form Bootstrap ships — including it ensures we win the
        // cascade if a future Bootstrap upgrade or template override
        // declares min-width on the same element with higher specificity.
        $this->assertMatchesRegularExpression(
            '/\.pagination\s+\.page-item\s*>\s*\.page-link/m',
            $src,
            'default.css MUST include the high-specificity .pagination '
            . '.page-item > .page-link selector as defensive duplicate.'
        );
    }

    #[Test]
    public function product_title_rule_includes_namespace_fallbacks(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/default.css');
        // .module-shop-products .post-holder h3 a and
        // .module-shop-products h3.mw-products-title a cover refactors.
        $this->assertMatchesRegularExpression(
            '/\.module-shop-products\s+\.post-holder\s+h3\s+a/m',
            $src,
            'default.css MUST include the .module-shop-products '
            . '.post-holder h3 a selector as defensive duplicate.'
        );
        $this->assertMatchesRegularExpression(
            '/\.module-shop-products\s+h3\.mw-products-title\s+a/m',
            $src,
            'default.css MUST include the .module-shop-products '
            . 'h3.mw-products-title a selector as another defensive '
            . 'duplicate.'
        );
    }

    #[Test]
    public function rule_is_inside_max_width_768_block(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/default.css');

        // Find the AI-184 anchor and check the closest preceding @media
        // block declares `(max-width: 768px)` so desktop density is
        // preserved.
        $anchorPos = strpos($src, 'AI-184');
        $this->assertNotFalse($anchorPos, 'AI-184 anchor must be present.');

        $rulePos = strpos($src, '.mw-products-title a', $anchorPos);
        $this->assertNotFalse($rulePos, 'AI-184 rule must follow the anchor.');

        $beforeRule = substr($src, 0, $rulePos);
        $lastMediaPos = strrpos($beforeRule, '@media');
        $this->assertNotFalse($lastMediaPos, 'AI-184 rule must sit inside an @media block.');

        $mediaQueryLine = substr($src, $lastMediaPos, 60);
        $this->assertStringContainsString('max-width: 768px', $mediaQueryLine,
            'AI-184 rule MUST be inside `@media (max-width: 768px)` so '
            . 'desktop pagination chips + product titles keep their '
            . 'natural density.');
    }

    #[Test]
    public function built_bundle_carries_ecommerce_pagination_floors(): void
    {
        $rel = 'public/vendor/microweber-packages/frontend-assets/build/default.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built default.css missing; skipping production-CSS pin.");
        }
        $built = file_get_contents($path);

        // Functional pin per cycle-142 lesson.
        $this->assertStringContainsString('.mw-products-title a', $built,
            'Built default.css MUST contain .mw-products-title a. If '
            . 'missing, the bundle was not rebuilt after the source edit.');
        $this->assertStringContainsString('.page-link', $built,
            'Built default.css MUST contain .page-link.');
        $this->assertMatchesRegularExpression(
            '/\.mw-products-title\s+a[\s\S]{0,800}min-height:\s*44px\s*!important/m',
            $built,
            'Built default.css MUST contain min-height:44px !important '
            . 'on .mw-products-title a.'
        );
        $this->assertMatchesRegularExpression(
            '/\.page-link[\s\S]{0,800}min-width:\s*44px\s*!important/m',
            $built,
            'Built default.css MUST contain min-width:44px !important '
            . 'on .page-link.'
        );
    }
}
