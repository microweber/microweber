<?php

/*

type: layout

name: Horizontal - List 1

description: List Navigation

*/

?>


<?php
$params['ul_class'] = 'mw-cats-menu';
$params['ul_class_deep'] = 'nav-list';
?>

<div class="module-categories module-categories-template-horizontal-list-1">
    <?php category_tree($params); ?>
</div>

