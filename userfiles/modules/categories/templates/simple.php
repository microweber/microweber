<?php

/*

type: layout

name: Simple

description: Simple Navigation

*/

?>

<?php
    $params['ul_class'] = 'nav nav-pills nav-stacked';
	$params['ul_class_deep'] = 'nav nav-pills nav-stacked';
?>

<div class="category-nav category-nav-stacked">
	<?php mw('category')->tree($params);   ?>
</div>
