{{-- 
type: layout

name: Titles 4

position: 4

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
    <div class="mw-layout-container no-element container safe-mode edit" field="layout-titles-skin-4-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row text-center mb-5">
            <div class="col-lg-10 mx-auto text-left regular-mode">
                <h4 data-mwplaceholder="{{ __('Enter title here') }}">A memory warm and happy as a bird flew to me. <br>
                    Remind me of you and brighten my day.
                </h4>
                <p data-mwplaceholder="{{ __('Enter text here') }}">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</section>
