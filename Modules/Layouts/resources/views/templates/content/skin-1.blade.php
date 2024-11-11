<?php

/*

type: layout

name: Content 1

position: 1

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


<section class="section <?php print $layout_classes; ?> edit safe-mode  " field="layout-content-skin-1-<?php print $params['id'] ?>" rel="module">
    <div class="px-4 py-5 my-5 text-center">
        <a class="mb-2" href=""><i class="mw-micon-Aerobics-3" style="font-size: 70px;"></i></a>
        <h1 class="display-5 fw-bold">Centered hero</h1>
        <div class="col-lg-6 mx-auto">
            <p class="lead mb-4">Quickly design and customize responsive mobile-first sites with Bootstrap, the world’s most popular front-end open source toolkit, featuring Sass variables and mixins, responsive grid system, extensive prebuilt components, and powerful JavaScript plugins.</p>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <module type="btn" button_style="btn btn-primary px-4 gap-3" button_text="Primary button"/>
                <module type="btn" button_style="btn btn-outline-secondary px-4" button_text="Secondary"/>
            </div>
        </div>
    </div>
</section>
