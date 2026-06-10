<?php

/*

type: layout

name: Blog 22

position: 22

categories: Blog

*/

?>

<style>
    .mw-blog-22-avatar-image-wrapper img {
        border-radius: 100px;
        width: 160px !important;
        height: 160px !important;
        object-fit: cover;
    }

    .section-title-wrap {
        background: var(--mw-primary-color);
        border-radius: 10px;
        padding: 10px 30px;
    }
</style>

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

<section class="section projects {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />
    <div class="container mw-layout-container no-element edit" field="layout-blog-skin-22-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-12 ms-auto">
                <div class="section-title-wrap d-flex justify-content-center align-items-center mb-4 mw-blog-22-avatar-image-wrapper background-color-element element">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/white-desk-work-study-aesthetics.jpg') }}" alt="" />
                    <h2 data-mwplaceholder="@php _e('Enter title here'); @endphp" class="text-white ms-4 mb-0">Projects</h2>
                </div>
            </div>
            <div class="clearfix"></div>
            <module type="posts" template="skin-23" />
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</section>
