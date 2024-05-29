<?php

/*

type: layout

name: Default

description: List Navigation

*/

?>

<?php
    $params['ul_class'] = 'nav-list';
    $params['active_class'] = 'active';
	$params['ul_class_deep'] = 'nav-list';

?>

<script>

</script>


<div class="module-categories module-categories-template-default">
    <?php  category_tree($params);  ?>
</div>

