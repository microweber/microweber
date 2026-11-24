{{--
  type: layout
  content_type: static
  name: Menu - skin-9
  position: 9
  description: Menu - skin-9
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

{{-- Shared sticky underline-nav chrome now lives in the framework component
     mw-nav.css under the dedicated .mw-menu-underline-nav class. The --thick
     modifier gives this skin its 2px link underline (vs the 1px default on
     skin-3/10). Kept off the generic .mw-menu-skin-com hook so the active store
     header (skin-1) is never restyled. --}}
<section class="header-background px-0 mw-menu-skin-com mw-menu-underline-nav mw-menu-underline-nav--thick" id="mw-header-background">
    <script>
        addEventListener('DOMContentLoaded', e => {
            mw.MWSiteMobileMenu({
                threshold: 1280,
                size: '25px',
                color: 'var(--primaryColor)',
            }, 5);
        });
    </script>

    <div class="container-fluid">
        <x-row class="col-12 d-flex justify-content-md-between justify-content-center align-items-center order-1">
            <module type="logo" id="header-logo-{{ $params['id'] }}"
                    class="col-auto mw-big-header-logo w-auto align-self-center my-md-0 my-1 module module-logo "/>

            <div
                class="menu-header-skin-1 col-sm-auto col-12 d-flex align-items-center gap-4 justify-content-center ms-md-auto">
                <div class="mw-vhmbgr--navigation">
                    <module type="menu" name="header_menu" id="header_menu-{{ $params['id'] }}12" template="navbar"/>
                </div>
                <module type="multilanguage" template="only_flag"/>
            </div>
        </x-row>
    </div>
</section>
