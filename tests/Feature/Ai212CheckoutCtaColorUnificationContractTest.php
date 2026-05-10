<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-166 / AI-212 (2026-05-10) — Primary CTA color unification.
 *
 * agent-test design audit found 4 different primary-button colors
 * across 4 surfaces:
 *   - /shop "All Categories": #0d6efd Bootstrap blue (radius 0)
 *   - /checkout "Next": #4299e1 Tailwind cyan (radius 4)
 *   - /checkout "Place Order": #2fb344 green (radius 4)
 *   - Admin "Save": #7c3aed purple (radius 4)
 *
 * Cycle-166 scope: align the two CHECKOUT buttons to Bootstrap's
 * canonical `--bs-primary: #0d6efd` so the public-facing flow has
 * one consistent color. Admin Save stays purple — Filament admin is
 * a different surface/persona; the PM brief listed it but didn't
 * ask for cross-application unification.
 *
 * Scoped to `body.fi-panel-checkout` so other Filament admin panels
 * keep their existing color tokens. Both `.fi-color-primary` (Next)
 * and `.fi-color-success` (Place Order) bumped to the same blue +
 * 4px radius.
 *
 * Specificity: bumped from `(0,4,1)` → `(0,5,2)` by prefixing with
 * `html body.fi-panel-checkout` so we beat both the cycle-N admin
 * theme rule `html.dark .fi-btn.fi-color-primary:not(...)` (0,4,1
 * with 2 :not pseudo-classes) AND the light-mode
 * `.fi-btn.fi-color-primary:not(.admin-toolbar-buttons)` (0,3,1).
 * Same-specificity ties + later-loaded Filament theme were beating
 * the (0,4,1) first-pass rule.
 */
class Ai212CheckoutCtaColorUnificationContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_ai_212_anchor(): void
    {
        $idx = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/index.blade.php');
        $this->assertStringContainsString('AI-212', $idx,
            'index.blade.php MUST carry the AI-212 anchor inline.');
        $this->assertMatchesRegularExpression('/[Cc]ycle-166/', $idx,
            'index.blade.php MUST carry the cycle-166 anchor inline.');
    }

    #[Test]
    public function checkout_primary_cta_uses_bootstrap_blue(): void
    {
        $idx = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/index.blade.php');

        // Both .fi-color-primary AND .fi-color-success buttons inside
        // the wizard footer MUST be aligned to #0d6efd.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-checkout\s+\.fi-sc-wizard-footer\s+\.fi-btn\.fi-color-primary[\s\S]{0,500}background-color:\s*#0d6efd\s*!important/m',
            $idx,
            'index.blade.php MUST pin background-color:#0d6efd !important '
            . 'on the checkout Next button (was #4299e1 cyan).'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-checkout\s+\.fi-sc-wizard-footer\s+\.fi-btn\.fi-color-success[\s\S]{0,500}background-color:\s*#0d6efd\s*!important/m',
            $idx,
            'index.blade.php MUST pin background-color:#0d6efd !important '
            . 'on the checkout Place Order button (was #2fb344 green).'
        );
    }

    #[Test]
    public function checkout_primary_cta_uses_4px_radius(): void
    {
        $idx = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/index.blade.php');

        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-checkout\s+\.fi-sc-wizard-footer\s+\.fi-btn\.fi-color-primary[\s\S]{0,500}border-radius:\s*4px\s*!important/m',
            $idx,
            'index.blade.php MUST pin border-radius:4px !important on '
            . 'the checkout primary button (PM brief: uniform border '
            . 'radius across surfaces).'
        );
    }

    #[Test]
    public function selector_specificity_beats_filament_dark_rule(): void
    {
        $idx = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/index.blade.php');

        // The cycle-N admin theme rule
        // `html.dark .fi-btn.fi-color-primary:not(...)` is (0,4,1) so
        // the cycle-166 selector MUST include `html.dark
        // body.fi-panel-checkout` to win.
        $this->assertMatchesRegularExpression(
            '/html\.dark\s+body\.fi-panel-checkout\s+\.fi-sc-wizard-footer\s+\.fi-btn\.fi-color-primary/m',
            $idx,
            'index.blade.php MUST include the html.dark + '
            . 'body.fi-panel-checkout selector chain so the rule beats '
            . 'the cycle-N Filament theme dark-mode override.'
        );
    }

    #[Test]
    public function admin_save_button_not_touched(): void
    {
        $idx = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/index.blade.php');

        // The cycle-166 scope decision: admin Save button (Filament
        // admin theme purple) is OUT OF SCOPE. The selectors MUST be
        // anchored to `body.fi-panel-checkout` only — touching .fi-btn
        // globally would bleed into admin.
        // Strip block comments before checking.
        $stripped = preg_replace('#/\*[\s\S]*?\*/#', '', $idx);
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.fi-btn\.fi-color-primary\s*\{/m',
            (string) $stripped,
            'index.blade.php MUST NOT carry an unscoped '
            . '`.fi-btn.fi-color-primary { ... }` rule — that would '
            . 'leak the AI-212 unification into the Filament admin '
            . 'panel where the existing color is intentional.'
        );
    }
}
