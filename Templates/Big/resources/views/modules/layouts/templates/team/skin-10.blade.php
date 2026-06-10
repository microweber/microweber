@php
/*
type: layout
name: Team 10
position: 10
categories: Team
*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = '';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'pb-0';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit" field="layout-team-skin-10-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="mb-5">
                <h3>Meet our Team</h3>
                <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. <br> Deserunt doloribus ducimus expedita labore non odit quibusdam repellendus sunt.</p>
            </div>
        </div>

        <module type="teamcard" template="skin-10"/>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
