<?php

/*

type: layout

name: Features 1

position: 1

categories: Features

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = '';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? ''; $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>


<section class="section features-skin-2 <?php print $layout_classes; ?> edit safe-mode" field="layout-features-skin-1-{{ $params['id'] }}" rel="module">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="mw-layout-container container">
        <div class="row text-center safe-mode">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="regular-mode">
                    <h4 data-mwplaceholder="Enter title here">The Feature Title</h4>
                </div>
            </div>
        </div>

        <div class="row text-center mt-7">
            <div class="mx-auto col-md-6 col-lg-4 col-12 mb-5 cloneable element text-center safe-mode background-color-element">
                <i class="features-skin-2-icons mb-2 safe-element no-typing mw-micon-Add-User"></i>

                <div class="text-center mt-6 regular-mode">
                    <p data-mwplaceholder="Enter text here">To get started in learning how to observe the stars much better, there are some basic things.</p>
                </div>
                <div class="mt-md-4 mt-3">
                    <module type="btn" id="{{ $params['id'] }}-btn-1" button_style="btn-outline-primary" button_size="btn-md" button_text="Learn More"/>
                </div>
            </div>

            <div class="mx-auto col-md-6 col-lg-4 col-12 mb-5 cloneable element text-center safe-mode background-color-element">
                <i class="features-skin-2-icons mb-2 safe-element no-typing mw-micon-Add-UserStar"></i>

                <div class="text-center mt-6 regular-mode">
                    <p data-mwplaceholder="Enter text here">To get started in learning how to observe the stars much better, there are some basic things.</p>
                </div>
                <div class="mt-md-4 mt-3">
                    <module type="btn" id="{{ $params['id'] }}-btn-2" button_style="btn-outline-primary" button_size="btn-md" button_text="Learn More"/>
                </div>
            </div>

            <div class="mx-auto col-md-6 col-lg-4 col-12 mb-5 cloneable element text-center safe-mode background-color-element">
                <i class="features-skin-2-icons mb-2 safe-element no-typing mw-micon-Business-ManWoman"></i>

                <div class="text-center mt-6 regular-mode">
                    <p data-mwplaceholder="Enter text here">To get started in learning how to observe the stars much better, there are some basic things.</p>
                </div>
                <div class="mt-md-4 mt-3">
                    <module type="btn" id="{{ $params['id'] }}-btn-3" button_style="btn-outline-primary" button_size="btn-md" button_text="Learn More"/>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>
