<?php

/*

type: layout

name: Blog blog-pro

position: blog-pro

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

    <div class="container-fluid mw-layout-container no-element edit" field="layout-blog-skin-blog-pro-{{ $params['id'] ?? '' }}" rel="module">
        <div class="blog-title mt-3 mb-5">
            <h1>Our Latest Blog</h1>
        </div>
        <module type="posts" template="blog-pro"/>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</section>
