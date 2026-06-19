{{--
  type: layout
  content_type: static
  name: Menu - skin-7
  position: 7
  description: Menu - skin-7
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
    .menu-skin-7 li.active a::after, .menu-skin-7 li:hover a::before {
        width: 20px;
        height: 2px;
        background-color: #fff;
        position: absolute;
        content: '';
        left: 15px;
        top: 2px;
        transition: all ease .7s;
    }

    .menu-skin-7 li {
        transition: all ease .7s;
    }

    .menu-skin-7 li a {
        position: relative;
        transition: all ease .7s;
    }
</style>

<section class="header-background px-0" id="mw-header-background">
    <script>
        addEventListener('DOMContentLoaded', e => {
            mw.MWSiteMobileMenu({
                threshold: 1280,
                size: '25px',
                color: 'var(--primaryColor)',
            }, 5);
        });
    </script>

    <div class="container-fluid menu-skin-7">
        <x-row class="col-12 d-flex justify-content-center">
            <module type="logo" id="header-logo-{{ $params['id'] }}"
                    class="me-auto col-auto col-xl-4 mw-big-header-logo w-auto align-self-center my-md-0 my-1 px-0 module module-logo "/>

            <div class="menu-header-skin-1 col-auto d-flex align-items-center justify-content-end">
                <div class="mw-vhmbgr--navigation">
                    <module type="menu" name="header_menu" id="header_menu-{{ $params['id'] }}12" template="navbar"/>
                </div>
            </div>
        </x-row>
    </div>
</section>
