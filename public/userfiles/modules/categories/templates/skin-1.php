<?php

/*

type: layout

name: Skin - 1

description: List Navigation 1

*/

?>

<?php
    $params['ul_class'] = 'nav-list';
    $params['active_class'] = 'active';
	$params['ul_class_deep'] = 'nav-list';

?>


<div class="module-categories module-categories-template-skin-1 module-categories module-categories-template-default">
    <?php  category_tree($params);  ?>
</div>

