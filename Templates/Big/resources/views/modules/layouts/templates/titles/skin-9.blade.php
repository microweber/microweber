{{-- 
type: layout

name: Titles 9

position: 9

categories: Titles
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container safe-mode edit safe-mode" field="layout-titles-skin-9-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row mb-5">
            <div class="col-lg-12 mx-auto regular-mode">
                <h5 data-mwplaceholder="{{ __('Enter title here') }}" class="mb-3">Jump to the Top</h5>
                <p data-mwplaceholder="{{ __('Enter text here') }}">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                <module type="btn" button_style="btn-link" text="Read More" />
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</section>
