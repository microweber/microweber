@php
/*

type: layout

name: Misc 7

position: 7

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

<section class="section {{ $layout_classes }} edit safe-mode" field="layout-misc-skin-7-{{ $params['id'] }}" rel="module">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-misc-skin-7-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 d-flex flex-wrap">
                <div class="col-sm-4 cloneable element safe-mode">
                    <h5>{{ _lang("Space The Final Frontier") }}</h5>
                    <p>{{ _lang("But for many of us, it was that first time we saw a rain of fire from.") }}</p>
                </div>
                <div class="col-sm-8 row align-self-center text-center">
                    <div class="col-6 col-lg-3 mb-5 cloneable element safe-mode">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/Facebook2.png') }}" alt="">
                    </div>
                    <div class="col-6 col-lg-3 mb-5 cloneable element safe-mode">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/Google2.png') }}" alt="">
                    </div>
                    <div class="col-6 col-lg-3 mb-5 cloneable element safe-mode">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/Logitech.png') }}" alt="">
                    </div>
                    <div class="col-6 col-lg-3 mb-5 cloneable element safe-mode pe-0">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/Philips.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
