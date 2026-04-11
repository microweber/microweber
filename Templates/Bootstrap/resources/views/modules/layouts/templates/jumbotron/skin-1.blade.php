<?php

/*

type: layout

name: Jumbotron 1

position: 1

categories: Jumbotron

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


<section class="section <?php print $layout_classes; ?> mw-layout-dark-background py-0 d-flex align-items-center justify-content-center edit safe-mode nodrop" field="layout-jumbotron-skin-1-{{ $params['id'] }}" rel="module">
    <module type="background" id="background-layout--{{ $params['id'] }}" data-background-color="#00000060" data-background-image="{{ asset('templates/bootstrap/img/hero.jpg') }}"/>
    <div class="mw-layout-container py-4 container mw-header-section-mh-100vh d-flex align-items-center justify-content-center">
        <div class="row text-center">
            <div class="col-12 mx-auto text-white">
                <h1 data-mwplaceholder="Enter title here" class="header-section-title mb-7">Describe your company</h1>
                <p data-mwplaceholder="Enter text here" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>
                <module type="btn" id="{{ $params['id'] }}-btn" button_style="btn-primary" button_size="btn-lg px-5" button_text="Call to action"/>
            </div>
        </div>
    </div>
</section>
