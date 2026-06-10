{{--
type: layout
name: Call to action 22
position: 22
categories: Call to Action
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-call-to-action-skin-22-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-8 col-lg-6 mx-auto text-center regular-mode">
                <h1 data-mwplaceholder="{{ _e('Enter title here') }}">Make a Reservation</h1>
                <p data-mwplaceholder="{{ _e('Enter title here') }}">Please fill the form below to make an online reservation</p>

                <module type="contact_form" template="skin-1" />
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
