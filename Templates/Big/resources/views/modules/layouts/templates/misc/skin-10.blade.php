@php
/*

type: layout

name: Misc 10

position: 10

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
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-misc-skin-10-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-sm-8 mx-auto">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="text-center">{{ _lang("How To Look Up") }}.</h3>
                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="text-center font-weight-bold">{{ _lang("Usage of the Internet is becoming more common due to rapid advancement of technology and the power of globalization.") }}</p>
            </div>
            <div class="row text-center py-5">
                <div class="col-6 col-lg-2 me-auto cloneable element safe-mode align-self-center">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/Amazon2.png') }}" alt="">
                </div>
                <div class="col-6 col-lg-2 mx-auto cloneable element safe-mode">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/Facebook2.png') }}" alt="">
                </div>
                <div class="col-6 col-lg-2 mx-auto cloneable element safe-mode">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/Google2.png') }}" alt="">
                </div>
                <div class="col-6 col-lg-2 mx-auto cloneable element safe-mode">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/LinkedIn2.png') }}" alt="">
                </div>
                <div class="col-6 col-lg-2 mx-auto cloneable element safe-mode">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/Logitech.png') }}" alt="">
                </div>
            </div>
            <div class="text-center">
                <module type="btn" text="Learn More" button_style="btn-primary" button_size=" " />
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
