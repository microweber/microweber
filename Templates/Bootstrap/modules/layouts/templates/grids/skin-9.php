<?php

/*

type: layout

name: Grid 9

position: 9

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


<section class="section <?php print $layout_classes; ?> edit safe-mode allow-drop" field="layout-grids-skin-9-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-2 cloneable">
                <div class="cube">
                    <h5>Pictures In The Sky</h5>
                    <p>There is a lot of exciting stuff going on in the stars above us that make astronomy so much fun.</p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-2 cloneable">
                <div class="cube">
                    <h5>Telescopes 101</h5>
                    <p>There is a lot of exciting stuff going on in the stars above us that make astronomy so much fun.</p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-2 cloneable">
                <div class="cube">
                    <h5>Asteroids</h5>
                    <p>There is a lot of exciting stuff going on in the stars above us that make astronomy so much fun.</p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-2 cloneable">
                <div class="cube">
                    <h5>Radio Astronomy</h5>
                    <p>There is a lot of exciting stuff going on in the stars above us that make astronomy so much fun.</p>
                </div>
            </div>
        </div>
    </div>
</section>
