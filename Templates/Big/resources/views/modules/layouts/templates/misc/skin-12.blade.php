@php
/*

type: layout

name: Misc 12

position: 12

categories: Misc

*/
@endphp

@if (!isset($classes['padding_top']))
    @php $classes['padding_top'] = 'mw-p-t-70'; @endphp
@endif
@if (!isset($classes['padding_bottom']))
    @php $classes['padding_bottom'] = 'mw-p-b-70'; @endphp
@endif

@php
$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="misc-12 d-flex {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container-fluid edit" field="layout-skin-12-{{ $params['id'] }}" rel="module">
        <div>
            <module type="accordion" template="misc-12"/>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
