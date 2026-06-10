{{-- 
type: layout

name: Ecommerce 12

position: 12

categories: Ecommerce
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }}">
    <module type="background" data-background-color="#FCF3EC" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    
    <div class="mw-layout-container no-element container-fluid edit" field="layout-ecommerce-skin-12-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-xl-10 justify-content-center align-items-center text-center mx-auto">
                <h3 class="my-3">We Provide Many Types Of Course</h3>
                <p class="mb-5">Price pattern glossy waistine ensemnle trend pumps petticoat sewing pretportrt <br> value young availability original hondbong influence</p>

                <module type="shop/products" template="skin-12"/>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
