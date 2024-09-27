<?php

/*

type: layout

name: Grid 14

position: 14

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

<section class="section <?php print $layout_classes; ?> edit safe-mode allow-drop cloneable" field="layout-grids-skin-14-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-9 mb-2">
                <div class="cube">
                    <h3>Beyond The Naked Eye</h3>
                    <p>When television was young, there was a hugely popular show based on the still popular functional character of Superman. The opening of that show had a familiar phrase that went, "Look. Up in the sky. Its a bird. It's a plane. It's Superman!"</p>
                </div>
            </div>
            <div class="col-12 col-lg-3 mb-2">
                <div class="cube">
                    <h4>Radio Astronomy</h4>
                    <p>In the history of modern astronomy, there is probably no one greater leap forward than the building and launch.</p>
                </div>
            </div>
        </div>
    </div>
</section>
