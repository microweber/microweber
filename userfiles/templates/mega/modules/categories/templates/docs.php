<?php

/*

type: layout

name: Docs

description: Docs

*/

?>

<?php
    $params['ul_class'] = 'mw-docs-cats';
	$params['ul_class_deep'] = "mw-docs-cats-inner";
?>

<div class="mw-docs-section">

		<?php  category_tree($params);  ?>

</div>