<?php

/*

type: layout

name: Simple

description: Simple Navigation

*/

?>

<?
    $params['ul_class'] = 'nav nav-pills nav-stacked';
	$params['ul_class_deep'] = 'nav nav-pills nav-stacked';
?>

<div class="category-nav category-nav-stacked">
	<? category_tree($params);   ?>
</div>
