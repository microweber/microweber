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


<script>mw.require("<?php print $config['url_to_module'] ?>templates/templates.css", true);</script>

<div class="well pages-nav pages-nav-default">
	<?php pages_tree($params);  ?>
</div>
