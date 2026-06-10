<?php

/*

type: layout

name: Blog 16

position: 16

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

    <div class="container-fluid mw-layout-container no-element edit" field="layout-blog-skin-16-{{ $params['id'] ?? '' }}" rel="module">
        <div class="col-xl-10 mx-auto">
            <div class="mx-auto text-center mb-8">
                <h1 class="mb-3" style="font-size: 42px; color: #181E4E;">Our Latest Episodes</h1>
            </div>
            <module type="posts" template="skin-16" />

            <div class="d-flex justify-content-center">
                <module type="btn" button_text="All Episodes" />
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</section>
