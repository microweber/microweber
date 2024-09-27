<?php

/*

type: layout

name: Grid 2

position: 2

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



<section class="section <?php print $layout_classes; ?> edit safe-mode allow-drop" field="layout-grids-skin-2-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-8 cloneable background-image-holder h-350 mx-auto" style="background-image: url('<?php print template_url(); ?>assets/img/layouts/grid-1.png');"></div>

            <div class="col-12 col-sm-3 cloneable background-image-holder h-350 mx-auto" style="background-image: url('<?php print template_url(); ?>assets/img/layouts/grid-2.png');"></div>
        </div>
    </div>
</section>
