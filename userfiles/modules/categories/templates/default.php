<?php

/*

type: layout

name: Default

description: List Navigation

*/

?>

<?php
    $params['ul_class'] = 'nav-list';
	$params['ul_class_deep'] = 'nav-list';

?>

<div class="module-categories module-categories-template-default">

		<?php  category_tree($params);  ?>

</div>

