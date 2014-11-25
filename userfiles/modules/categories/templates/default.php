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

?>

 
<script>mw.require("<?php print modules_url(); ?>categories/templates.css", true); </script>

<div class="module-categories module-categories-template-default">
	<div class="well">
		<?php  category_tree($params);  ?>
	</div>
</div>

