<?php

/*

type: layout

name: Blog 25

position: 25

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
    <div class="mw-layout-container container no-element edit" field="layout-blog-skin-25-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 mb-4">
                <div class="section-heading text-center mb-7">
                    <h4 data-mwplaceholder="@php _e('Enter title here'); @endphp">Best Weekly Offers In Each City</h4>
                    <p data-mwplaceholder="@php _e('Enter text here'); @endphp">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.</p>
                </div>
            </div>

            <module type="posts" template="skin-26" />
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</section>
