<?php

/*

type: layout

name: Shop products

position: 2

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

<div class="nodrop safe-mode edit <?php print $layout_classes; ?>" field="layout-skin-3-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="box-container latest-items">
            <h2 class="section-title">
                <small class="safe-element"><?php print _lang('What\'s new', 'templates / liteness'); ?></small>
                <span class="safe-element"><?php print _lang('From the store', 'templates/liteness'); ?></span></h2>
            <module type="shop/products" limit="3" hide-paging="true" data-show="thumbnail,title,add_to_cart,price">
        </div>
    </div>
</div>