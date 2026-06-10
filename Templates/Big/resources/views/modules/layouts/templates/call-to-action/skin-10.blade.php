{{--
type: layout
name: Call to action 10
position: 10
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
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-call-to-action-skin-10-{{ $params['id'] }}" rel="module">
        <div class="row d-flex justify-content-between">
            <div class="col-12 col-sm-10 col-lg-7 text-center text-lg-start regular-mode">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Start your free trial now, with a simple registration.</h3>
            </div>

            <div class="col-12 col-sm-10 col-lg-5 d-flex align-items-center justify-content-lg-end justify-content-center mt-2 mt-sm-0 mx-auto safe-mod">
                <module type="btn" button_style="btn-primary px-5" text="Button" class="ms-2"/>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
