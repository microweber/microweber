{{--
type: layout
name: Design 24
position: 124
categories: Design
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="{{ $layout_classes }} section mw-new-layouts-24">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>

    <div class="container mw-layout-container no-element edit safe-mode" field="layout-new-layouts-skin-23-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12">
                <div class="mw-new-24-title-holder mb-4">
                    <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-24-title">Your Title Here</h2>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-new-24-subtitle">Your subtitle here</p>
                </div>

                <module type="posts" template="skin-22"/>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>
