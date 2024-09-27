<?php

/*

type: layout

name: Titles 5

position: 5

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


<section class="section <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-titles-skin-5-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row text-center mb-5 nodrop">
            <div class="col-lg-10 mx-auto allow-drop text-left">
                <h5>A universe is a concept that usually means the entire space-time continuum in which we exist.</h5>
                <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
            </div>
        </div>
    </div>
</section>
