<?php

/*

type: layout

name: Default

description: List Navigation

*/

?>

<?php
    $params['ul_class'] = 'nav nav-pills nav-stacked';
	$params['ul_class_deep'] = 'nav nav-pills nav-stacked';
?>

<div class="well pages-nav">
	<?php  mw('content')->pages_tree($params);  ?>
</div>
