@php
/*
type: layout
name: Team 20
position: 20
categories: Team
*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = '';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .about-image {
        width: 100% !important;
        height: 100% !important;
        max-height: 635px;
        min-height: 475px;
        object-fit: cover;
    }

    .custom-links {
        max-width: 230px;
    }

    .custom-link {
        position: relative;
        overflow: hidden;
        z-index: 1;
        display: inline-block;
        transition: all .3s cubic-bezier(.645,.045,.355,1);
    }

    .custom-link::after {
        content: "";
        width: 0;
        height: 2px;
        bottom: 0;
        position: absolute;
        left: auto;
        right: 0;
        z-index: -1;
        transition: width .6s cubic-bezier(.25,.8,.25,1) 0s;
        background: currentColor;
    }

    .custom-link:hover::after {
        width: 100%;
        left: 0;
        right: auto;
    }

    .custom-link:hover,
    .custom-link:hover::after {
        color: #fff;
    }
</style>

<section class="section team-20 {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />

    <div class="mw-layout-container no-element edit" field="layout-team-skin-20-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-lg-3 col-12 p-0">
                <img class="about-image" loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-4.jpg') }}" alt="" />
            </div>

            <div class="col-lg-3 col-12 background-color-element element" style="background-color: #2b2b2b;">
                <div class="d-flex flex-column flex-wrap justify-content-center h-100 py-5 px-4 pt-lg-4 pb-lg-0">
                    <h4 class="text-white mb-3">We’re - idealists and strategic thinkers.</h4>
                    <p class="text-white">Over six years in the video business</p>
                    <div class="mt-3 custom-links">
                        <a class="text-white custom-link">Read News & Events</a>
                        <a class="text-white custom-link">Work with Us</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-12 p-0">
                <module type="teamcard" template="skin-19"/>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" height="70px" />
</section>
