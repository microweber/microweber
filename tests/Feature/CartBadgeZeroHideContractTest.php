<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-59 / TASK-022 / TICKET-CZ / AI-40 — cart-badge zero-hide
 * regression coverage.
 *
 * Pins the contract that the public-store cart badge is hidden when
 * the cart is empty (no "0" rendered to users), and that the JS
 * cartModify hook keeps the hidden state in sync after add-to-cart /
 * remove. Three layers verified:
 *
 *   1. Modules/Layouts/.../shopping_cart.blade.php
 *      (server-rendered initial state)
 *   2. Templates/Bootstrap/.../menus/skin-1.blade.php
 *      (Bootstrap template inline copy of the same markup)
 *   3. packages/frontend-assets/.../shop.js
 *      (cartModify JS hook)
 *
 * Style after the cycle-52..58 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class CartBadgeZeroHideContractTest extends TestCase
{
    private string $partialSrc;
    private string $bootstrapSrc;
    private string $shopJsSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->partialSrc   = file_get_contents(base_path(
            'Modules/Layouts/resources/views/partials/menu/parts/shopping_cart.blade.php'
        ));
        $this->bootstrapSrc = file_get_contents(base_path(
            'Templates/Bootstrap/resources/views/modules/layouts/templates/menus/skin-1.blade.php'
        ));
        $this->shopJsSrc    = file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/api-core/core/core/shop.js'
        ));
    }

    #[Test]
    public function layouts_partial_hides_badge_when_cart_is_empty(): void
    {
        // The blade must compute the cart quantity once, then attach the
        // HTML5 `hidden` attribute + aria-hidden when qty <= 0. The
        // span stays in the DOM so shop.js can keep updating its text.
        $this->assertStringContainsString(
            '$cart_qty',
            $this->partialSrc,
            'Layouts partial: must read the cart quantity into $cart_qty before rendering the badge'
        );
        $this->assertStringContainsString(
            '(int) cart_sum(false)',
            $this->partialSrc,
            'Layouts partial: must cast cart_sum(false) to int for the comparison'
        );
        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*\$cart_qty\s*<=\s*0\s*\)\s*hidden\s+aria-hidden="true"\s*@endif/',
            $this->partialSrc,
            'Layouts partial: must conditionally render `hidden aria-hidden="true"` when $cart_qty <= 0'
        );
        $this->assertStringContainsString(
            'data-cart-count="{{ $cart_qty }}"',
            $this->partialSrc,
            'Layouts partial: must expose data-cart-count for CSS / JS hooks'
        );
    }

    #[Test]
    public function bootstrap_template_inline_badge_hides_when_empty(): void
    {
        // The Bootstrap template carries an inline copy of the same
        // badge markup (it does not include the Layouts partial). Same
        // contract applies. function_exists guard is required because
        // the Bootstrap template can render outside a shop context.
        $this->assertStringContainsString(
            "function_exists('cart_sum')",
            $this->bootstrapSrc,
            'Bootstrap template: must guard cart_sum() with function_exists() to stay safe outside shop context'
        );
        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*\$cart_qty\s*<=\s*0\s*\)\s*hidden\s+aria-hidden="true"\s*@endif/',
            $this->bootstrapSrc,
            'Bootstrap template: must conditionally render `hidden aria-hidden="true"` when $cart_qty <= 0'
        );
        $this->assertStringContainsString(
            'data-cart-count="{{ $cart_qty }}"',
            $this->bootstrapSrc,
            'Bootstrap template: must expose data-cart-count for CSS / JS hooks'
        );

        // No more bare static "0" badge content (AC#1).
        $this->assertStringNotContainsString(
            'js-shopping-cart-quantity">0</span>',
            $this->bootstrapSrc,
            'Bootstrap template: bare `<span class="...js-shopping-cart-quantity">0</span>` must be gone — render $cart_qty from cart_sum() instead'
        );
    }

    #[Test]
    public function shop_js_toggles_hidden_attribute_on_cart_modify(): void
    {
        // The JS hook MUST toggle the `hidden` attribute when the
        // cart_items_quantity changes. The previous shape was a bare
        // .html(...) update which left a stale "0" visible after a
        // cart-empty operation. New shape: parse to int, set
        // data-cart-count, then attach/remove `hidden` + `aria-hidden`.
        $this->assertMatchesRegularExpression(
            '/parseInt\(\s*data\.cart_items_quantity\s*,\s*10\s*\)/',
            $this->shopJsSrc,
            'shop.js: must parse cart_items_quantity to an integer before the comparison'
        );
        $this->assertMatchesRegularExpression(
            '/\$badge\.attr\(\s*"hidden"\s*,\s*"hidden"\s*\)/',
            $this->shopJsSrc,
            'shop.js: must set `hidden` attribute when qty <= 0'
        );
        $this->assertMatchesRegularExpression(
            '/\$badge\.removeAttr\(\s*"hidden"\s*\)/',
            $this->shopJsSrc,
            'shop.js: must clear `hidden` attribute when qty > 0'
        );
        $this->assertMatchesRegularExpression(
            '/\$badge\.attr\(\s*"data-cart-count"\s*,\s*qty\s*\)/',
            $this->shopJsSrc,
            'shop.js: must mirror the parsed qty onto data-cart-count'
        );
    }

    #[Test]
    public function task_003_task_018_delegated_listener_is_unaffected(): void
    {
        // AC#3: no regression on the delegated add-to-cart listener
        // established in TASK-003 / TASK-018. Pin the marker selector
        // (`mw-add-to-cart-btn`) is still referenced by shop.js and
        // not deleted by this cycle.
        $this->assertStringContainsString(
            'mw-add-to-cart-btn',
            $this->shopJsSrc,
            'shop.js: TASK-003/TASK-018 delegated listener selector `mw-add-to-cart-btn` must still be present'
        );
    }
}
