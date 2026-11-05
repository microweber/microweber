{{--
  type: layout
  content_type: static
  name: Menu - skin-10
  position: 10
  description: Menu - skin-10
  categories: Menu
--}}

@php
    $header_top_menu = get_option('header_top_menu', $params['id']);
@endphp

@if(isset($header_top_menu) && $header_top_menu)
    <div class="templates-top-header-menu">
        @include('modules.layouts::partials.menu.parts.templates-top-header-menu')
    </div>
@endif

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

    .mw-menu-skin-com .dropdown menu {
        max-width: 15rem !important;
    }

    .mw-menu-skin-com ul > li > a:hover span:after, .mw-menu-skin-com ul > li.active span:after {
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

<section class="header-background m-7 rounded-lg px-0 mw-menu-skin-com" id="mw-header-background">
    <script>
        addEventListener('DOMContentLoaded', e => {
            mw.MWSiteMobileMenu({
                threshold: 1280,
                size: '25px',
                color: 'var(--primaryColor)',
            }, 5);
        });
    </script>

    <div class="container-fluid mw-menu-skin-com-container">
        <x-row class="col-12 d-flex justify-content-md-between justify-content-center align-items-center order-1">
            <module type="logo" id="header-logo-{{ $params['id'] }}"
                    class="col-auto mw-big-header-logo w-auto align-self-center my-md-0 my-1 module module-logo "/>

            <div
                class="menu-header-skin-1 col-sm-auto col-12 d-flex align-items-center justify-content-center order-lg-2 order-3 mx-auto">
                <div class="mw-vhmbgr--navigation">
                    <module type="menu" name="header_menu" id="header_menu-{{ $params['id'] }}12" template="navbar"/>
                </div>
            </div>

            <div class="col-auto order-lg-3 order-2 ms-lg-0 ms-auto edit d-sm-block d-none"
                 field="header_menu-{{ $params['id'] }}button-wrapper" rel="global"
                 id="header_menu-{{ $params['id'] }}button-wrapper">
                <module type="btn" button_style="btn-primary" id="header_menu-{{ $params['id'] }}button"/>
            </div>
        </x-row>
    </div>
</section>
