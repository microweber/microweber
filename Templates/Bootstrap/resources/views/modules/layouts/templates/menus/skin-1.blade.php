<?php

/*

  type: layout
  content_type: static
  name: Menu - skin-1
  position: 1
  description: Menu - skin-1
  categories: Menu


*/

?>

<div class="templates-top-header-menu">

    <style>
        .top-header-background.header-top ul {
            list-style: none;
        }
    </style>

    <div class="top-header-background header-top navbar">

        <div>
            <li class="nav-item dropdown btn-socials ps-md-3 my-xl-0 my-1">
                <module type="social_links" id="header-layout-social-links" template="skin-1"/>
            </li>
        </div>

        <ul class="d-flex align-items-center flex-wrap gap-2 mb-0">
            <li class="nav-item dropdown btn-phone ps-md-3 my-xl-0 my-1">
                <a href="tel:0888%20888%20888" class="nav-link text-decoration-none ps-0 pe-2" style="font-size: 16px;">
                    <span class="mdi mdi-phone"></span>
                    0888 888 888
                </a>
            </li>

            <a class="nav-item dropdown btn-contacts ps-md-3 my-xl-0 my-1">
            </a>
            <module type="btn" id="header-layout-btn" button_size="btn-sm" button_text="CONTACT US"/>

            <li class="nav-item dropdown btn-search">
                {{-- AI-205 (cycle-164 2026-05-10): replaced
                     `<i class="mdi mdi-magnify mdi-20px">` with an
                     inline SVG matching the neighboring user/cart
                     icons. The MDI font icon inherits the theme's
                     link color (orange in Bootstrap) and is sized at
                     20px while user is 24px and cart is 28px — so it
                     read as a different style + smaller. The inline
                     SVG uses `fill="currentColor"` (same as user/
                     cart) and is sized at 28×28 (matching cart).
                     The path is the Material outlined "search" icon
                     so it visually pairs with the Material outlined
                     person/cart icons used by the surrounding
                     buttons. --}}
                <a href="#" class="nav-link" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" aria-label="Search"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 -960 960 960" width="28"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg></a>
                <div class="dropdown-menu dropdown-menu-end">
                    <div class="row">
                        <form class="col w-300 mx-auto input-glass" action="{{ url('search') }}" method="get">
                            <input class="form-control border-0" type="text" id="keywords" name="keywords" placeholder="Search...">
                        </form>
                    </div>
                </div>
            </li>

            <li class="dropdown btn-member ps-md-3">
                {{-- AI-205 (cycle-164 2026-05-10): bumped user/member
                     icon from 24×24 to 28×28 to match cart icon + new
                     search icon for uniform header-icon size. --}}
                <a href="#" class="nav-link dropdown px-0" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" aria-label="Account">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 -960 960 960" width="28"><path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"/></svg>
                </a>
                <ul class="mw-big-dropdown dropdown-menu dropdown-menu-end">
                    @if(user_id())
                        <li><a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Profile</a></li>
                        <li><a href="#" data-bs-toggle="modal" data-bs-target="#ordersModal">My Orders</a></li>
                        <li><a href="{{ admin_url() }}">Admin panel</a></li>
                        <li><a href="{{ url('logout') }}">Logout</a></li>
                    @else
                        <li><a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a></li>
                        <li><a href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a></li>
                    @endif
                </ul>
            </li>

            <li class="nav-item dropdown btn-shopping-cart ps-md-3">
                @php
                    // TASK-022 / TICKET-CZ / AI-40 (cycle-59 2026-05-08):
                    // hide the cart-count badge when the cart is empty so
                    // the public store never renders a misleading "0".
                    // The span stays in the DOM so shop.js can update its
                    // text on cartModify; we use the HTML5 `hidden`
                    // attribute and aria-hidden to remove it from the AT
                    // tree when the count is 0.
                    $cart_qty = function_exists('cart_sum') ? (int) cart_sum(false) : 0;
                @endphp
                <a href="#" class="nav-link px-0 d-flex align-items-center" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="btn btn-outline-primary btn-sm mx-2 js-shopping-cart-quantity"
                          data-cart-count="{{ $cart_qty }}"
                          @if ($cart_qty <= 0) hidden aria-hidden="true" @endif>{{ $cart_qty }}</span>
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 -960 960 960" width="28"><path d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z"/></svg>
                </a>
                <div class="mw-big-dropdown-cart dropdown-menu dropdown-menu-end shopping-cart px-2">
                    <module type="shop/cart" id="header-layout-shop-cart" template="small_modal"/>
                </div>
            </li>
        </ul>

    </div>

</div>

<style>
    .mw-menu-skin-com-container {
        position: relative;
        z-index: 1;
    }

    .mw-menu-skin-com {
        position: sticky;
        top: 0;
        z-index: 9;
        backdrop-filter: saturate(180%) blur(20px);
    }

    .mw-menu-skin-com ul:not(.ul-deep) > li > a {
        color: #fff;
        display: inline-block;
        line-height: 50px;
        position: relative;
        transition: .3s;
    }

    .mw-menu-skin-com .dropdown-menu {
        min-width: 15rem !important;
    }

    .mw-menu-skin-com ul > li {
        position: relative;
    }

    .mw-menu-skin-com .ul-deep {
        text-decoration: none;
    }

    .mw-menu-skin-com ul > li > a span:after {
        position: absolute;
        content: '';
        bottom: 10px;
        left: 0;
        height: 1px;
        width: 70%;
        background-color: var(--mw-header-link-hover-color);
        transition: .5s;
        opacity: 0;
    }

    .mw-menu-skin-com ul > li > a:hover {
        opacity: .8;
    }

    .mw-menu-skin-com .dropdown-menu {
        max-width: 15rem !important;
    }

    .mw-menu-skin-com ul > li > a:hover span:after {
        width: 100%;
        opacity: 1;
    }
    .mw-menu-skin-com ul > li.depth-1 > a span:after {
        opacity: 0 !important;
        content: unset !important;
    }

    .mw-vhmbgr--navigation .navbar-nav {
        gap: 20px;
    }
</style>

<section class="header-background mw-menu-skin-com px-0" id="mw-header-background">

    <script>
        addEventListener('DOMContentLoaded', e => {
            mw.MWSiteMobileMenu({
                threshold: 1280,
                size: '25px',
                color: 'var(--primaryColor)',
            }, 5);
        });
    </script>

    <div class="container-fluid justify-content-center mw-menu-skin-com-container">
        <div class="row col-12 d-flex justify-content-center">
            <module type="logo" id="header-logo-header-layout" class="me-auto col-auto col-xl-4 mw-big-header-logo w-auto align-self-center my-md-0 my-1 px-0"/>

            <div class="menu-header-skin-1 col-auto d-flex align-items-center justify-content-end">
                <div class="mw-vhmbgr--navigation">
                    <module type="menu" name="header_menu" id="header_menu-header-layout12" template="navbar"/>
                </div>
                {{-- The mobile hamburger SVG is injected by
                     packages/frontend-assets/resources/assets/widgets/hamburger.js
                     (MWSiteMobileMenu — fired from the script block at the top
                     of this file). It walks every `.mw-vhmbgr--navigation` and
                     appends a `.mw-vhmbgr-wrapper > svg` sibling, so the
                     hardcoded copy that used to live here was duplicating it.
                     Removing the static HTML keeps a single icon driven by
                     the JS (which also wires up size/color/threshold options
                     and the open/close handler). --}}
            </div>
        </div>
    </div>
</section>
