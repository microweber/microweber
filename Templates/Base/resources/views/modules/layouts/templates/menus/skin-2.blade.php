{{--
  type: layout
  content_type: static
  name: Menu - skin-2
  position: 2
  description: Menu - skin-2
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

    <div class="container-fluid px-md-5 justify-content-center">
        <x-row class="col-12 d-flex justify-content-center">
            <div class="menu-header-skin-1 col-auto d-flex flex-column align-items-center">
                <module type="logo" id="header-logo-{{ $params['id'] }}"
                        class="mx-auto mw-big-header-logo w-auto align-self-center my-md-0 my-1 module module-logo "/>
                <br>
                <div class="mw-vhmbgr--navigation">
                    <module type="menu" name="header_menu" id="header_menu-{{ $params['id'] }}12" template="navbar"/>
                </div>
            </div>
        </x-row>
    </div>
</section>
