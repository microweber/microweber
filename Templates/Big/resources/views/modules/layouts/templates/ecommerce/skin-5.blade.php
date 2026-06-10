{{-- 
type: layout

name: Ecommerce 5

position: 5

categories: Ecommerce
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit" field="layout-ecommerce-skin-5-{{ $params['id'] }}" rel="module">
        <module type="shop/products" template="skin-4" slides-md="1" slides-lg="2" adaptive_height="false" arrows="true" pager="false" />
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
