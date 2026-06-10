@php
/*
 
type: layout
 
name: Misc 3
 
position: 3
 
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
    <div class="mw-layout-container no-element container edit" field="layout-misc-skin-3-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12">
                <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-bold text-center">The amazing hubble</h5>
                <h1 data-mwplaceholder="{{ _e('Enter text here') }}" class="text-center">To appreciate what is really exciting about radio astronomy, first we have to shift how we view astronomy.</h1>
                <div class="col-md-8 mx-auto mt-5">
                    <module type="accordion" template="skin-2"/>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
