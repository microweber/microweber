{{--
    /cart standalone page view.

    Wraps the existing `Modules\Checkout\Livewire\CartItems` Livewire
    component in the active template's master layout so the public
    header/footer/CSS bundles render around the cart contents.

    The active template is resolved at runtime via `template_dir()`
    helper. We use a runtime-resolved `@extends()` so /cart works
    regardless of which template is active (Bootstrap, Big2, etc.) —
    every Microweber template ships a `layouts.master` view.

    History:
      - Cycle-163 / AI-204 (2026-05-10) — initial standalone page +
        AI-204 / AI-206 touch-target + image-size fixes
      - task-2026-05-17-0e6cfa / AI-796 — empty-state cleanup:
        * Slice A: conditional-render the "Proceed to Checkout" CTA
          only when the cart has items (was a dead button on the
          empty cart, competing with the Livewire empty-state CTA)
        * Slice B: brand-blue (#0d6efd / MwColors::Blue) override
          on .mw-cart-standalone-checkout-cta so it doesn't pick up
          Big2-template's salmon-orange .btn-primary
        * Slice D: Slice already satisfied at cycle-163 — page
          @extends the active template's master so navigation +
          footer chrome wrap the cart. Documented here for the
          contract test pin.

    Lineage: AI-759 (competing-CTA anti-pattern, 4th cross-surface
    instance) + AI-737 (#0d6efd brand-blue standardisation) +
    AI-731 (empty-state CTA chrome).
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
    // AI-944 / task-2026-05-22 — add Bootstrap fallback when the active template's
    // master layout isn't registered (e.g. Big2 is gitignored on fresh clones).
    // Matches the $extendsView ?? 'templates.bootstrap::layouts.master' pattern
    // from AI-757/AI-795 (shop index.blade.php).
    $candidateMaster = "templates.{$activeTemplate}::layouts.master";
    $masterLayout = view()->exists($candidateMaster)
        ? $candidateMaster
        : 'templates.bootstrap::layouts.master';

    /*
     * task-2026-05-17-0e6cfa / AI-796 Slice A — gate the bottom
     * Proceed-to-Checkout CTA on the cart having ≥1 item. Pre-fix,
     * the button rendered on the empty cart too — dead button,
     * competing with the Livewire empty-state CTA. cart_manager->get()
     * returns null when empty; coerce to [] for the empty()-check.
     */
    $mwAi796CartItems = function_exists('mw') ? (mw()->cart_manager->get() ?? []) : [];
    $mwAi796HasItems = !empty($mwAi796CartItems);
@endphp

@extends($masterLayout)

@section('content')
    <section class="container py-4 mw-cart-standalone-page" id="mw-cart-standalone-page">
        <h1 class="mb-3">{{ __('Your Cart') }}</h1>

        @livewire('modules.checkout.livewire.cart-items')

        @if($mwAi796HasItems)
            <div class="mt-4 text-center mw-cart-standalone-checkout-cta-wrap">
                {{-- Cycle-163 + task-2026-05-17-0e6cfa: explicit Proceed
                     to Checkout CTA. Pre-AI-796 it rendered unconditionally;
                     AI-796 Slice A gates it on cart having items so the
                     empty cart shows ONE CTA (the Livewire "Continue
                     shopping" link), not two competing ones. --}}
                <a href="{{ url('/checkout/checkout') }}"
                   class="btn btn-primary mw-cart-standalone-checkout-cta"
                   aria-label="{{ __('Proceed to Checkout') }}">
                    {{ __('Proceed to Checkout') }}
                </a>
            </div>
        @endif
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

        /*
         * task-2026-05-17-0e6cfa / AI-796 Slice B — brand-blue override
         * for the Proceed-to-Checkout CTA. Big2's active template ships
         * .btn-primary as a salmon orange (~#E69462), which collides
         * with the AI-737-mandated brand blue #0d6efd (MwColors::Blue)
         * used across admin + checkout + .mw-table-empty-cta. !important
         * needed because the template's app.css already declares
         * .btn-primary at high specificity. Mirrors the colour shape of
         * .mw-table-empty-cta (in microweber-filament-theme.css) so the
         * design language is consistent across surfaces.
         */
        #mw-cart-standalone-page .mw-cart-standalone-checkout-cta {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            color: #fff !important;
            border-radius: 0.5rem !important;
            font-weight: 500;
            transition: background-color 120ms ease, transform 120ms ease;
        }
        #mw-cart-standalone-page .mw-cart-standalone-checkout-cta:hover,
        #mw-cart-standalone-page .mw-cart-standalone-checkout-cta:focus-visible {
            background-color: #0b5ed7 !important;
            border-color: #0a58ca !important;
            color: #fff !important;
            transform: translateY(-1px);
        }
        #mw-cart-standalone-page .mw-cart-standalone-checkout-cta:active {
            background-color: #0a58ca !important;
            transform: translateY(0);
        }

        /*
         * task-2026-05-17-0e6cfa / AI-796 Slices B+C — empty-state
         * chrome for the Livewire CartItems else-branch (the markup
         * rendered when the cart has no items). The Livewire view
         * (Modules/Checkout/.../cart-items.blade.php) uses Tailwind
         * classes that aren't loaded on the public template, so
         * style the empty state explicitly here. Scoped to
         * .mw-cart-standalone-page so it doesn't leak elsewhere.
         */
        #mw-cart-standalone-page .mw-cart-empty {
            text-align: center;
            padding: 48px 24px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            max-width: 480px;
            margin: 24px auto;
        }
        #mw-cart-standalone-page .mw-cart-empty__heading {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 12px;
        }
        #mw-cart-standalone-page .mw-cart-empty__body {
            font-size: 16px;
            line-height: 1.5;
            color: #4b5563;
            margin: 0 0 24px;
        }
        #mw-cart-standalone-page .mw-cart-empty__actions {
            display: flex;
            justify-content: center;
        }
        #mw-cart-standalone-page .mw-cart-empty-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 44px;
            padding: 0.625rem 1.25rem;
            background-color: #0d6efd;
            color: #fff;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.9375rem;
            line-height: 1.25;
            text-decoration: none;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: background-color 120ms ease, transform 120ms ease;
            border: 1px solid transparent;
        }
        #mw-cart-standalone-page .mw-cart-empty-cta:hover,
        #mw-cart-standalone-page .mw-cart-empty-cta:focus-visible {
            background-color: #0b5ed7;
            color: #fff;
            text-decoration: none;
            transform: translateY(-1px);
        }
        #mw-cart-standalone-page .mw-cart-empty-cta:active {
            transform: translateY(0);
            background-color: #0a58ca;
        }
        @media (prefers-reduced-motion: reduce) {
            #mw-cart-standalone-page .mw-cart-standalone-checkout-cta,
            #mw-cart-standalone-page .mw-cart-empty-cta {
                transition: none;
            }
        }
    </style>
@endsection
