@php
/*
 
type: layout
 
name: Misc 16
 
position: 16
 
categories: Misc
 
*/
@endphp

@if (!isset($classes['padding_top']))
    @php $classes['padding_top'] = ''; @endphp
@endif
@if (!isset($classes['padding_bottom']))
    @php $classes['padding_bottom'] = ''; @endphp
@endif

@php
$layout_classes = $layout_classes ?? ''; 
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .text-info {
        color: var(--mw-primary-color);
    }
</style>

<section class="section misc-16 {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" height="120px" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit my-5" field="layout-misc-skin-16-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-lg-12 col-12">
                <h2 class="mb-5 text-center">Next <u class="text-info">Schedules</u></h2>
                <module type="tabs" template="skin-2"/>
            </div>
        </div>
    </div>
    <module type="spacer" height="120px" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
