<?php

/*

type: layout

name: Slider

position: 1

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = '';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = '';
}

$layout_classes = ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>

<div class="nodrop safe-mode edit <?php print $layout_classes; ?>" field="layout-skin-2-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="content-gallery-slider home-slider">
            <module type="pictures" content-id="<?php echo PAGE_ID; ?>"/>
        </div>
    </div>
</div>