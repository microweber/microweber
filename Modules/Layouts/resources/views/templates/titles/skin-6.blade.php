<?php

/*

type: layout

name: Titles 6

position: 6

categories: Titles

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


<section class="section <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-titles-skin-6-<?php print $params['id'] ?>" rel="module" style="background-color: #f5f5f5;">
    <div class="container">
        <div class="row text-center my-5 nodrop">
            <div class="col-lg-8 mx-auto allow-drop">
                <h2 class="mb-3">Back to the Earth</h2>
                <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. </p>
            </div>
        </div>
    </div>
</section>
