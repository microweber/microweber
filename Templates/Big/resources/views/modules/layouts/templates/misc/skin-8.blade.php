@php
/*

type: layout

name: Misc 8

position: 8

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
    <div class="mw-layout-container no-element container edit" field="layout-misc-skin-8-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col cloneable element safe-mode align-self-center">
                <img loading="lazy" src="{{ asset('templates/big/img/layouts/Amazon2.png') }}" alt="">
            </div>
            <div class="col cloneable element safe-mode">
                <img loading="lazy" src="{{ asset('templates/big/img/layouts/Facebook2.png') }}" alt="">
            </div>
            <div class="col cloneable element safe-mode">
                <img loading="lazy" src="{{ asset('templates/big/img/layouts/Google2.png') }}" alt="">
            </div>
            <div class="col cloneable element safe-mode">
                <img loading="lazy" src="{{ asset('templates/big/img/layouts/LinkedIn2.png') }}" alt="">
            </div>
            <div class="col cloneable element safe-mode">
                <img loading="lazy" src="{{ asset('templates/big/img/layouts/Logitech.png') }}" alt="">
            </div>
            <div class="col cloneable element safe-mode">
                <img loading="lazy" src="{{ asset('templates/big/img/layouts/Philips.png') }}" alt="">
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
