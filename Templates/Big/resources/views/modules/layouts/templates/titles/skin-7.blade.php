{{--
type: layout

name: Titles 7 - Parallax

position: 7

categories: Titles
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section mw-layout-parallax" data-parallax="true" data-overlay-x="1">
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-7.jpg') }}" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" height="150px" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element safe-mode container edit regular-mode" field="layout-titles-skin-7-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row text-center">
            <div class="col-8 col-md-6 mx-auto background-color-element element" style="background-color: #FFFFFF; padding: 80px 50px">
                <h3 data-mwplaceholder="{{ __('Enter title here') }}">The future is here and belongs to you.</h3>
                <p data-mwplaceholder="{{ __('Enter text here') }}">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
            </div>
        </div>
    </div>
    <module type="spacer" height="150px" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
