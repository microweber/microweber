@php
/*
 
type: layout
 
name: Misc 11
 
position: 11
 
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
    <div class="mw-layout-container no-element container edit" field="layout-misc-skin-11-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-sm-8 mx-auto pb-5 text-center">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}">The Glossary Of Telescopes</h3>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">I can change any and everything in my life by simply changing myself. This puts me in the driving seat of my life and makes me responsible for my own journey.</p>
            </div>
            <div class="col-12 d-flex justify-content-center text-center pb-5">
                <module type="tabs" template="new" default_content="1" class="tabs" />
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
