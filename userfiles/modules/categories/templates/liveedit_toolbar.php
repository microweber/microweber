<?php

/*

type: layout

name: Liveedit Toolbar

description: List Navigation for Liveedit Toolbar

*/

?>

<?php
    $params['ul_class'] = '';
	$params['ul_class_deep'] = '';
?>

<div class="category-nav category-nav-default">

		<?php  mw('category')->tree($params);  ?>

</div>

