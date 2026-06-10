@php
/*
 
type: layout
 
name: Price Lists 5
 
position: 5
 
categories: Price Lists
 
*/
@endphp

@if (!isset($classes['padding_top']))
    @php $classes['padding_top'] = 'p-t-70'; @endphp
@endif
@if (!isset($classes['padding_bottom']))
    @php $classes['padding_bottom'] = 'p-b-70'; @endphp
@endif

@php
$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .prices-skin-5 .white-text {
        color: #FFFFFF;
    }
</style>

<section class="section {{ $layout_classes }} prices-skin-5 ">
    <module type="background" data-background-color="#2b2b2b" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-price-lists-skin-5-{{ $params['id'] }}" rel="module">
        <div class="row d-flex py-4">
            <h3 class="white-text text-center allow-select regular-mode py-5">Our menu</h3>
            <div class="col-lg-4 col-md-6 allow-select regular-mode ">
                <div class="cloneable element p-4">
                    <h5 class="py-2 cloneable element white-text safe-element">Buttermilk Flapjacks — 8€</h5>
                    <p class="white-text cloneable element">Two flapjacks served with molasses and our signature sassafras-infused whipped cream.</p>
                </div>
                <div class="cloneable element allow-select regular-mode p-4">
                    <h5 class="py-2 cloneable element white-text safe-element">Hotcakes & Sausage — 9€</h5>
                    <p class="white-text cloneable element">Cornmeal and molasses hotcakes served with sweet fennel pork sausage.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 allow-select regular-mode ">
                <div class="cloneable element p-4">
                    <h5 class="py-2 cloneable element white-text safe-element">Sourdough Biscuits — 8€</h5>
                    <p class="white-text cloneable element">Four small biscuits served with boysenberry and blackberry compote and rosemary lard or apple maple butter.</p>
                </div>
                <div class="cloneable element p-4">
                    <h5 class="py-2 cloneable element white-text safe-element">Baked Eggs & Bacon — 6€</h5>
                    <p class="white-text cloneable element">Cage-free eggs, house-smoked bacon, and spinach baked in an iron skillet and topped with aged cheddar. Served with two pieces of cowboy fry bread.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 allow-select regular-mode ">
                <div class="cloneable element p-4">
                    <h5 class="py-2 cloneable element white-text safe-element">Grits & Strawberries — 9€</h5>
                    <p class="white-text cloneable element">Corn grits and local beans known as Arizona Strawberries. Served with onion, garlic, poblano, and ham gravy.</p>
                </div>
                <div class="cloneable element p-4">
                    <h5 class="py-2 cloneable element white-text safe-element">Scrapple Scramble — 9€</h5>
                    <p class="white-text cloneable element">Cornmeal-based pork scrapple cut into chunks and scrambled with cage-free eggs. Served with a fresh tomato and two sourdough biscuits.</p>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
