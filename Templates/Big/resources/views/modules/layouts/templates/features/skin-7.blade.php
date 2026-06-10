<?php
/*
type: layout
name: Feature 7
position: 7
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
    <div class="mw-layout-container no-element container edit" field="layout-feature-skin-7-{{ $params['id'] }}" rel="module">
        <div class="row text-center mb-5">
            <div class="col-12 col-lg-10 col-lg-10 mx-auto regular-mode">
                <h3 data-mwplaceholder="<?php _e('Enter title here'); ?>">Title Here</h3>
                <p data-mwplaceholder="<?php _e('Enter text here'); ?>">In addition to the 2.0.3 install, you should be aware that some bugs have already been found, and that a plugin will need to be installed to repair those bugs. If you modify any of the files</p>
            </div>
        </div>

        <div class="row d-flex justify-content-center text-center text-md-start">
            <div class="col-sm-10 col-md-6 col-lg-4 mb-4 cloneable element background-color-element">
                <div class="h-100 d-flex flex-column border p-5">
                    <div class="d-block d-sm-flex align-items-center">
                        <div>
                            <i class="mb-4 d-inline-block safe-element no-typing mw-micon-Apple" style="font-size: 40px;"></i>
                            <div class="regular-mode">
                                <h5 data-mwplaceholder="<?php _e('Enter title here'); ?>">Feature Title</h5>
                                <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Before we discuss all of the things that could be affecting your.</p>
                                <module type="btn" text="Learn More" button_style="btn-primary" button_size=" "/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-10 col-md-6 col-lg-4 mb-4 cloneable element background-color-element">
                <div class="h-100 d-flex flex-column border p-5">
                    <div class="d-block d-sm-flex align-items-center">
                        <div>
                            <i class="mb-4 d-inline-block safe-element no-typing mw-micon-Alien-2" style="font-size: 40px;"></i>
                            <div class="regular-mode">
                                <h5 data-mwplaceholder="<?php _e('Enter title here'); ?>">Feature Title</h5>
                                <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Before we discuss all of the things that could be affecting your.</p>
                                <module type="btn" text="Learn More" button_style="btn-primary" button_size=" "/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
