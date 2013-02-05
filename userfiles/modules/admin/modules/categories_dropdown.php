<? $rand = crc32($params['id']);
if(isset($params['data-for'])){
	
$for =  $for2 =	$params['data-for'];
} else {
$for = 'modules';
$for2 = 'module';	
}

?>

<div class="mw_dropdown mw_dropdown_type_navigation left mw_dropdown_autocomplete" id="<? print $for2 ?>_category_selector" data-value='all'>
<span class="mw_dropdown_val">All</span>
<input style="width: 102px;" type="text" id="dd_<? print $for2 ?>_search" class="mw-ui-field dd_search" />
  <div class="mw_dropdown_fields">
    <ul>

      <li data-category-id="all"><a href="#">All</a></li>
      <li class="dd_custom" style="display: none" value="-1"><a style="text-decoration: underline" href="#"></a></li>
      <li>
        <module type="categories" data-no-wrap=1 data-for="<? print $for ?>" id="modules_toolbar_categories_<? print $rand+1  ?>" />
      </li>
    </ul>
  </div>
</div>
