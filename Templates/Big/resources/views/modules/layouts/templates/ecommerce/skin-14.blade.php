{{-- 
type: layout

name: Ecommerce 14

position: 14

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
    
    <div class="mw-layout-container no-element container edit" field="layout-ecommerce-skin-14-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="section-heading">
                    <p>LATEST COURSES</p>
                    <h2>Latest Courses</h2>
                </div>
            </div>
            <module type="shop" template="skin-1"/>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
