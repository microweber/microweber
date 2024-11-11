<?php

/*

type: layout

name: Text block 10

position: 10

categories: Text block

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


<section class="section <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-text-block-skin-10-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row text-center nodrop">
            <div class="col-12 mx-auto allow-drop text-left">
                <p class="lead">Like rock stars, asteroids have been given their fair share of urban myth and lore. Many have attributed the extinction of the dinosaurs to the impact of a huge asteroid on the earth. Like rock stars, asteroids have been given their fair share of urban myth and lore. Many have attributed the extinction of the dinosaurs to the impact of a huge asteroid on the earth.
                    ‌Like rock stars, asteroids have been given their fair share of urban myth and lore. Many have attributed the extinction of the dinosaurs to the impact of a huge asteroid on the earth. Like rock stars, asteroids have been given their fair share of urban myth and lore. Many have attributed the extinction of the dinosaurs to the impact of a huge asteroid on the earth. ‌ ‌‌ </p>
            </div>
        </div>
    </div>
</section>
