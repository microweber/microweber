@php
/*

type: layout

name: Contacts 5

position: 5

categories: Contact Us

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = '';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode " field="layout-contacts-skin-5-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row text-center">
            <div class="mx-auto col-sm-6 col-md-4 cloneable element background-color-element allow-select regular-mode">
                <i class="mw-micon-Map-Marker2 safe-element" style="font-size: 40px;"></i>
                <p class="mt-3" data-mwplaceholder="{{ _e('Enter text here') }}">6100 Hackett Plain Suite 705 <br/>Palo Alto, CA</p>
            </div>

            <div class="mx-auto col-sm-6 col-md-4 cloneable element background-color-element allow-select regular-mode">
                <i class="mw-micon-Email safe-element" style="font-size: 40px;"></i>
                <p class="mt-3 d-block safe-mode element">info@company.com</p>
            </div>

            <div class="mx-auto col-sm-6 col-md-4 cloneable element background-color-element allow-select regular-mode">
                <i class="mw-micon-Smartphone-3 safe-element" style="font-size: 40px;"></i>
                <p class="mt-3" data-mwplaceholder="{{ _e('Enter text here') }}">+1 234 567 890</p>
            </div>
        </div>

        <br>

        <div class="col-12 col-lg-10 col-lg-8 mx-auto text-center allow-select regular-mode">
            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">There is a moment in the life of any aspiring astronomer that it is time to buy that first telescope.
                <br> It's exciting to think about setting up your own
            </h5>
            <br/><br/>
            <module type="social_links"/>
        </div>

    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />

</section>
