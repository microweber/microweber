{{--
type: layout
name: Design 23
position: 123
categories: Design
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-23"
    container-class="mw-layout-container no-element"
>
    <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] }}-top"/>

        <div class="container mw-layout-container no-element edit safe-mode" field="layout-new-layouts-skin-23-{{ $params['id'] }}" rel="module">
            <module type="tabs" template="skin-1"/>
        </div>
        <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</x-layout-section>
