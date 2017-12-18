<?php

/*

type: layout

name: Default

description: Default Categories

*/

?>

<?php
$params['ul_class'] = ' ';
$params['ul_class_deep'] = 'hidden';
$params['li_class'] = '';
$params['li_class_deep'] = 'hidden';
?>

<div class="masonry masonry-shop">
    <div class="masonry__filters text-center">
        <?php category_tree($params); ?>
    </div>
</div>
