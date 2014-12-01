<?php

/*

type: layout

name: Dropdown

description: Dropdown skin

*/

?>
<?php
    $params['ul_class'] = '';
	$params['ul_class_deep'] = ' ';

?>
<div class="mw-dropdown mw-dropdown-default w100 categories-dropdown">
  <span class="mw-dropdown-value mw-ui-btn mw-dropdown-val">Categories</span>
  <div class="mw-dropdown-content">

        <?php  category_tree($params);  ?>

  </div>
</div>