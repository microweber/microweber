{{--
  type: layout
  content_type: static
  name: Menu - Menu with button
  position: 20
  description: Menu - Menu with button
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
    .mw-menu-skin-com-container ul li a {
        padding: 10px 20px;
    }
</style>

<section class="header-background px-0 mw-menu-skin-com" id="mw-header-background">
    <script>
        addEventListener('DOMContentLoaded', e => {
            mw.MWSiteMobileMenu({
                threshold: 1280,
                size: '25px',
                color: 'var(--mw-primary-color)',
            }, 5);
        });
    </script>

    <div class="container-fluid mw-menu-skin-com-container">
        <x-row class="col-12 d-flex justify-content-md-between justify-content-center align-items-center order-1">
            <module type="logo" id="header-logo-{{ $params['id'] }}"
                    class="col-auto mw-big-header-logo w-auto align-self-center my-md-0 my-1 module module-logo "/>

            <div
                class="menu-header-skin-1 col-sm-auto col-12 d-flex align-items-center justify-content-center order-lg-2 order-3">
                <div class="mw-vhmbgr--navigation">
                    <module type="menu" name="header_menu" id="header_menu-{{ $params['id'] }}12"
                            template="navbar"/>
                </div>
            </div>

            <div class="col-auto order-lg-3 order-2 ms-lg-0 ms-auto d-flex align-items-center gap-4">
                <module type="btn" button_style="btn btn-outline-primary theme-btn w-100" button_text="CREATE A WEBSITE"
                        id="header-btn-{{ $params['id'] }}"/>
            </div>
        </x-row>
    </div>
</section>
