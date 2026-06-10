{{--
type: layout
name: Gallery 23
description: Gallery 23
categories: Gallery
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? 'p-t-70';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? 'p-b-70';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }}">

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container-fluid px-0 edit" field="layout-skin-23-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12">
                <module type="pictures" template="skin-3-guest"/>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
