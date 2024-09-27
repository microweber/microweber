<?php

/*

type: layout

name: Grid 13

position: 13

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

<section class="section <?php print $layout_classes; ?> edit safe-mode allow-drop cloneable" field="layout-grids-skin-13-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8 mb-2">
                <div class="cube">
                    <h3>The Amazing Hubble</h3>
                    <p>When television was young, there was a hugely popular show based on the still popular functional character of Superman. The opening of that show had a familiar phrase that went.</p>
                </div>
            </div>
            <div class="col-12 col-lg-4 mb-2">
                <div class="cube">
                    <h4>Radio Astronomy</h4>
                    <p>There is a lot of exciting stuff going on in the stars above us that make astronomy so much fun.</p>
                </div>
            </div>
        </div>
    </div>
</section>
