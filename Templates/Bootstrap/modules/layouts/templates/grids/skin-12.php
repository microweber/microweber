<?php

/*

type: layout

name: Grid 12

position: 12

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


<section class="section <?php print $layout_classes; ?> edit safe-mode allow-drop" field="layout-grids-skin-12-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-12 mb-2 cloneable">
                <div class="cube"  style="text-align: center;">
                    <h1>Asteroids</h1>
                    <p class="lead">When television was young, there was a hugely popular show based on the still<br> popular functional character of Superman. The opening of that show had a familiar<br> phrase that went.</p>
                </div>
            </div>
        </div>
    </div>
</section>
