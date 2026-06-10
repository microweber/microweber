@php
/*
 
type: layout
 
name: Price Lists 4
 
position: 4
 
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

<section class="section {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-price-lists-skin-4-{{ $params['id'] }}" rel="module">
        <div class="row d-flex py-4">
            <h3 class="text-center allow-select regular-mode py-5">Our menu</h3>
            <div class="col-lg-4 col-md-6 allow-select">
                <div class="cloneable element regular-mode p-4">
                    <h5 class="py-2 cloneable element safe-mode safe-element">Buttermilk Flapjacks — 8€</h5>
                    <p class="cloneable element safe-mode">Two flapjacks served with molasses and our signature sassafras-infused whipped cream.</p>
                </div>

                <div class="cloneable element regular-mode p-4 ">
                    <h5 class="py-2 cloneable element safe-mode safe-element">Hotcakes & Sausage — 9€</h5>
                    <p class="cloneable element safe-mode">Cornmeal and molasses hotcakes served with sweet fennel pork sausage.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 allow-select">
                <div class="cloneable element regular-mode p-4">
                    <h5 class="py-2 cloneable element safe-mode safe-element">Sourdough Biscuits — 8€</h5>
                    <p class="cloneable element safe-mode">Four small biscuits served with boysenberry and blackberry compote and rosemary lard or apple maple butter.</p>
                </div>

                <div class="cloneable element regular-mode p-4">
                    <h5 class="py-2 cloneable element safe-mode safe-element">Baked Eggs & Bacon — 6€</h5>
                    <p class="cloneable element safe-mode">Cage-free eggs, house-smoked bacon, and spinach baked in an iron skillet and topped with aged cheddar. Served with two pieces of cowboy fry bread.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 allow-select">
                <div class="cloneable element regular-mode p-4">
                    <h5 class="py-2 cloneable element safe-mode safe-element">Grits & Strawberries — 9€</h5>
                    <p class="cloneable element safe-mode">Corn grits and local beans known as Arizona Strawberries. Served with onion, garlic, poblano, and ham gravy.</p>
                </div>

                <div class="cloneable element regular-mode p-4">
                    <h5 class="py-2 cloneable element safe-mode safe-element">Scrapple Scramble — 9€</h5>
                    <p class="cloneable element safe-mode">Cornmeal-based pork scrapple cut into chunks and scrambled with cage-free eggs. Served with a fresh tomato and two sourdough biscuits.</p>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
