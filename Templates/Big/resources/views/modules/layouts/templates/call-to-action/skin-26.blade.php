{{--
type: layout
name: Call to action 26
position: 26
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
    <div class="mw-layout-container no-element container-fluid edit safe-mode" field="layout-call-to-action-skin-26-{{ $params['id'] }}" rel="module">
        <div class="col-xl-10 justify-content-center mx-auto element background-color-element p-7" style="background-color: #c5e0d8;">
            <div class="row d-flex align-items-center justify-content-between">
                <div class="col-12 col-lg-8 text-center text-lg-start mx-auto regular-mode">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}" style="color: #ffffff;">Get Free Marketing Analysis </h3>
                    <p data-mwplaceholder="{{ _e('Enter title here') }}" style="color: #ffffff;">Amet minim mallit non desnit Lorem Ipsum ast sit aliqua dolor do amet sit velit lorem ipsum velit</p>
                </div>

                <div class="col-12 col-lg-4">
                    <module type="btn" button_style="btn btn-primary w-100" button_text="Contact" />
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
