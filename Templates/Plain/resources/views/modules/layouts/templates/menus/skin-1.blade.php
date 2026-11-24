{{--
  type: layout
  content_type: static
  name: Menu - skin-1
  position: 1
  description: Storefront header (promo bar + logo / search / currency / cart + nav)
  categories: Menu
--}}

@php
    $header_top_menu = get_option('header_top_menu', $params['id']);
    $cart_qty = (int) cart_sum(false);
@endphp

@if(isset($header_top_menu) && $header_top_menu)
    <div class="templates-top-header-menu">
        @include('modules.layouts::partials.menu.parts.templates-top-header-menu')
    </div>
@endif

{{-- Storefront header chrome now lives in the shared framework component
     packages/microweber-css-framework-bootstrap/.../components/mw-header.css
     (.mw-store-* classes), loaded in <head>. Only the few CASCADE-SENSITIVE
     rules remain inline here: they are id-scoped + !important and MUST stay in
     this <body> <style> so source order lets them beat saved Live-Edit CSS
     (framework <link> in <head> would lose that battle). --}}
<style>
    /* Flush the promo bar to the very top (beats a global #mw-header-background
       padding-block from saved Live-Edit CSS; id+class beats the id-only rule). */
    #mw-header-background.mw-store-header {
        padding-top: 0 !important;
    }

    /* Cart icon follows the header's real text colour, not --mw-header-link-color
       (which a saved dark-header Live-Edit design forces to #fff — invisible on a
       light header). id-scoped + !important to beat those saved rules. */
    #mw-header-background .mw-store-cart-link,
    #mw-header-background .mw-store-cart-link svg {
        color: var(--mw-body-color, #1f2937) !important;
        fill: var(--mw-body-color, #1f2937) !important;
    }

    #mw-header-background .mw-store-cart-link:hover,
    #mw-header-background .mw-store-cart-link:hover svg {
        color: var(--mw-primary-color, #1f2937) !important;
        fill: var(--mw-primary-color, #1f2937) !important;
    }

    /* Nav link colour (!important to beat saved Live-Edit header-link rules); the
       nav-link LAYOUT lives in the framework mw-header.css. */
    .mw-store-nav .nav-link,
    .mw-store-nav ul > li > a {
        color: var(--mw-header-link-color, #1f2937) !important;
    }

    .mw-store-nav ul > li > a:hover {
        color: var(--mw-primary-color, #1f2937) !important;
    }
</style>

<section class="header-background mw-menu-skin-com mw-store-header px-0" id="mw-header-background">
    <script>
        addEventListener('DOMContentLoaded', e => {
            mw.MWSiteMobileMenu({
                threshold: 1280,
                size: '25px',
                color: 'var(--mw-body-color, #1f2937)',
            }, 5);
        });
    </script>

    {{-- Promo / announcement bar --}}
    <div class="mw-store-promobar">
        <div class="container-fluid text-center edit" field="store-promobar-{{ $params['id'] }}" rel="module">
            {{ _e('Free shipping on orders over $75 — 30-day easy returns', true) }}
        </div>
    </div>

    {{-- Main header: logo | search | currency + cart --}}
    <div class="container-fluid mw-menu-skin-com-container mw-store-header-main">
        <div class="d-flex align-items-center gap-3 py-3 flex-nowrap">
            <module type="logo" id="header-logo-{{ $params['id'] }}"
                    class="mw-store-logo col-auto w-auto align-self-center px-0 module module-logo"/>

            <div class="mw-store-search flex-grow-1 d-none d-lg-block">
                <module type="search" id="header-search-{{ $params['id'] }}"/>
            </div>

            <div class="mw-store-actions d-flex align-items-center gap-2 ms-auto">
                <module type="currency" id="header-currency-{{ $params['id'] }}"/>

                <div class="mw-store-cart dropdown">
                    <a class="mw-store-cart-link d-flex align-items-center" href="javascript:;"
                       data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
                       aria-label="{{ _e('Shopping cart', true) }}">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="26" viewBox="0 -960 960 960" width="26" aria-hidden="true"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg>
                        <span class="mw-store-cart-count js-shopping-cart-quantity"
                              data-cart-count="{{ $cart_qty }}"
                              @if($cart_qty <= 0) hidden aria-hidden="true" @endif>{{ $cart_qty }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end mw-store-cart-dropdown p-2">
                        @livewire('modules.checkout.livewire.cart-items')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Nav row --}}
    <div class="mw-store-nav">
        <div class="container-fluid">
            <div class="mw-vhmbgr--navigation d-flex justify-content-center">
                <module type="menu" name="header_menu" id="header_menu-{{ $params['id'] }}12" template="navbar"/>
            </div>
        </div>
    </div>
</section>
