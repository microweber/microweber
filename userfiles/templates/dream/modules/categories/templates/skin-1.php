<?php

/*

type: layout

name: Categories - Sidebar

description: Skin 1

*/

?>

<?php
$params['ul_class'] = 'link-list';
$params['ul_class_deep'] = '';
$params['li_class'] = '';
$params['li_class_deep'] = '';
?>


<div class="categories-list">
    <?php category_tree($params); ?>
</div>
