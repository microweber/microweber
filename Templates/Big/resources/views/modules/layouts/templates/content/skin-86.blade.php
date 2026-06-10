@php
    /*

    type: layout

    name: Content 86 - Parallax

    position: 86

    categories: Content

    */
@endphp

<style>
    .text-info {
        color: var(--mw-primary-color);
    }
</style>

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

<section class="section mw-layout-parallax mw-layout-dark-background d-flex align-items-end justify-content-center">
    <module type="background" data-parallax="true" data-overlay-x="1" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" height="110px" id="spacer-layout--{{ $params['id'] }}-top" />

    <div class="mw-layout-container safe-mode no-element">
        <div class="container mw-layout-overlay-container {{ $layout_classes }} text-white edit" field="layout-content-skin-86-{{ $params['id'] }}" rel="module">
            <div class="row align-items-center py-5">
                <div class="col-lg-9 col-12">
                    <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="text-white mb-3">Become an <u class="text-info">event speaker?</u></h2>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}"> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut dolore</p>
                </div>
                <div class="col-lg-3 col-12 ms-lg-auto mt-4 mt-lg-0">
                    <module type="btn" button_style="btn-primary" text="Register Today"/>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" height="110px" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
