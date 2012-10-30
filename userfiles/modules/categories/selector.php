<?
$rand = uniqid();
if (!isset($params['for'])) {

	$for = 'content';
} else {
	$for = $params['for'];
}

$is_shop = '';

if (isset($params['is_shop'])) {
	$is_shop = '&is_shop=' . $params['is_shop'];
}

if (!isset($params['to_table_id'])) {
	$to_table_id = '';
} else {
	$to_table_id = '&to_table_id=' . $params['to_table_id'];
}
?>
<?
$cats_str = array();
$cats_ids = array();
$cats__parents = array();
$is_ex1 = array();
 
//    $is_ex1 = get('debug=1&limit=100&what=category&parent_id=0&for='.$for);
$is_ex3 = get('limit=100&what=table_content&subtype=dynamic&type=page' . $is_shop);

if (isarr($is_ex3)) {
	foreach ($is_ex3 as $itm) {
		$is_ex1[]['parent_id'] = $itm['subtype_value'];

	}

}
$cats_str = implode(',', $cats_ids);
//  $cats__parents = $is_ex1;
if (empty($cats__parents)) {
	// $is_ex1 = get('limit=100&what=category&parent_id=0&for='.$for);
	foreach ($is_ex1 as $item) {
		$cats__parents[] = $item['parent_id'];
	}
}

if ($to_table_id != '') {

	$is_ex3 = get('limit=1000&what=category_items&for=' . $for . $to_table_id);
	// d($to_table_id);
	 
	if (isset($is_ex3[0])) {
		foreach ($is_ex3 as $item) {
			$cats_ids[] = $item['parent_id'];
		}
	}
} else {

	//
} 
 
?>
<? //print $cats_str ?>
<? if(!empty($cats__parents)):
?>
<script  type="text/javascript">

	$(document).ready(function(){

mw.$('.mw_cat_selector_<? print $rand ?>').on('click', function(e){
	// e.preventDefault(); //stop the default form action
	var names = [];
	mw.$('.mw_cat_selector_<? print $rand ?>:checked').each(function() {
	names.push($(this).val());
	});

	if(names.length > 0){
	mw.$('#mw_cat_selected_<? print $rand ?>').val(names.join(',')).change();
} else {
mw.$('#mw_cat_selected_<? print $rand ?>').val('__EMPTY_CATEGORIES__').change();

	}

	//mw.log(names);

	});

	});
</script>
<?

 
foreach ($cats__parents as $item1) {
	// 
	$tree = array();
	$tree['include_first'] = 1;
	$tree['parent'] = $item1;
	  //$tree['debug'] = 1;
	if (isset($params['add_ids'])) {
		$tree['add_ids'] = $params['add_ids'];

	}
	

	if (!empty($cats_ids)) {
	//	$cats_ids[] = $item1;
		$tree['actve_ids'] = $cats_ids;
		$tree['active_code'] = 'checked="checked" ';
	}
 
	$tree['link'] = "<input type='checkbox'  {active_code} value='{id}' class='mw_cat_selector_{$rand}' >{title}";

	category_tree($tree);

}
?>
<? endif; ?>

<input type="hidden" name="categories" id="mw_cat_selected_<? print $rand ?>" value="<? print $cats_str ?>" />
