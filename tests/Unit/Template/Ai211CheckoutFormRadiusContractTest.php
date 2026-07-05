<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-211 checkout form style unification contract test
 * (task-2026-05-13-8b4721).
 *
 * tester observed Filament checkout inputs rendering at 7px (the
 * Microweber admin brand `!rounded-[7px]` global rule in
 * general-styles.css) while the Place Order button rendered at 4px
 * (Filament `.fi-btn` default) and Bootstrap public inputs use 4px
 * (`--radius-sm`). The mismatch was both internal to checkout (7px
 * inputs + 4px button) AND cross-surface (Filament checkout vs
 * Bootstrap public).
 *
 * Fix: a checkout-scoped override that pulls every input wrapper +
 * input + select + textarea + button down to `var(--radius-sm, 4px)`
 * !important, leaving the admin panel's 7px brand choice untouched.
 *
 * Acceptance:
 *   - AC #1 input border radius unified — every checkout input
 *     wrapper + element + button collapses to a single 4px value
 *   - AC #2 checkout consistent with site form theme — `--radius-sm`
 *     is the Bootstrap public form radius
 *   - AC #3 CTA colors unified within checkout — already covered by
 *     AI-209 (regression-pinned in Ai209PrimaryColorUnificationContractTest)
 *
 * Test pins:
 *   - The CSS rule exists, scoped to `body.fi-panel-checkout`
 *   - All 7 expected selectors (.fi-input-wrp + .fi-fo-select-wrp +
 *     .fi-fo-textarea-wrp + .fi-input + .fi-fo-select + .fi-fo-textarea +
 *     .fi-btn) appear in the selector list (Filament v5 renamed the
 *     form field classes fi-select/fi-textarea → fi-fo-select/fi-fo-textarea)
 *   - The rule resolves `border-radius` to `var(--radius-sm, 4px)`
 *     with `!important`
 *   - The admin panel's `!rounded-[7px]` global rule is NOT regressed
 *   - The built theme bundle carries the checkout override
 */
class Ai211CheckoutFormRadiusContractTest extends TestCase
{
    private const GENERAL_STYLES_CSS = __DIR__ . '/../../../packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css';
    private const BUILT_BUNDLE       = __DIR__ . '/../../../public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    #[Test]
    public function checkout_override_collapses_radius_to_radius_sm_with_important(): void
    {
        $css = $this->readFile(self::GENERAL_STYLES_CSS);

        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-checkout\s+\.fi-input-wrp[\s\S]*?body\.fi-panel-checkout\s+\.fi-btn\s*\{[^}]*border-radius:\s*var\(--radius-sm,\s*4px\)\s*!important/s',
            $css,
            'AI-211 must declare a body.fi-panel-checkout-scoped rule covering .fi-input-wrp through .fi-btn that sets border-radius: var(--radius-sm, 4px) !important.'
        );
    }

    public static function checkoutScopedSelectorsProvider(): array
    {
        // Filament v5 renamed the form field classes: fi-select→fi-fo-select,
        // fi-textarea→fi-fo-textarea (+ their -wrp wrappers). fi-input(-wrp)
        // and fi-btn are unchanged.
        return [
            'fi-input-wrp'       => ['body\.fi-panel-checkout\s+\.fi-input-wrp'],
            'fi-fo-select-wrp'   => ['body\.fi-panel-checkout\s+\.fi-fo-select-wrp'],
            'fi-fo-textarea-wrp' => ['body\.fi-panel-checkout\s+\.fi-fo-textarea-wrp'],
            'fi-input'           => ['body\.fi-panel-checkout\s+\.fi-input\b'],
            'fi-fo-select'       => ['body\.fi-panel-checkout\s+\.fi-fo-select\b'],
            'fi-fo-textarea'     => ['body\.fi-panel-checkout\s+\.fi-fo-textarea\b'],
            'fi-btn'             => ['body\.fi-panel-checkout\s+\.fi-btn\b'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('checkoutScopedSelectorsProvider')]
    #[Test]
    public function each_checkout_input_selector_is_covered(string $selectorPattern): void
    {
        $css = $this->readFile(self::GENERAL_STYLES_CSS);

        $this->assertMatchesRegularExpression(
            '/' . $selectorPattern . '/',
            $css,
            "AI-211 checkout-scoped override must include the selector matching: {$selectorPattern}"
        );
    }

    #[Test]
    public function admin_brand_rounded_7px_rule_is_not_regressed(): void
    {
        $css = $this->readFile(self::GENERAL_STYLES_CSS);

        // The Microweber admin brand chrome uses `!rounded-[7px]` on a
        // large selector list above the AI-211 override. AI-211 must
        // NOT regress that — admin panel keeps 7px, only the checkout
        // panel collapses to 4px.
        $this->assertMatchesRegularExpression(
            '/@apply\s*!rounded-\[7px\]\s*;/',
            $css,
            'AI-211 must NOT remove the global `@apply !rounded-[7px]` rule — the admin panel brand still depends on it. The AI-211 override is a checkout-scoped reduction, not a global replacement.'
        );
    }

    #[Test]
    public function radius_sm_token_resolves_to_4px_in_design_system(): void
    {
        $designSystem = $this->readFile(__DIR__ . '/../../../packages/frontend-assets/resources/assets/css/microweber/css/design-system.css');

        $this->assertMatchesRegularExpression(
            '/--radius-sm:\s*4px\s*;/',
            $designSystem,
            'AI-211 depends on --radius-sm = 4px in design-system.css. If this token drifts the checkout fallback (4px) still wins, but the var() resolution would no longer match site-wide form radius.'
        );
    }

    #[Test]
    public function built_theme_bundle_carries_the_checkout_override(): void
    {
        if (!file_exists(self::BUILT_BUNDLE)) {
            $this->markTestSkipped('Built filament-theme bundle missing — run `npm run build` in packages/microweber-filament-theme.');
        }

        $built = $this->readFile(self::BUILT_BUNDLE);

        $this->assertStringContainsString(
            '.fi-panel-checkout .fi-input-wrp',
            $built,
            'Built bundle must carry the body.fi-panel-checkout .fi-input-wrp override selector — verify with `npm run build`.'
        );

        $this->assertStringContainsString(
            'var(--radius-sm',
            $built,
            'Built bundle must carry the var(--radius-sm, 4px) value reference so the checkout panel stays anchored on the design-system token.'
        );
    }

    private function readFile(string $path): string
    {
        $real = realpath($path);
        $this->assertNotFalse($real, "File not found: {$path}");

        $contents = file_get_contents($real);
        $this->assertNotFalse($contents, "Could not read: {$path}");
        $this->assertNotEmpty($contents, "File is empty: {$path}");

        return $contents;
    }
}
