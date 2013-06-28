<?php

/*

type: layout

name: Default

description: List Navigation

*/

?>

<?php
    $params['ul_class'] = 'nav nav-list';
	$params['ul_class_deep'] = 'nav nav-list';
	$params['return_data'] = true;
 
?>
<?php $pages_tree= pages_tree($params);  ?>
 
<?php if($pages_tree != ''): ?>
<div class="pages-nav">
	<div class="well">
		<?php print $pages_tree ?>
	</div>
</div>
<?php endif; ?>