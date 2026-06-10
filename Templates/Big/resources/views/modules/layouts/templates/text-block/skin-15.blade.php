{{-- 
type: layout
name: Text block 15
position: 15
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
    <div class="mw-layout-container no-element container safe-mode edit safe-mode" field="layout-text-block-skin-15-{{ $params['id'] }}" rel="module">
        <div class="row d-flex justify-content-center justify-content-md-between border-top border-bottom py-8 cloneable element regular-mode">
            <div class="col-4">
                <div>
                    <h6 data-mwplaceholder="<?php _e('Enter text here'); ?>"><span>1.</span> Is it easy to build a website?</h6>
                </div>
            </div>
            <div class="col-8">
                <div>
                    <p data-mwplaceholder="<?php _e('Enter text here'); ?>" class="mb-0">When you enter into any new area of science, you almost always find yourself with a baffling new language of technical terms to learn before you can converse with the experts.</p>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
