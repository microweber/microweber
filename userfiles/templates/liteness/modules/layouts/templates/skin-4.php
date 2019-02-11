<?php

/*

type: layout

name: Blog posts

position: 3

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

<div class="nodrop safe-mode edit <?php print $layout_classes; ?>" field="layout-skin-4-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="box-container latest-items">
            <h2 class="element section-title">
                <small class="safe-element"><?php print _lang('What\'s new', 'templates / liteness'); ?></small>
                <span class="safe-element"><?php print _lang('From the Blog', 'templates/liteness'); ?></span></h2>
            <module type="posts" limit="3" hide-paging="true" data-show="thumbnail,title,read_more,description">
        </div>
    </div>
</div>