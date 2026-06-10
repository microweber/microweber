{{--
type: layout
name: Gallery 21
description: Gallery 21
categories: Gallery
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? 'mw-p-t-0';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? 'mw-p-b-0';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="py-0 section {{ $layout_classes }}">

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container-fluid px-0 edit" field="layout-skin-21-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12">
                <module type="pictures" template="skin-12" style="padding: 0;"/>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
