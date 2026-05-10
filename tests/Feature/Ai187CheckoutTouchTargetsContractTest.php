<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-161 / AI-187 — /checkout small touch targets at 390x844.
 *
 * UX-audit P2 follow-up findings (agent-test verification of AI-186
 * cycle-160 fix):
 *   - "Add more items" link (`.checkout-cart-add-more a`) measured
 *     98×16 — height below WCAG 2.5.5 / iOS HIG 44×44 floor.
 *   - Filament Wizard "Next" button (`.fi-sc-wizard-footer .fi-btn`)
 *     measured 83×42 — height below floor.
 *   - User-menu trigger (`.fi-user-menu-trigger`) measured 32×32 —
 *     well below floor.
 *
 * Cycle-161 fix (CSS-only, scoped to checkout panel):
 *   - `.checkout-cart-add-more a` floored to 44x44 in
 *     `cart-items.blade.php` `<style>` block (the rule is component-
 *     local so it can't leak elsewhere).
 *   - `.fi-panel-checkout .fi-sc-wizard-footer .fi-btn` floored to
 *     min-height/min-width: 44px !important inside `(max-width: 768px),
 *     (pointer: coarse)` in the wizard `index.blade.php`. The
 *     !important is defensive: Filament's bundled `.fi-btn { min-
 *     height: 36px }` rule loads AFTER our scoped style tag in source
 *     order, so without !important Filament wins on equal specificity.
 *   - `.fi-panel-checkout .fi-user-menu-trigger` floored to 44x44
 *     !important. Scoped to .fi-panel-checkout so other Filament
 *     panels (admin, etc.) keep their existing user-menu density.
 */
class Ai187CheckoutTouchTargetsContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_ai_187_cycle_161_anchor(): void
    {
        $idx = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/index.blade.php');
        $cart = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/cart-items.blade.php');

        $this->assertStringContainsString('Cycle-161', $idx,
            'index.blade.php MUST carry the Cycle-161 anchor inline.');
        $this->assertStringContainsString('Cycle-161', $cart,
            'cart-items.blade.php MUST carry the Cycle-161 anchor inline.');
    }

    #[Test]
    public function add_more_items_link_pinned_to_44(): void
    {
        $cart = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/cart-items.blade.php');

        // The .checkout-cart-add-more a rule MUST hit min-height:44px +
        // inline-flex centering so the link's tap area meets the floor.
        $this->assertMatchesRegularExpression(
            '/\.checkout-cart-add-more\s+a\s*\{[\s\S]{0,800}min-height:\s*44px/m',
            $cart,
            'cart-items.blade.php MUST pin min-height:44px on '
            . '.checkout-cart-add-more a so the link tap area meets '
            . 'the floor (was 98x16).'
        );
        $this->assertMatchesRegularExpression(
            '/\.checkout-cart-add-more\s+a\s*\{[\s\S]{0,800}display:\s*inline-flex/m',
            $cart,
            'cart-items.blade.php MUST set display:inline-flex on the '
            . 'link so the larger tap area centres the visible text.'
        );
    }

    #[Test]
    public function wizard_next_button_pinned_to_44_with_important(): void
    {
        $idx = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/index.blade.php');

        // Filament's bundled `.fi-btn { min-height: 36px }` loads after
        // this style tag in source order — !important is required to win.
        // Cycle-162 (AI-203 follow-up) extended the Next-button selector
        // into a comma-separated list of defensive duplicates; pin the
        // canonical selector and the !important rule body separately.
        $this->assertStringContainsString(
            '.fi-panel-checkout .fi-sc-wizard-footer .fi-btn',
            $idx,
            'index.blade.php MUST include the canonical '
            . '.fi-panel-checkout .fi-sc-wizard-footer .fi-btn selector.'
        );
        $this->assertMatchesRegularExpression(
            '/\.fi-panel-checkout\s+\.fi-sc-wizard-footer\s+\.fi-btn[\s\S]{0,2000}min-height:\s*44px\s*!important/m',
            $idx,
            'index.blade.php MUST pin min-height:44px !important on '
            . 'the .fi-panel-checkout .fi-sc-wizard-footer .fi-btn rule '
            . '(possibly grouped with defensive duplicates).'
        );
    }

    #[Test]
    public function user_menu_trigger_pinned_to_44_scoped_to_checkout(): void
    {
        $idx = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/index.blade.php');

        // Scoped to .fi-panel-checkout so other Filament panels (admin
        // etc.) keep their existing user-menu density. Cycle-162 (AI-203
        // follow-up) extended this into a comma-separated list of
        // defensive duplicates; pin the canonical selector + the rule
        // body separately.
        $this->assertStringContainsString(
            '.fi-panel-checkout .fi-user-menu-trigger',
            $idx,
            'index.blade.php MUST include the canonical '
            . '.fi-panel-checkout .fi-user-menu-trigger selector.'
        );
        $this->assertMatchesRegularExpression(
            '/\.fi-panel-checkout\s+\.fi-user-menu-trigger[\s\S]{0,2000}min-width:\s*44px\s*!important/m',
            $idx,
            'index.blade.php MUST pin min-width:44px !important on the '
            . '.fi-panel-checkout .fi-user-menu-trigger rule.'
        );
        $this->assertMatchesRegularExpression(
            '/\.fi-panel-checkout\s+\.fi-user-menu-trigger[\s\S]{0,2000}min-height:\s*44px\s*!important/m',
            $idx,
            'index.blade.php MUST pin min-height:44px !important on the '
            . '.fi-panel-checkout .fi-user-menu-trigger rule.'
        );
    }

    #[Test]
    public function cycle_162_defensive_duplicate_selectors_present(): void
    {
        $idx = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/index.blade.php');

        // Cycle-162 added defensive higher-specificity duplicates per
        // PM after agent-test reported the cycle-161 rule "regressing"
        // (which my browser couldn\'t reproduce — see AI-203 thread).
        // Multiple winning paths protect against any future Filament
        // base-CSS bump.
        $this->assertMatchesRegularExpression('/[Cc]ycle-162/', $idx,
            'index.blade.php MUST carry the cycle-162 anchor (any case) '
            . 'for the AI-203 defensive-duplicate hardening.');
        // Higher-spec Next button duplicate (adds the .fi-color-primary
        // class which Filament puts on the active wizard step's submit
        // button — bumps specificity from 0,3,1 to 0,4,1).
        $this->assertStringContainsString(
            '.fi-panel-checkout .fi-sc-wizard-footer .fi-btn.fi-color-primary',
            $idx,
            'index.blade.php MUST include the .fi-color-primary defensive '
            . 'duplicate so a higher-specificity selector also targets the '
            . 'active wizard Next button.'
        );
        // Body-level UserMenu duplicate
        $this->assertStringContainsString(
            'body.fi-panel-checkout button.fi-user-menu-trigger',
            $idx,
            'index.blade.php MUST include the body.fi-panel-checkout '
            . 'button.fi-user-menu-trigger defensive duplicate (specificity '
            . 'bumped via element-selector + body-class).'
        );
    }

    #[Test]
    public function wizard_rules_inside_mobile_or_touch_media(): void
    {
        $idx = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/index.blade.php');

        // Find the Cycle-161 anchor and check the next @media block
        // declares both `(max-width: 768px)` AND `(pointer: coarse)`.
        $anchorPos = strpos($idx, 'Cycle-161');
        $this->assertNotFalse($anchorPos, 'Cycle-161 anchor must be present.');

        $mediaPos = strpos($idx, '@media', $anchorPos);
        $this->assertNotFalse($mediaPos, 'Cycle-161 rules must sit inside an @media block.');

        $mediaQueryLine = substr($idx, $mediaPos, 80);
        $this->assertStringContainsString('max-width: 768px', $mediaQueryLine,
            'Cycle-161 @media MUST include `(max-width: 768px)` so '
            . 'desktop density is preserved.');
        $this->assertStringContainsString('pointer: coarse', $mediaQueryLine,
            'Cycle-161 @media MUST include `(pointer: coarse)` so the '
            . 'floor applies on touch devices regardless of width.');
    }
}
