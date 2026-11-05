{{--
  type: layout
  content_type: static
  name: Menu - skin-5
  position: 5
  description: Menu - skin-5
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
    .mw-skin-5-header-background {
        /* Styles for the header background */
    }

    .nav-link {
        opacity: .6;
        font-weight: bold;
    }

    .nav-link::after {
        content: "\F012F";
        font: normal normal normal 18px / 1 "Material Design Icons";
        display: inline-block;
        margin-left: 10px;
        color: var(--mw-primary-color);
        opacity: 0;
        transition: opacity 0.3s, transform 0.3s;
        transform: translateY(10px);
    }

    .nav-link:hover::after,
    .nav-link:focus::after,
    .nav-link:active::after,
    .nav-link.active::after {
        opacity: 1;
        transform: translateY(0);
    }

    .nav-link:hover,
    .nav-link:focus,
    .nav-link:active,
    .nav-link.active {
        opacity: 1;
    }
</style>

<script>
    $(window).on('load resize', function () {
        var headerHeight = $('.mw-skin-5-header-background').outerHeight();
        var viewportHeight = $(window).height();
        var newVideoContainerHeight = viewportHeight - headerHeight;
        $('#mw-menu-5-video-container').css('height', newVideoContainerHeight + 'px');
    });
</script>

<section class="section mw-layout-dark-background">
    <module type="background" data-background-video="{{ template_url() }}video/layouts/content-video-1.mp4"
            id="background-layout--{{ $params['id'] }}"/>
    <div id="mw-menu-5-video-container"
         class="container mw-layout-container d-flex align-items-center justify-content-center no-element edit"
         field="layout-skin-menu-video-5-{{ $params['id'] }}" rel="global">
        <div class="regular-mode">
            <h2 data-mwplaceholder="{{ __('Enter title here') }}" class="header-section-title fx-deactivate">Your
                Awesome Title Here</h2>
            <p data-mwplaceholder="{{ __('Enter text here') }}" class="header-section-p fx-deactivate">Leave application
                now and get -20% discount <br/>for your first repair</p>
        </div>
    </div>
</section>

<section class="header-background shadow-lg mw-header-sticky-nav mw-skin-5-header-background px-0"
         id="mw-header-background">
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
        <x-row class="col-12 d-flex justify-content-center">
            <module type="logo" id="header-logo-{{ $params['id'] }}"
                    class="me-auto col-auto col-xl-4 mw-big-header-logo w-auto align-self-center my-md-0 my-1 px-0 module module-logo "/>

            <div class="menu-header-skin-1 col-auto d-flex align-items-center justify-content-end">
                <div class="mw-vhmbgr--navigation">
                    <module type="menu" name="header_menu" id="header_menu-{{ $params['id'] }}13" template="navbar"/>
                </div>
            </div>
        </x-row>
    </div>
</section>
