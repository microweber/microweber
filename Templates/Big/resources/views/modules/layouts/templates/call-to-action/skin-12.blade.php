{{--
type: layout
name: Call to action 12
position: 12
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
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-call-to-action-skin-12-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-12 col-lg-12 py-2 d-block d-lg-flex justify-content-between align-items-center">
                <div class="col-md-4 py-4 text-center text-lg-start mt-4 pt-5 regular-mode">
                    <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="m-0">Leave your details and we will
                        <br> call you</h5>
                </div>

                <div class="col-md-8 py-4 text-center text-lg-end">
                    <module type="contact_form" template="subscribe-6"/>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
