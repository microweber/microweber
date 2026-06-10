{{--
  type: layout
  content_type: static
  name: Menu - skin-6
  position: 6
  description: Menu - skin-6
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
    .mw-header-6-popup {
        height: 100%;
        transform: translateY(100%); /* Initially position the menu off the bottom of the screen */
        transition: transform 0.5s ease; /* Animate the transform */
        top: 98px;
        right: -300px;
        left: unset !important;
        max-width: 600px !important;

        ul {
            list-style: none;
            /*margin-top: 80px;*/
            padding: 0;
            transition: all 0.5s;
            transition-delay: 0.5s;
            opacity: 1;
            transform: translateY(0%);
        }

        li {
            padding: 15px 40px;
            text-align: left !important;
        }

        li a {
            font-size: 24px;
            font-weight: 300;
            color: #fff;
            text-decoration: none;
            transition: all 0.5s;
        }

        li a:hover {
            opacity: 0.5;
        }
    }

    /* Add a class for when the menu is open */
    .mw-header-6-popup.open {
        transform: translateY(0);
    }
</style>

<section class="header-background px-0 mw-header-menu-6" id="mw-header-background">
    <script>
        addEventListener('DOMContentLoaded', e => {
            mw.MWSiteMobileMenu({
                threshold: 9999,
                size: '25px',
                color: 'var(--primaryColor)',
                popupTemplate: 'mw-vhmbgr-active-popup mw-header-6-popup'
            }, 5);
        });

        var activeElement = document.querySelector('.mw-vhmbgr-active');
        var menuElement = document.querySelector('.mw-header-menu-6');
        if (activeElement) {
            menuElement.classList.toggle('open');
        }
    </script>

    <div class="container-fluid px-md-5 justify-content-center">
        <div class="row col-12 d-flex justify-content-center ">
            <module type="logo" id="header-logo-{{ $params['id'] }}"
                    class="me-auto col-auto col-xl-4 mw-big-header-logo w-auto align-self-center my-md-0 my-1 px-0 module module-logo "/>

            <div class="menu-header-skin-1 col-auto d-flex align-items-center justify-content-end">
                <div class="mw-vhmbgr--navigation mw-header-menu-6">
                    <module type="menu" name="header_menu" id="header_menu-{{ $params['id'] }}12" template="navbar"/>
                    <module type="social_links" name="header_menu_socials"
                            id="header_menu-socials-{{ $params['id'] }}12" template="skin-6"/>
                </div>
            </div>
        </div>
    </div>
</section>
