<?php
/*
type: layout
name: Feature 9
position: 9
categories: Features
*/
?>

<?php
$classes['padding_top'] = $classes['padding_top'] ?? '';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? '';

$layout_classes = $layout_classes ?? ''; 
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>

<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-feature-skin-9-{{ $params['id'] }}" rel="module">
        <div class="row mb-md-3 py-md-4 text-center text-sm-start d-flex justify-content-center justify-content-lg-between">
            <div class="col-sm-10 col-md-8 col-lg-5">
                <div class="py-md-5">
                    <div class="mb-md-6 cloneable element background-color-element safe-element regular-mode">
                        <h4 data-mwplaceholder="<?php _e('Enter title here'); ?>">Feature Title 1</h4>
                        <div class="mb-md-6 cloneable element background-color-element safe-element regular-mode">
                            <h5 data-mwplaceholder="<?php _e('Enter title here'); ?>">Your Awesome Title Here</h5>
                            <p data-mwplaceholder="<?php _e('Enter text here'); ?>" class="mb-0">Since the introduction of Virtual Game, it has been achieving great heights</p>
                        </div>
                        <div class="mb-md-6 cloneable element background-color-element safe-element regular-mode">
                            <h5 data-mwplaceholder="<?php _e('Enter title here'); ?>">Your Awesome Title Here</h5>
                            <p data-mwplaceholder="<?php _e('Enter text here'); ?>" class="mb-0">Since the introduction of Virtual Game, it has been achieving great heights</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-1 d-none d-lg-inline-block text-center cloneable element">
                <div class="border-end border-color-secondary border-width-2 h-100 mx-auto top-0 d-inline-block text-center w-auto"></div>
            </div>

            <div class="col-sm-10 col-md-8 col-lg-5">
                <div class="py-md-5">
                    <div class="mb-md-6 cloneable element background-color-element safe-element regular-mode">
                        <h4 data-mwplaceholder="<?php _e('Enter title here'); ?>">Feature Title 2</h4>
                        <div class="mb-md-6 cloneable element background-color-element safe-element regular-mode">
                            <h5 data-mwplaceholder="<?php _e('Enter title here'); ?>">Your Awesome Title Here</h5>
                            <p data-mwplaceholder="<?php _e('Enter text here'); ?>" class="mb-0">Since the introduction of Virtual Game, it has been achieving great heights</p>
                        </div>
                        <div class="mb-md-6 cloneable element background-color-element safe-element regular-mode">
                            <h5 data-mwplaceholder="<?php _e('Enter title here'); ?>">Your Awesome Title Here</h5>
                            <p data-mwplaceholder="<?php _e('Enter text here'); ?>" class="mb-0">Since the introduction of Virtual Game, it has been achieving great heights</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
