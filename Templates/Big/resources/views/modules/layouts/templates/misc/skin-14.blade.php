@php
/*
 
type: layout
 
name: Misc 14
 
position: 14
 
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

<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit" field="layout-misc-skin-14-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <h2 data-mwplaceholder="{{ _e('Enter title here') }}">Frequently asked questions</h2>
                <module type="accordion" template="default"/>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
