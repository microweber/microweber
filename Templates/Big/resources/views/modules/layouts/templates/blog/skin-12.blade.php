<?php

/*

type: layout

name: Blog 12

position: 12

categories: Blog

*/

?>

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = '';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />

    <div class="container mw-layout-container no-element edit" field="layout-blog-skin-12-{{ $params['id'] ?? '' }}" rel="module">
        <module type="posts" template="skin-12" slides-md="2" slides-lg="3" slides-lg="4" adaptive_height="false" arrows="true" />
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</section>
