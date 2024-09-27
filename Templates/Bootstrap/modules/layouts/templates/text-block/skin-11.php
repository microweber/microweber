<?php

/*

type: layout

name: Text block 11

position: 11

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


<section class="section <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-text-block-skin-11-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row text-center nodrop">
            <div class="mw-default-layout-background-text col-12 mx-auto allow-drop">
                <p class="lead mb-0">
                   Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                </p>
            </div>
        </div>
    </div>
</section>
