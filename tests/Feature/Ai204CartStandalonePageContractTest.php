<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-163 / AI-204 (2026-05-10) — /cart standalone page contract.
 *
 * Background:
 *   - Microweber's `<module type="shop/cart">` has no registered
 *     renderer (only `cart_add` is registered). The reference in
 *     `CartServiceProvider.php:81` (CartModule class) is commented
 *     out AND the class doesn't exist on disk.
 *   - Page.content body strings don't process @livewire directives,
 *     so the cycle-161 attempt to embed `<module type="shop/cart">`
 *     in a Page rendered an empty container.
 *
 * Cycle-163 fix: a real route + controller + view that wraps the
 * existing `Modules\Checkout\Livewire\CartItems` component (which
 * already has qty input + remove button + cart totals + empty
 * state) in the active template's master layout.
 *
 * The route lives in `Modules/Cart/routes/web.php` so it loads
 * BEFORE the Microweber catch-all Page resolver. That way /cart
 * hits the controller instead of falling through to a Page lookup.
 */
class Ai204CartStandalonePageContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function controller_exists_with_anchor(): void
    {
        $ctrl = $this->read('Modules/Cart/Http/Controllers/CartPageController.php');
        $this->assertStringContainsString('AI-204', $ctrl,
            'CartPageController.php MUST carry the AI-204 anchor inline.');
        $this->assertMatchesRegularExpression('/[Cc]ycle-163/', $ctrl,
            'CartPageController.php MUST carry the cycle-163 anchor (any case) inline.');
        $this->assertMatchesRegularExpression(
            '/public function show\([^)]*\)/',
            $ctrl,
            'CartPageController MUST expose a `show()` action.'
        );
    }

    #[Test]
    public function view_namespace_uses_modules_dot_cart(): void
    {
        $ctrl = $this->read('Modules/Cart/Http/Controllers/CartPageController.php');
        // BaseModuleServiceProvider::registerViews() registers the
        // namespace as `modules.cart` (lowercase). The cycle-163 first
        // pass tried `cart::page` which threw "No hint path defined
        // for [cart]" — pin the corrected namespace.
        $this->assertMatchesRegularExpression(
            '/return\s+view\(\s*[\'"]modules\.cart::page[\'"]/',
            $ctrl,
            'CartPageController::show() MUST render `modules.cart::page` '
            . '(not `cart::page` — that namespace is not registered by '
            . 'BaseModuleServiceProvider).'
        );
    }

    #[Test]
    public function route_registered_at_cart_path_in_web_php(): void
    {
        $routes = $this->read('Modules/Cart/routes/web.php');
        $this->assertStringContainsString("Route::get('cart',", $routes,
            'Cart/routes/web.php MUST register `Route::get(\'cart\', ...)` '
            . 'so /cart hits the controller before falling through to '
            . 'the Microweber catch-all Page resolver.');
        $this->assertStringContainsString('CartPageController::class', $routes,
            'Cart/routes/web.php MUST point the /cart route at '
            . 'CartPageController::class.');
        $this->assertStringContainsString('AI-204', $routes,
            'Cart/routes/web.php MUST carry the AI-204 anchor on the '
            . 'cart-route registration.');
    }

    #[Test]
    public function page_view_extends_active_template_master_layout(): void
    {
        $view = $this->read('Modules/Cart/resources/views/page.blade.php');
        // The view must dynamically extend `templates.<active>::layouts.master`
        // using a lowercased basename so it works regardless of which
        // template is active.
        $this->assertMatchesRegularExpression(
            '/strtolower\(basename\(/',
            $view,
            'page.blade.php MUST lowercase the active template basename '
            . 'before composing the layout namespace (template_dir() '
            . 'returns capitalized "Bootstrap" but the namespace '
            . 'BaseTemplateServiceProvider registers is lowercase).'
        );
        $this->assertMatchesRegularExpression(
            '/templates\.\{?\$activeTemplate\}?::layouts\.master/',
            $view,
            'page.blade.php MUST extend `templates.{$activeTemplate}::layouts.master` '
            . 'so the public header/footer wrap the cart contents.'
        );
    }

    #[Test]
    public function page_view_embeds_cart_items_livewire_component(): void
    {
        $view = $this->read('Modules/Cart/resources/views/page.blade.php');
        // The fully-functional cart-edit Livewire component is registered
        // as `modules.checkout.livewire.cart-items` in
        // CheckoutServiceProvider. The view MUST embed it via @livewire.
        $this->assertStringContainsString(
            "@livewire('modules.checkout.livewire.cart-items')",
            $view,
            'page.blade.php MUST embed `@livewire(\'modules.checkout.'
            . 'livewire.cart-items\')` — that component already has '
            . 'qty input + remove button + totals + empty state.'
        );
    }

    #[Test]
    public function page_view_pins_44_floor_on_cart_controls(): void
    {
        $view = $this->read('Modules/Cart/resources/views/page.blade.php');
        // Qty input was 202x28 in cycle-163 first browser-verify —
        // height below WCAG 2.5.5 / iOS HIG 44x44.
        $this->assertMatchesRegularExpression(
            '/#mw-cart-standalone-page\s+input\[type="number"\][\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $view,
            'page.blade.php MUST pin min-height:44px !important on the '
            . 'qty input so the Tailwind py-1.5 floor is bumped past '
            . 'the touch-target threshold.'
        );
        $this->assertMatchesRegularExpression(
            '/#mw-cart-standalone-page\s+button\[wire\\\\:click\^="removeItem"\][\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $view,
            'page.blade.php MUST pin min-height:44px !important on the '
            . 'remove button (so the Tailwind p-2 32px floor is bumped).'
        );
        // Floor must apply on touch viewports
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*768px\)\s*,\s*\(pointer:\s*coarse\)/',
            $view,
            'page.blade.php MUST gate the touch-target floor on `(max-'
            . 'width: 768px), (pointer: coarse)` so desktop density is '
            . 'preserved.'
        );
    }

    #[Test]
    public function checkout_cart_items_view_filters_non_array_totals(): void
    {
        $view = $this->read('Modules/Checkout/resources/views/livewire/cart-items.blade.php');
        // mw()->cart_manager->totals() returns mixed shape — `subtotal`
        // and `total` are arrays with [label, value, amount];
        // `shipping`, `tax`, `discount` are EMPTY STRINGS when not
        // applicable. The cycle-N foreach indexed `$total['label']`
        // unconditionally and crashed with "Trying to access array
        // offset on null" the first time the standalone /cart route
        // hit this view. Cycle-163 added the is_array filter.
        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*!is_array\(\s*\$total\s*\)\s*\)\s*@continue/',
            $view,
            'cart-items.blade.php MUST filter $total via @if(!is_array)) '
            . '@continue before indexing — the cart_manager->totals() '
            . 'return shape is mixed (arrays for subtotal/total, empty '
            . 'strings for shipping/tax/discount).'
        );
    }
}
