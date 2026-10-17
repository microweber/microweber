

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
    // master layout isn't registered (e.g. Big2 is gitignored on fresh clones).
    // Matches the $extendsView ?? 'templates.bootstrap::layouts.master' pattern
    $candidateMaster = "templates.{$activeTemplate}::layouts.master";
    $masterLayout = view()->exists($candidateMaster)
        ? $candidateMaster
        : 'templates.bootstrap::layouts.master';

    $mwCartCartItems = function_exists('mw') ? (app()->cart_manager->get() ?? []) : [];
    $mwCartHasItems = !empty($mwCartCartItems);
@endphp

@extends($masterLayout)

@section('content')
    <section class="container py-4 mw-cart-standalone-page" id="mw-cart-standalone-page">
        <h1 class="mb-3">{{ __('Your Cart') }}</h1>

        @livewire('modules.checkout.livewire.cart-items')

        @if($mwCartHasItems)
            <div class="mt-4 text-center mw-cart-standalone-checkout-cta-wrap">

                <a href="{{ url('/checkout/checkout') }}"
                   class="btn btn-primary mw-cart-standalone-checkout-cta"
                   aria-label="{{ __('Proceed to Checkout') }}">
                    {{ __('Proceed to Checkout') }}
                </a>
            </div>
        @endif
    </section>

    <style>

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

        #mw-cart-standalone-page img {
            max-width: 80px;
            max-height: 80px;
            object-fit: cover;
        }

        /* Product thumbnail: the real <img> is caught by the rule above; the
           no-image heroicon-o-cube <svg> needs its own constraint. a > svg
           targets only the thumbnail link — the remove button is a button>svg. */
        #mw-cart-standalone-page a > svg {
            width: 80px;
            height: 80px;
            max-width: 80px;
            max-height: 80px;
            flex: 0 0 auto;
        }
        /* Each cart line = flex row. Item rows carry .flex; the totals block
           (a .mt-8 sibling) does not, so it is left to the :not(.flex) rule. */
        #mw-cart-standalone-page .gap-y-4 > .flex {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: #fff;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        #mw-cart-standalone-page .flex-1 {
            flex: 1 1 auto;
            min-width: 0;
        }
        #mw-cart-standalone-page .w-24 {
            flex: 0 0 auto;
            width: 6rem;
        }
        #mw-cart-standalone-page .text-right {
            text-align: right;
        }
        /* Cart Totals block + its rows. */
        #mw-cart-standalone-page .gap-y-4 > div:not(.flex) {
            background: #fff;
            padding: 1rem;
            margin-top: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        #mw-cart-standalone-page .gap-y-2 > .flex {
            display: flex;
            justify-content: space-between;
            padding: 0.25rem 0;
        }

        /* Qty <input type=number>: Tailwind w-20/py-1.5 not loaded, so it
           stretched to ~213px and overflowed its column. Constrain to a compact
           field. (The @media touch-target rule above still bumps it to 44px on
           coarse-pointer / narrow viewports via !important.) */
        #mw-cart-standalone-page input[type="number"] {
            width: 4.5rem;
            max-width: 100%;
            height: 38px;
            padding: 0 0.5rem;
            text-align: center;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background: #fff;
            color: #111827;
        }
        /* Remove button: Tailwind bg-red-600/p-3/rounded-md and the icon's
           w-5/h-5 aren't loaded, so the button was background-less with a 0x0
           invisible icon. Style it explicitly. */
        #mw-cart-standalone-page button[wire\:click^="removeItem"] {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            flex: 0 0 auto;
            transition: background-color 120ms ease;
        }
        #mw-cart-standalone-page button[wire\:click^="removeItem"]:hover,
        #mw-cart-standalone-page button[wire\:click^="removeItem"]:focus-visible {
            background: #b91c1c;
        }
        #mw-cart-standalone-page button[wire\:click^="removeItem"] svg {
            width: 20px;
            height: 20px;
            display: block;
        }

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
