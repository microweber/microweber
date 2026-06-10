@php
/*
 
type: layout
 
name: Price Lists 1
 
position: 1
 
categories: Price Lists
 
*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = 'p-t-70';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'p-b-70';
}

$layout_classes = $layout_classes ?? ''; 
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="{{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-price-lists-skin-1-{{ $params['id'] }}" rel="module">
        <div class="row py-4 allow-select">
            <h3 class="text-center py-5 regular-mode">Price List</h3>
            <div class="col-10 mx-auto regular-mode">
                <div class="cloneable element safe-mode py-4">
                    <div class="py-2"><h6 class="safe-element">Mustache shaping</h6></div>
                    <div class="d-flex"><span class="price-list-content col-8 safe-element px-0">Haircut and mustache shaping</span><span class="col-4 justify-content-end text-end text-right px-0">$ 23.00</span></div>
                    <hr class="price-list-hr">
                </div>
            </div>

            <div class="col-10 mx-auto regular-mode">
                <div class="cloneable element safe-mode py-4">
                    <div class="py-2"><h6 class="safe-element">Beard shaping</h6></div>
                    <div class="d-flex"><span class="price-list-content col-8 safe-element px-0">Beard trimming and shaping</span><span class="col-4 justify-content-end text-end text-right px-0">$ 30.00</span></div>
                    <hr class="price-list-hr">
                </div>
            </div>

            <div class="col-10 mx-auto regular-mode">
                <div class="cloneable element safe-mode py-4">
                    <div class="py-2"><h6 class="safe-element">Hairstyle styling</h6></div>
                    <div class="d-flex"><span class="price-list-content col-8 safe-element px-0">Haircuts and styling</span><span class="col-4 justify-content-end text-end text-right px-0">$ 35.00</span></div>
                    <hr class="price-list-hr">
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
