{{--
    Cycle-163 / AI-204 (2026-05-10) — /cart standalone page view.

    Wraps the existing `Modules\Checkout\Livewire\CartItems` Livewire
    component in the active template's master layout so the public
    header/footer/CSS bundles render around the cart contents.

    The active template is resolved at runtime via `template_dir()`
    helper. We use a runtime-resolved `@extends()` so /cart works
    regardless of which template is active (Bootstrap, Big2, etc.) —
    every Microweber template ships a `layouts.master` view.

    The CartItems Livewire component already provides:
      - line items (image, title, price)
      - qty input wired to updateQuantity()
      - remove button wired to removeItem()
      - cart totals + checkout CTA
      - empty state with Continue Shopping CTA
--}}

@php
    /*
     * Resolve the active template's blade-namespace, which is registered
     * by `BaseTemplateServiceProvider::loadViewsFrom(...,
     * 'templates.' . $this->moduleNameLower)` — always lowercase. The
     * `template_dir()` helper returns the filesystem path
     * (e.g. "Templates/Bootstrap/"), so we basename + lowercase.
     */
    $activeTemplate = function_exists('template_dir')
        ? strtolower(basename(rtrim(template_dir(), DIRECTORY_SEPARATOR)))
        : 'bootstrap';
    $masterLayout = "templates.{$activeTemplate}::layouts.master";
@endphp

@extends($masterLayout)

@section('content')
    <section class="container py-4 mw-cart-standalone-page" id="mw-cart-standalone-page">
        <h1 class="mb-3">{{ __('Your Cart') }}</h1>

        @livewire('modules.checkout.livewire.cart-items')

        <div class="mt-4 text-center">
            {{-- Cycle-163: explicit Proceed to Checkout CTA so users
                 always have a clear path forward; the CartItems component's
                 own empty-state CTA goes home, but a populated cart needs
                 a checkout button. --}}
            <a href="{{ url('/checkout/checkout') }}"
               class="btn btn-primary mw-cart-standalone-checkout-cta"
               aria-label="{{ __('Proceed to Checkout') }}">
                {{ __('Proceed to Checkout') }}
            </a>
        </div>
    </section>

    <style>
        /*
         * AI-204 (cycle-163 2026-05-10) — touch-target floor for the
         * standalone /cart page controls. The CartItems Livewire view
         * (`livewire/cart-items.blade.php`) renders the qty <input> with
         * Tailwind py-1.5 (~30px tall) and the remove <button> with p-2
         * (~32px) — both below the WCAG 2.5.5 / iOS HIG 44x44 floor.
         *
         * Scoped to .mw-cart-standalone-page so this doesn't affect
         * the same component if it's embedded elsewhere with different
         * sizing intent (e.g. inside a tighter dropdown).
         */
        @media (max-width: 768px), (pointer: coarse) {
            /* Qty inputs (`<input type="number" wire:model.live.debounce.
               1500ms="cartItems.X.qty">`) measured 202x28 in cycle-163
               browser-verify — height below WCAG 2.5.5 / iOS HIG 44x44.
               There are no other type=number inputs inside the standalone
               cart page so an unqualified type=number selector is safe. */
            #mw-cart-standalone-page input[type="number"] {
                min-width: 44px !important;
                min-height: 44px !important;
                padding: 8px 10px !important;
            }
            #mw-cart-standalone-page button[wire\:click^="removeItem"] {
                min-width: 44px !important;
                min-height: 44px !important;
                padding: 10px !important;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            #mw-cart-standalone-page .mw-cart-standalone-checkout-cta {
                min-width: 44px;
                min-height: 44px;
                padding: 10px 18px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
        }

        /*
         * AI-206 (cycle-164b 2026-05-10) — Cart item images oversized
         * on /cart standalone page.
         *
         * agent-test verification of cycle-163 surfaced that line-item
         * `<img>` elements rendered at 602×402 (the picsum.photos
         * source resolution) instead of being constrained to ~80×80.
         * Root cause: the CartItems Livewire view uses Tailwind
         * `w-20 h-20` classes on the `<img>` but Tailwind isn't loaded
         * on the public Bootstrap template — only the Filament admin
         * panel ships Tailwind.
         *
         * Constrain via plain CSS scoped to the standalone cart wrap.
         * `object-fit: cover` keeps the aspect ratio while the box
         * stays a fixed 80×80. NOT in the @media block above — image
         * size matters on desktop too (otherwise the cart row sprawls).
         */
        #mw-cart-standalone-page img {
            max-width: 80px;
            max-height: 80px;
            object-fit: cover;
        }
    </style>
@endsection
