@php
/*
 
type: layout
 
name: Misc 1
 
position: 1
 
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
    .mw-ui-btn-nav.mw-ui-btn-nav-tabs.df {
        display: flex;
        flex-direction: column;
        width: 300px;
    }

    .mw-ui-btn.df {
        justify-content: start;
        margin-bottom: 5px;
        margin-right: 5px;
        border-radius: 0!important;
        height: 60px;
    }

    .mw-ui-btn.df i {
        margin-right: 10px;
    }
    .mw-ui-box {
        display: table-cell;
        border: none;
        box-shadow: none !important;
    }
</style>

<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit" field="layout-misc-skin-1-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12">
                <module type="tabs" default_content="1" class="tabs" class="d-flex flex-column"/>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
