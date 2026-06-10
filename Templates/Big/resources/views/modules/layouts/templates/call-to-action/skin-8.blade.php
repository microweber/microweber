{{--
type: layout
name: Call to action 8
position: 8
hidden: true
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
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-call-to-action-skin-8-{{ $params['id'] }}" rel="module">
        <div class="row d-flex justify-content-between">
            <div class="col-12 col-sm-10 col-lg-5 text-center text-lg-start regular-mode">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Space The Final Frontier</h3>
            </div>

            <div class="col-12 col-sm-10 col-lg-6 d-flex align-items-center justify-content-lg-end justify-content-center mt-2 mt-sm-0 mx-auto">
                <module type="btn" button_style="btn-outline" text="Button" class="ms-3"/>
                <module type="btn" button_style="btn-primary" text="Button" class="ms-3"/>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
