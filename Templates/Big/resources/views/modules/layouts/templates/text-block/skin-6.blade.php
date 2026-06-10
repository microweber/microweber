{{-- 
type: layout
name: Text block 6
position: 6
categories: Text block
--}}

@php
$classes['padding_top'] = $classes['padding_top'] ?? '';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container safe-mode edit" field="layout-text-block-skin-6-{{ $params['id'] }}" rel="module">
        <div class="row text-center">
            <div class="col-10 mx-auto regular-mode">
                <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Like rock stars, asteroids have been given their fair share of urban myth and lore. Many have attributed the extinction of the dinosaurs to the impact of a huge asteroid on the earth.</p>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
