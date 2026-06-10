{{--
type: layout
name: Call to action 5
position: 5
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
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-call-to-action-skin-5-{{ $params['id'] }}" rel="module">
        <div class="col-12 col-sm-10 col-lg-10 col-lg-7 mx-auto text-center regular-mode">
            <h1 data-mwplaceholder="{{ _e('Enter title here') }}">Your Title Here</h1>
            <br/>
            <p data-mwplaceholder="{{ _e('Enter title here') }}">Subscribe for our newsletter to receive information about the new stuff.</p>
        </div>

        <div><br/><br/><br/></div>

        <div class="row">
            <div class="col-12 col-sm-10 col-lg-10 col-lg-7 mx-auto text-center">
                <module type="contact_form" template="subscribe-7"/>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
