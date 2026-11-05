{{--
  type: layout
  content_type: static
  name: Menu - skin-8 no-settings
  position: 8 no-settings
  description: Menu - skin-8 no-settings
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

<script>
    addEventListener('DOMContentLoaded', e => {
        mw.MWSiteMobileMenu({
            threshold: 1280,
            size: '25px',
            color: 'var(--primaryColor)',
        }, 5);
    });
</script>

<style>
    @media (min-width: 1024px) {
        .menu-header-skin-8 {
            position: fixed;
            top: 50%;
            left: 4%;
            transform: translate(-50%, -50%);
            z-index: 100;
            background-color: transparent !important;

            .nav-link {
                display: flex;
                align-items: center;
                justify-content: center;
                color: #45505b;
                padding: 10px 18px;
                margin-bottom: 8px;
                transition: 0.3s;
                font-size: 15px;
                border-radius: 50px;
                background-color: var(--mw-header-background-color) !important;
                height: 56px;
                width: 100%;
                overflow: hidden;
                box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
            }
        }
    }
</style>

<div class="menu-header-skin-8 header-background d-flex justify-content-center ps-xl-2 ps-5">
    <div class="mw-vhmbgr--navigation">
        <module type="menu" name="header_menu" id="header_menu-{{ $params['id'] }}12" template="simple"/>
    </div>
</div>
