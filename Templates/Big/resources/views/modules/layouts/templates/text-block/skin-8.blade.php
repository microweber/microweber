{{-- 
type: layout
name: Text block 8
position: 8
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
    <div class="mw-layout-container no-element container d-flex justify-content-center align-items-center safe-mode edit" field="layout-text-block-skin-8-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 regular-mode col-lg-10 col-lg-10 mx-auto">
                <div class="row">
                    <div class="col-md-6 mb-6 cloneable element safe-element">
                        <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Arrange your room for optimal picture and sound by reducing screen and hard surface reflections. Do not forget the TV picture is not very pretty when light is reflecting off the screen.</p>
                    </div>
                    <div class="col-md-6 mb-6 cloneable element safe-element">
                        <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Arrange your room for optimal picture and sound by reducing screen and hard surface reflections. Do not forget the TV picture is not very pretty when light is reflecting off the screen.</p>
                    </div>
                    <div class="col-md-6 mb-6 cloneable element safe-element">
                        <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Arrange your room for optimal picture and sound by reducing screen and hard surface reflections. Do not forget the TV picture is not very pretty when light is reflecting off the screen.</p>
                    </div>
                    <div class="col-md-6 mb-6 cloneable element safe-element">
                        <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Arrange your room for optimal picture and sound by reducing screen and hard surface reflections. Do not forget the TV picture is not very pretty when light is reflecting off the screen.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
