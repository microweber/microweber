<?php
/*
type: layout
name: Feature 8
position: 8
categories: Features
*/
?>

<?php
$classes['padding_top'] = $classes['padding_top'] ?? '';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? '';

$layout_classes = $layout_classes ?? ''; 
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>

<style>
    .hover-bg-body:hover .bg-body {
        background: #0055ff !important;
        color: #fff;
    }
</style>

<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-feature-skin-8-{{ $params['id'] }}" rel="module">
        <div class="row text-center mb-5">
            <div class="col-12 col-lg-10 col-lg-8 mx-auto regular-mode">
                <h1 data-mwplaceholder="<?php _e('Enter title here'); ?>">Some Extra Cool Title <br> You Are Going To Write Here</h1>
                <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Many of us are incredibly lucky to be doing what we love now.</p>
            </div>
        </div>

        <div class="text-center text-sm-start row justify-content-center mb-3 py-md-4">
            <div class="col-lg-3 col-md-6 col-12 mb-3 cloneable element safe-mode background-color-element">
                <div class="h-100 d-flex flex-column border hover-bg-body p-4">
                    <div class="regular-mode">
                        <h5 data-mwplaceholder="<?php _e('Enter title here'); ?>">Feature Title</h5>
                        <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Many of us are incredibly lucky to be doing what we love now.</p>
                    </div>
                    <module type="btn" button_style="btn-outline-primary" button_size="btn-md" text="Learn More"/>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-3 cloneable element safe-mode background-color-element">
                <div class="h-100 d-flex flex-column border hover-bg-body p-4">
                    <div class="regular-mode">
                        <h5 data-mwplaceholder="<?php _e('Enter title here'); ?>">Feature Title</h5>
                        <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Many of us are incredibly lucky to be doing what we love now.</p>
                    </div>
                    <module type="btn" button_style="btn-outline-primary" button_size="btn-md" text="Learn More"/>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-3 cloneable element safe-mode background-color-element">
                <div class="h-100 d-flex flex-column border hover-bg-body p-4">
                    <div class="regular-mode">
                        <h5 data-mwplaceholder="<?php _e('Enter title here'); ?>">Feature Title</h5>
                        <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Many of us are incredibly lucky to be doing what we love now.</p>
                    </div>
                    <module type="btn" button_style="btn-outline-primary" button_size="btn-md" text="Learn More"/>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-3 cloneable element safe-mode background-color-element">
                <div class="h-100 d-flex flex-column border hover-bg-body p-4">
                    <div class="regular-mode">
                        <h5 data-mwplaceholder="<?php _e('Enter title here'); ?>">Feature Title</h5>
                        <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Many of us are incredibly lucky to be doing what we love now.</p>
                    </div>
                    <module type="btn" button_style="btn-outline-primary" button_size="btn-md" text="Learn More"/>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
