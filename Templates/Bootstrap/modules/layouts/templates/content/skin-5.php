<?php

/*

type: layout

name: Content 5

position: 5

categories: Content

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


<section class="section <?php print $layout_classes; ?> edit safe-mode  " field="layout-content-skin-5-<?php print $params['id'] ?>" rel="module">
    <div class="container my-5">
        <div class="row p-4 pb-0 pe-lg-0 pt-lg-5 align-items-center rounded-3 border shadow-lg">
            <div class="col-lg-7 p-3 p-lg-5 pt-lg-3">
                <h1 class="display-4 fw-bold lh-1">Border hero with cropped image and shadows</h1>
                <p class="lead">Quickly design and customize responsive mobile-first sites with Bootstrap, the world’s most popular front-end open source toolkit, featuring Sass variables and mixins, responsive grid system, extensive prebuilt components, and powerful JavaScript plugins.</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-4 mb-lg-3">
                    <module type="btn" button_style="btn btn-primary px-4 me-md-2 fw-bold" button_text="Primary button"/>
                    <module type="btn" button_style="btn btn-outline-secondary px-4" button_text="Secondary"/>
                </div>
            </div>
            <div class="col-lg-4 offset-lg-1 p-0 overflow-hidden shadow-lg">
                <img  class="rounded-lg-3" src="<?php print template_url(); ?>assets/img/bootstrap5/bootstrap-docs.png" width="720" alt="" >

            </div>
        </div>
    </div>
</section>
