{{--
 type: layout
 name: Design 9
 position: 109
 categories: Design
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="{{ $layout_classes }} section mw-new-layouts-9">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>

    <div class="container mw-layout-container no-element edit safe-mode text-center"
         field="layout-new-layouts-skin-9-{{ $params['id'] }}" rel="module">

        <h6 class="mb-5" data-mwplaceholder="{{ _e('Enter title here') }}">Trusted by over 350 companies around the world and growing</h6>

        <module type="pictures" template="sliding-skin"/>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>
