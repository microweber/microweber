{{--
type: layout
name: Text block 11
position: 11
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
    <div class="mw-layout-container no-element container safe-mode edit " field="layout-text-block-skin-11-{{ $params['id'] }}" rel="module">
        <div class="row text-center">
            <div class="mw-default-layout-background-text col-12 regular-mode mx-auto allow-select">
                <p data-mwplaceholder="<?php _e('Enter text here'); ?>" class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
