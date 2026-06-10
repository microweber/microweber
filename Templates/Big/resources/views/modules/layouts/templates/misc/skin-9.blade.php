@php
/*

type: layout

name: Misc 9

position: 9

categories: Misc

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

<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-misc-skin-9-{{ $params['id'] }}" rel="module">
        <div class="row col-12 py-5 text-center justify-content-center">
            <div class="col-6 col-lg-4 mb-5 cloneable element safe-mode background-color-element align-self-center">
                <img loading="lazy" src="{{ asset('templates/big/img/layouts/Amazon2.png') }}" alt="">
                <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="pt-5">{{ _lang("Heading One") }}</h5>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">{{ _lang("Sony laptops are among the most well-known laptops on today's market.") }}</p>
            </div>
            <div class="col-6 col-lg-4 mb-5 cloneable element safe-mode background-color-element">
                <img loading="lazy" src="{{ asset('templates/big/img/layouts/Google2.png') }}" alt="">
                <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="pt-5">{{ _lang("Heading Two") }}</h5>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">{{ _lang("Once the printer ink runs dry it has to be replaced with another.") }}</p>
            </div>
            <div class="col-6 col-lg-4 mb-5 cloneable element safe-mode background-color-element">
                <img loading="lazy" src="{{ asset('templates/big/img/layouts/Logitech.png') }}" alt="">
                <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="pt-5">{{ _lang("Heading Three") }}</h5>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">{{ _lang("Accessories: Here you can find the best computer accessory.") }}</p>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
