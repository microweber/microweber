<?php

/*

type: layout

name: Titles 2

position: 2

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


<section class="section <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-titles-skin-2-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row text-center mb-5 nodrop">
            <div class="col-lg-12 mx-auto allow-drop">
                <h2 class="mb-3">Jump to the Top</h2>
                <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
            </div>
        </div>
    </div>
</section>
