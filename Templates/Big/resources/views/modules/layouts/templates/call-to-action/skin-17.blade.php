{{--
type: layout
name: Call to action 17
position: 17
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
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-call-to-action-skin-17-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-10 col-lg-7 mx-auto text-center regular-mode">
                <h1 data-mwplaceholder="{{ _e('Enter title here') }}">Sign Up</h1>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">Planning to visit Las Vegas or any other vacational resort where casinos Planning to visit Las Vegas or any other vacational resort where casinos </p>
            </div>
        </div>

        <div><br/><br/></div>

        <div class="row">
            <div class="col-12 col-sm-10 col-lg-8 col-lg-4 mx-auto safe-mode">
                <module type="contact_form" template="skin-2" />
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
