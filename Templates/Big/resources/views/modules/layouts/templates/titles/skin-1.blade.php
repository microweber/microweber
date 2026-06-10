{{-- 
type: layout

name: Titles 1

position: 1

categories: Titles
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .titles-1 .mw-breadcrumb {
        justify-content: center;
    }
</style>

<section class="section titles-1 {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />
    <div class="mw-layout-container no-element container safe-mode edit" field="layout-titles-skin-1-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row text-center mb-5">
            <div class="col-lg-10 mx-auto regular-mode text-center">
                <module type="breadcrumb"/>
                <h1 data-mwplaceholder="{{ __('Enter title here') }}" class="mb-3">Design Concept</h1>
                <p data-mwplaceholder="{{ __('Enter text here') }}">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</section>
