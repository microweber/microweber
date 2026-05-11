<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-178 / AI-276 + AI-277 + AI-278 (2026-05-11) — Phase 1
 * design-system implementation.
 *
 * PM (acting via ux-designer subagent) filed three P0 Phase 1
 * tickets after the comprehensive design audit + DESIGN_SYSTEM.md
 * token proposal:
 *
 *   AI-276 — Unify primary button colors / heights / radii via
 *            CSS tokens (var(--color-primary) = #0d6efd, height
 *            44px, radius 4px, hover #0b5ed7).
 *
 *   AI-277 — Footer color token separation. agent-test's audit
 *            found footer text bleeding to the product-title
 *            orange (#d97706) on some pages. The fix splits the
 *            two into separate tokens:
 *              --color-footer-text  = #F8F9FA  (off-white)
 *              --color-product-title = #d97706 (amber)
 *            so footer styling can apply the footer token
 *            without the product-title rule cascading in.
 *
 *   AI-278 — Checkout alignment. The `body.fi-panel-checkout`
 *            customer-facing surface inherits Filament's full
 *            dark admin chrome (dark surface, 7px input radius,
 *            gray-900 text). On mobile this looks like the
 *            admin panel, not the rest of the public site.
 *            Override scoped to `body.fi-panel-checkout` on
 *            mobile + touch viewports — force light-surface
 *            tokens, 4px input radius, 44px input height.
 *
 * Implementation:
 *   - Canonical token source at
 *     packages/frontend-assets/resources/assets/css/microweber/css/design-system.css
 *     (PM-specified path; added as vite input so it builds to
 *     public/vendor/microweber-packages/frontend-assets/build/
 *     design-system.css).
 *   - Same :root token block inlined at the top of
 *     public-touch.css (covers public Bootstrap pages + iframe)
 *     and mobile-touch.css (covers Filament admin) so var()s
 *     resolve regardless of load order. This contract test pins
 *     the duplication to stay in sync.
 */
class Ai276Ai277Ai278DesignSystemContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function design_system_css_file_exists_at_pm_spec_path(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/design-system.css');
        $this->assertMatchesRegularExpression('/[Cc]ycle-178/', $src,
            'design-system.css MUST carry the cycle-178 anchor.');
        $this->assertStringContainsString('Microweber design', $src,
            'design-system.css MUST identify itself as the '
            . 'Microweber design-system token file.');
    }

    #[Test]
    public function design_system_carries_all_canonical_tokens(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/design-system.css');
        $tokens = [
            '--color-primary: #0d6efd',
            '--color-primary-hover: #0b5ed7',
            '--color-success: #22c55e',
            '--color-error: #ef4444',
            '--color-warning: #f59e0b',
            '--color-surface: #ffffff',
            '--color-text-primary: #111827',
            '--color-border: #d1d5db',
            '--color-footer-text: #F8F9FA',
            '--color-product-title: #d97706',
            '--touch-target-min: 44px',
            '--space-11: 44px',
            '--radius-sm: 4px',
            '--radius-md: 8px',
            '--shadow-1:',
            '--duration-fast: 150ms',
            '--ease-default: cubic-bezier',
        ];
        foreach ($tokens as $token) {
            $this->assertStringContainsString($token, $src,
                "design-system.css MUST define the canonical token: "
                . "`{$token}`.");
        }
    }

    #[Test]
    public function ai_276_design_system_primary_button_tokens(): void
    {
        $src = $this->read('public/templates/bootstrap/css/public-touch.css');
        $this->assertStringContainsString('AI-276', $src,
            'public-touch.css MUST carry the AI-276 anchor.');
        // Primary button rule uses var(--color-primary).
        $this->assertMatchesRegularExpression(
            '/\.btn-primary[\s\S]{0,800}background-color:\s*var\(--color-primary\)/m',
            $src,
            'public-touch.css MUST set .btn-primary background-color '
            . 'to var(--color-primary) — single source of truth for '
            . 'public primary button color (AI-276).'
        );
        // Hover uses var(--color-primary-hover).
        $this->assertMatchesRegularExpression(
            '/\.btn-primary:hover[\s\S]{0,400}background-color:\s*var\(--color-primary-hover\)/m',
            $src,
            'public-touch.css MUST set .btn-primary:hover '
            . 'background-color to var(--color-primary-hover).'
        );
        // 44px touch target + 4px radius via tokens.
        $this->assertMatchesRegularExpression(
            '/\.btn-primary[\s\S]{0,800}min-height:\s*var\(--touch-target-min\)/m',
            $src,
            'public-touch.css MUST set .btn-primary min-height to '
            . 'var(--touch-target-min) — token-driven WCAG floor.'
        );
        $this->assertMatchesRegularExpression(
            '/\.btn-primary[\s\S]{0,800}border-radius:\s*var\(--radius-sm\)/m',
            $src,
            'public-touch.css MUST set .btn-primary border-radius '
            . 'to var(--radius-sm) — token-driven 4px radius.'
        );
    }

    #[Test]
    public function ai_277_footer_color_token_separation(): void
    {
        $src = $this->read('public/templates/bootstrap/css/public-touch.css');
        $this->assertStringContainsString('AI-277', $src,
            'public-touch.css MUST carry the AI-277 anchor.');
        // .footer-background text color comes from --color-footer-text.
        $this->assertMatchesRegularExpression(
            '/\.footer-background[\s\S]{0,800}color:\s*var\(--color-footer-text\)/m',
            $src,
            'public-touch.css MUST set .footer-background color to '
            . 'var(--color-footer-text) so footer text does not bleed '
            . 'to the orange product-title color (was happening on '
            . 'some pages — AI-277 finding).'
        );
        // Token definitions must include both --color-footer-text
        // and --color-product-title as DISTINCT values.
        $this->assertMatchesRegularExpression(
            '/--color-footer-text:\s*#F8F9FA/i',
            $src,
            'public-touch.css :root MUST define --color-footer-text '
            . 'as #F8F9FA (off-white) — distinct from product title.'
        );
        $this->assertMatchesRegularExpression(
            '/--color-product-title:\s*#d97706/i',
            $src,
            'public-touch.css :root MUST define --color-product-title '
            . 'as #d97706 (amber) — distinct from footer text.'
        );
    }

    #[Test]
    public function ai_278_checkout_panel_alignment(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        $this->assertStringContainsString('AI-278', $src,
            'mobile-touch.css MUST carry the AI-278 anchor.');
        // body.fi-panel-checkout uses light surface tokens.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-checkout[\s\S]{0,800}background-color:\s*var\(--color-surface\)/m',
            $src,
            'mobile-touch.css MUST override body.fi-panel-checkout '
            . 'background-color to var(--color-surface) so checkout '
            . 'matches public-site surface tokens on mobile (was '
            . 'inheriting Filament dark surface).'
        );
        // Input radius aligned to 4px var(--radius-sm).
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-checkout\s+\.fi-input[\s\S]{0,400}border-radius:\s*var\(--radius-sm\)/m',
            $src,
            'mobile-touch.css MUST set checkout .fi-input '
            . 'border-radius to var(--radius-sm) (4px) matching '
            . 'public Bootstrap forms (was Filament default ~7px).'
        );
        // Input height floored to 44px via token.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-checkout\s+\.fi-input[\s\S]{0,400}min-height:\s*var\(--touch-target-min\)/m',
            $src,
            'mobile-touch.css MUST floor checkout .fi-input '
            . 'min-height to var(--touch-target-min) — WCAG 2.5.5.'
        );
    }

    #[Test]
    public function tokens_inlined_in_public_touch_match_canonical(): void
    {
        $public = $this->read('public/templates/bootstrap/css/public-touch.css');
        // Critical color values must be present at :root level in
        // the public-touch.css so var() resolves regardless of
        // design-system.css load order.
        $this->assertStringContainsString('--color-primary: #0d6efd', $public,
            'public-touch.css :root MUST define --color-primary in '
            . 'lockstep with the canonical design-system.css value.');
        $this->assertStringContainsString('--touch-target-min: 44px', $public,
            'public-touch.css :root MUST define --touch-target-min.');
        $this->assertStringContainsString('--radius-sm: 4px', $public,
            'public-touch.css :root MUST define --radius-sm.');
        $this->assertStringContainsString('--color-footer-text: #F8F9FA', $public,
            'public-touch.css :root MUST define --color-footer-text.');
    }

    #[Test]
    public function tokens_inlined_in_mobile_touch_match_canonical(): void
    {
        $admin = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        $this->assertStringContainsString('--color-primary: #0d6efd', $admin,
            'mobile-touch.css :root MUST define --color-primary '
            . 'in lockstep with canonical design-system.css.');
        $this->assertStringContainsString('--color-surface: #ffffff', $admin,
            'mobile-touch.css :root MUST define --color-surface — '
            . 'AI-278 checkout panel uses it to force light surface.');
        $this->assertStringContainsString('--radius-sm: 4px', $admin,
            'mobile-touch.css :root MUST define --radius-sm — '
            . 'AI-278 uses it to align checkout input radius.');
        $this->assertStringContainsString('--touch-target-min: 44px', $admin,
            'mobile-touch.css :root MUST define --touch-target-min.');
    }

    #[Test]
    public function design_system_dark_mode_overrides(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/css/microweber/css/design-system.css');
        // Cover Filament .fi.dark cascade.
        $this->assertMatchesRegularExpression(
            '/\.fi\.dark[\s\S]{0,500}--color-surface:\s*#111827/m',
            $src,
            'design-system.css MUST provide dark-mode override for '
            . '--color-surface scoped under .fi.dark (Filament admin '
            . 'dark mode cascade).'
        );
        // Cover Bootstrap [data-bs-theme="dark"] cascade.
        $this->assertStringContainsString('[data-bs-theme="dark"]', $src,
            'design-system.css MUST provide dark-mode override for '
            . '[data-bs-theme="dark"] (Bootstrap public-side dark).');
    }

    #[Test]
    public function built_bundles_carry_tokens(): void
    {
        $designSystemRel = 'public/vendor/microweber-packages/frontend-assets/build/design-system.css';
        $designSystemPath = base_path($designSystemRel);
        if (!file_exists($designSystemPath)) {
            $this->markTestSkipped("Built frontend-assets design-system.css missing.");
        }
        $built = file_get_contents($designSystemPath);

        // Functional pin per cycle-142 lesson — load-bearing
        // tokens MUST appear in compiled output.
        $this->assertStringContainsString('--color-primary', $built,
            'Built design-system.css MUST contain --color-primary.');
        $this->assertStringContainsString('#0d6efd', $built,
            'Built design-system.css MUST contain the canonical '
            . 'primary color value #0d6efd.');
        $this->assertStringContainsString('--touch-target-min', $built,
            'Built design-system.css MUST contain --touch-target-min.');
        $this->assertStringContainsString('--color-footer-text', $built,
            'Built design-system.css MUST contain --color-footer-text.');
        $this->assertStringContainsString('--color-product-title', $built,
            'Built design-system.css MUST contain --color-product-title.');

        // Filament theme bundle must also carry the AI-278 rule.
        $adminBundleRel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $adminBundlePath = base_path($adminBundleRel);
        if (!file_exists($adminBundlePath)) {
            $this->markTestSkipped("Built filament-theme bundle missing.");
        }
        $adminBuilt = file_get_contents($adminBundlePath);
        $this->assertStringContainsString('.fi-panel-checkout', $adminBuilt,
            'Built filament-theme.css MUST contain the AI-278 '
            . 'body.fi-panel-checkout rule.');
        $this->assertStringContainsString('var(--color-surface)', $adminBuilt,
            'Built filament-theme.css MUST contain var(--color-surface) '
            . 'reference — proves token-driven approach shipped.');
    }
}
