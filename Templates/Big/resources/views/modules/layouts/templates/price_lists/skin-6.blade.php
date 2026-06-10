@php
/*

type: layout

name: Price Lists 6 - Parallax

position: 6

categories: Price Lists

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = '';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'p-b-70';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="{{ $layout_classes }} section pt-0 mw-layout-dark-background mw-layout-parallax ">
    <module type="background" data-background-image="{{ asset('templates/big/img/layouts/salads.jpg') }}" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element mh-450 container edit safe-mode" field="layout-price-lists-skin-6-{{ $params['id'] }}" rel="module">
        <div class="row py-4">
            <div class="allow-select regular-mode">
                <h3 class="text-center py-3">MAIN DISHES</h3>
                <p class="text-center">Try our specialities. <br> We go to a restaurant to try something delicious and different that can't cook at home.</p>
            </div>
            <div class="row d-flex">
                <div class="col-md-6 allow-select regular-mode">
                    <div class="cloneable element py-2">
                        <div class="py-2"><h6 class="safe-element">MUSSALES SOUP</h6></div>
                        <div class="d-flex"><p class="price-list-content col-8 safe-element px-0">Lorem Ipsum has been the industry's standard dummy text</p><span class="col-4 justify-content-end text-end text-right px-0">$ 23.00</span></div>
                        <hr class="price-list-hr">
                    </div>

                    <div class="cloneable element py-2">
                        <div class="py-2"><h6 class="safe-element">SICILIAN MEATBALLS</h6></div>
                        <div class="d-flex"><p class="price-list-content col-8 safe-element px-0">Lorem Ipsum has been the industry's standard dummy text</p><span class="col-4 justify-content-end text-end text-right px-0">$ 30.00</span></div>
                        <hr class="price-list-hr">
                    </div>

                    <div class="cloneable element py-2">
                        <div class="py-2"><h6 class="safe-element">ITALIAN SPAGHETTI</h6></div>
                        <div class="d-flex"><p class="price-list-content col-8 safe-element px-0">Lorem Ipsum has been the industry's standard dummy text</p><span class="col-4 justify-content-end text-end text-right px-0">$ 35.00</span></div>
                        <hr class="price-list-hr">
                    </div>
                </div>
                <div class="col-md-6 allow-select regular-mode">
                    <div class="cloneable element py-2">
                        <div class="py-2"><h6 class="safe-element">BEEF BURGER</h6></div>
                        <div class="d-flex"><p class="price-list-content col-8 safe-element px-0">Lorem Ipsum has been the industry's standard dummy text</p><span class="col-4 justify-content-end text-end text-right px-0">$ 23.00</span></div>
                        <hr class="price-list-hr">
                    </div>

                    <div class="cloneable element py-2">
                        <div class="py-2"><h6 class="safe-element">ROAST CHIKEN</h6></div>
                        <div class="d-flex"><p class="price-list-content col-8 safe-element px-0">Lorem Ipsum has been the industry's standard dummy text</p><span class="col-4 justify-content-end text-end text-right px-0">$ 30.00</span></div>
                        <hr class="price-list-hr">
                    </div>

                    <div class="cloneable element py-2">
                        <div class="py-2"><h6 class="safe-element">SEAFOOD SALAD</h6></div>
                        <div class="d-flex"><p class="price-list-content col-8 safe-element px-0">Lorem Ipsum has been the industry's standard dummy text</p><span class="col-4 justify-content-end text-end text-right px-0">$ 35.00</span></div>
                        <hr class="price-list-hr">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
