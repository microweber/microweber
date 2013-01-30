<? $rand = crc32($params['id']);
if(isset($params['data-for'])){
	
$for =  $for2 =	$params['data-for'];
} else {
$for = 'modules';
$for2 = 'module';	
}

?>

<div class="mw_dropdown mw_dropdown_type_navigation left" id="<? print $for2 ?>_category_selector" data-value='all'> <span class="mw_dropdown_val">All</span>
  <div class="mw_dropdown_fields">
    <ul>
      <li value="-1" class="other-action">
        <div class="dd_search">
          <input type="text" id="dd_<? print $for2 ?>_search" class="dd_search" />
          <span class="tb_search_magnify"></span></div>
        <a href="#" id="dd_<? print $for2 ?>_val_ctrl" class="semi_hidden"></a> </li>
      <li value="all"><a href="#">All</a></li>
      <li>
        <module type="categories" data-no-wrap=1 data-for="<? print $for ?>" id="modules_toolbar_categories_<? print $rand+1  ?>" />
      </li>
    </ul>
  </div>
</div>
