<?php
$rand = crc32($params['id']);
if(isset($params['data-for'])){
    $for =  $for2 =	$params['data-for'];
}
else {
  $for = 'modules';
  $for2 = 'module';
}
?>
<div class="mw-dropdown mw-dropdown_type_navigation left mw-dropdown_autocomplete" id="<?php print $for2 ?>_category_selector" data-value='all'>
<span class="mw-dropdown-val"><?php _e("All"); ?></span>
<input style="width: 102px;" type="text" id="dd_<?php print $for2 ?>_search" class="mw-ui-field dd_search" />
  <div class="mw-dropdown-content">
    <ul>
      <li data-category-id="all"><a href="#"><?php _e("All"); ?></a></li>
      <li class="dd_custom" style="display: none" value="-1"><a style="text-decoration: underline" href="#"></a></li>
      <li>
        <module
            type="categories"
            data-no-wrap=1
            data-for="<?php print $for ?>"
            id="modules_toolbar_categories_<?php print $rand+1;  ?>"
            template="liveedit_toolbar" />
      </li>
    </ul>
  </div>
</div>
