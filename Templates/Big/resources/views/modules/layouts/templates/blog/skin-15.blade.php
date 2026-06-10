<?php

/*

type: layout

name: Blog 15

position: 15

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

<section class="section mw-layout-dark-background {{ $layout_classes }}">
    <module type="background" data-background-color="#000" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />

    <div class="container-fluid mw-layout-container no-element edit" field="layout-blog-skin-15-{{ $params['id'] ?? '' }}" rel="module">
        <div class="col-md-10 mx-auto">
            <div class="mx-auto text-center mb-5 d-lg-flex justify-content-between">
                <h1 class="mb-3" style="font-size: 64px;">My Project</h1>
                <div>
                    <p>Grab the opportunity to capture memories that you will treasure for
                        <br> a safetime. Why be ordinary when you can extraordinary?</p>

                    <div class="d-flex align-items-center justify-content-end cloneable">
                        <module type="btn" button_text="See All Moments ->" button_style="btn btn-link px-5 py-4 text-decoration-underline" />
                    </div>
                </div>
            </div>
            <module type="posts" template="skin-15" />
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</section>
