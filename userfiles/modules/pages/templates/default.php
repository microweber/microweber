<?php

/*

type: layout

name: Default

description: List Navigation

*/

?>

<?
    $params['ul_class'] = 'nav nav-list';
	$params['ul_class_deep'] = 'nav nav-list';
?>

<div class="pages-nav">
	<div class="well" style="padding: 0;">
		<?  pages_tree($params);  ?>
	</div>
</div>

