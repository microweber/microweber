{{--
type: layout

name: Titles 6

position: 6

categories: Titles
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }}">
    <module type="background"   id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container safe-mode edit" field="layout-titles-skin-6-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row text-center my-5">
            <div class="col-lg-8 mx-auto regular-mode">
                <h2 data-mwplaceholder="{{ __('Enter title here') }}" class="mb-3">Back to the Earth</h2>
                <p data-mwplaceholder="{{ __('Enter text here') }}">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</section>
