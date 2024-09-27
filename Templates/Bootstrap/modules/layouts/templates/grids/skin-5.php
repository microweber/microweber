<?php

/*

type: layout

name: Grid 5

position: 5

categories: Grids

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

if (page_title()) {
    $title = page_title();
}
?>

<section class="section <?php print $layout_classes; ?> edit safe-mode allow-drop" field="layout-grids-skin-5-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-4 col-lg-4 mb-2 cloneable">
                <div class="w-100 cube-wrapper">
                    <img class=" " src="<?php print template_url(); ?>assets/img/layouts/grid-7.png">
                </div>
            </div>
            <div class="col-12 col-sm-4 col-lg-4 mb-2 cloneable">
                <div class="w-100 cube-wrapper">
                    <img class=" " src="<?php print template_url(); ?>assets/img/layouts/grid-7.png">
                </div>
            </div>
            <div class="col-12 col-sm-4 col-lg-4 mb-2 cloneable">
                <div class="w-100 cube-wrapper">
                    <img class=" " src="<?php print template_url(); ?>assets/img/layouts/grid-7.png">
                </div>
            </div>
        </div>
    </div>
</section>
