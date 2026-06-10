{{--
type: layout
name: Call to action 19
position: 19
hidden: true
categories: Call to Action
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section form-control-outline-dark {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-call-to-action-skin-19-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 mx-auto text-center regular-mode">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}">The Amazing Hubble</h3>
                <p data-mwplaceholder="{{ _e('Enter title here') }}">Have you ever finally just gave in to the temptation and read your horoscope in the newspaper on Sunday morning? Sure, we all have.</p>
            </div>
        </div>

        <div><br /></div>

        <div class="row">
            <div class="col-12 mx-auto text-center">
                <module type="contact_form" template="subscribe-6"/>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
