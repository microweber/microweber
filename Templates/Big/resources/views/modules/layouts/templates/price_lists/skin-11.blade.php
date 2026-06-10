@php
/*

type: layout

name: Price Lists 11

position: 11

categories: Price Lists

*/
@endphp

@if (!isset($classes['padding_top']))
    @php $classes['padding_top'] = ''; @endphp
@endif
@if (!isset($classes['padding_bottom']))
    @php $classes['padding_bottom'] = ''; @endphp
@endif

@php
$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section price-list-11 {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="container allow-drop edit safe-mode" field="layout-skin-11-{{ $params['id'] }}" rel="module">
        <div class="row text-center">
            <p>TASTY AND HEALTHY</p>
            <h2>OUR MENU</h2>
            <p style="text-align-last: center; text-align: justify !important;">Our menu is prepared by the special advice of Cheff Manchev. All the products we use to prepare the dishes are environmentally friendly, healthy and delicious</p>
        </div>

        <div class="row menu">
            <div class="col-md-6 item cloneable element regular-mode">
                <div class="title"><span class="safe-element">Mussales soup</span></div>
                <div class="price">$ 23.00</div>
                <p>Lorem Ipsum has been the industry's standard dummy text</p>
            </div>

            <div class="col-md-6 item cloneable element regular-mode">
                <div class="title"><span class="safe-element">Sicilian meatballs</span></div>
                <div class="price">$ 25.00</div>
                <p>Lorem Ipsum has been the industry's standard dummy text</p>
            </div>

            <div class="col-md-6 item cloneable element regular-mode">
                <div class="title"><span class="safe-element">ITALIAN SPAGHETTI</span></div>
                <div class="price">$ 12.00</div>
                <p>Lorem Ipsum has been the industry's standard dummy text</p>
            </div>

            <div class="col-md-6 item cloneable element regular-mode">
                <div class="title"><span class="safe-element">SEAFOOD SALAD</span></div>
                <div class="price">$ 17.00</div>
                <p>Lorem Ipsum has been the industry's standard dummy text</p>
            </div>

            <div class="col-md-6 item cloneable element regular-mode">
                <div class="title"><span class="safe-element">BEEF BURGER</span></div>
                <div class="price">$ 10.00</div>
                <p>Lorem Ipsum has been the industry's standard dummy text</p>
            </div>

            <div class="col-md-6 item cloneable element regular-mode">
                <div class="title"><span class="safe-element">ROAST CHICKEN</span></div>
                <div class="price">$ 23.00</div>
                <p>Lorem Ipsum has been the industry's standard dummy text</p>
            </div>

            <div class="col-md-6 item cloneable element regular-mode">
                <div class="title"><span class="safe-element">STUFFED STRAWBERRY</span></div>
                <div class="price">$ 15.00</div>
                <p>Lorem Ipsum has been the industry's standard dummy text</p>
            </div>

            <div class="col-md-6 item cloneable element regular-mode">
                <div class="title"><span class="safe-element">GRILLED FISH</span></div>
                <div class="price">$ 37.00</div>
                <p>Lorem Ipsum has been the industry's standard dummy text</p>
            </div>
        </div>

        <div class="mt-5 text-center">
            <module type="btn" button_text="SEE OUR MENU" />
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
