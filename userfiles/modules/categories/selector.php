<?

// d($params);
 
//$rand = uniqid();
if (!isset($params['for'])) {

	$for = 'content';
} else {
	$for = $params['for'];
}


if (!isset($params['to_table'])) {
$for = 'content';
	 
} else {
	$for = $params['to_table'];
}

$is_shop = '';
$active_cats = array();

if(isset($params['data-subtype']) and $params['data-subtype'] == 'product'){
	$params['is_shop'] = 'y';
} else {
 	//$params['is_shop'] = 'n';
}


if (isset($params['is_shop'])) {
	$is_shop = '&is_shop=' . $params['is_shop'];
}

if (!isset($params['to_table_id'])) {
	$to_table_id = '';
	if (!isset($params['to_table_id'])) {
		
	}
	
	
	if (isset($params['parent-category-id'])) {
		$active_cats [] = $params['parent-category-id'];
	}
 
	
	
	
} else {
	$to_table_id = '&to_table_id=' . $params['to_table_id'];
}
?>
<?
$cats_str = array();
$cats_ids = array();
$cats__parents = array();
$is_ex1 = array();
 $for = db_get_assoc_table_name($for);
 
	  if($for == 'conaaaaaaatent' or $for == 'table_content'){
		  
		  
	  }
	  
	$str1 = 'table=table_taxonomy&to_table='.$for.'&data_type=category&limit=1000&parent_id=0&to_table_id=[mt][int]0';
							$is_ex = get($str1); 
 	
	if(isarr($is_ex)){
	foreach ($is_ex as $item) {
		 $cats__parents[] = $item['id'];
	}
	}
 
 
/*
//  $cats__parents = $is_ex1;
if (empty($cats__parents)) {
	// $is_ex1 = get('limit=100&what=category&parent_id=0&for='.$for);
	foreach ($is_ex1 as $item) {
	//	$cats__parents[] = $item['parent_id'];
	}
}
 
if (isset($params['to_table_id']) and $params['to_table_id'] != 0) {

	$is_exs3 = get('limit=1000&what=category_items&to_table=' . $for .'&to_table_id=' . $params['to_table_id']);
 
	 
	if (isset($is_exs3[0])) {
		foreach ($is_exs3 as $is_exs3z) {
			$active_cats[] = $is_exs3z['parent_id'];
		}
	}
} else {

	//
} */ 
 
?>
<script  type="text/javascript">

 
/*

$(document).ready(function(){

   // mw_load_post_cutom_fields_from_categories{rand}()
    mw.$('#categorories_selector_for_post_{rand} input[name="categories"]').bind('change', function(e){
   // mw_load_post_cutom_fields_from_categories{rand}();





});
   
 


 
   
});

function mw_load_post_cutom_fields_from_categories{rand}(){
var a =	mw.$('#categorories_selector_for_post_{rand} *[name="categories"]').val();
var holder1 = mw.$('#custom_fields_from_categorories_selector_for_post_{rand}')
if(a == undefined || a == '' || a == '__EMPTY_CATEGORIES__'){
	holder1.empty();
	
} else {
	var cf_cats = a.split(',');
	holder1.empty();
	var i = 1;
	$.each(cf_cats, function(index, value) { 
	
	$new_div_id = 'cf_post_cat_hold_{rand}_'+i+mw.random();
	$new_div = '<div id="'+$new_div_id+'"></div>'
	$new_use_btn = '<button type="button" class="use_'+$new_div_id+'">use</button>'
  holder1.append($new_div);
		 mw.$('#'+$new_div_id).attr('for','categories');
		 mw.$('#'+$new_div_id).attr('to_table_id',value);

  	     mw.load_module('custom_fields/index','#'+$new_div_id, function(){
			// mw.log(this);
			//	$(this).find('*').addClass('red');
		 	$(this).find('input').attr('disabled','disabled');
			$(this).find('.control-group').append($new_use_btn);
			mw.$('.use_'+$new_div_id).unbind('click');
					mw.$('.use_'+$new_div_id).bind('click', function(e){
						//   mw_load_post_cutom_fields_from_categories{rand}()
						$closest =$(this).parent('.control-group').find('*[data-custom-field-id]:first');
						$closest= $closest.attr('data-custom-field-id');
						mw.$('#fields_for_post_{rand}').attr('copy_from',$closest);
						mw.reload_module('#fields_for_post_{rand}');
					 	mw.log($closest );
						 
						return false;
						});
			
			
			
			
			 });
  // mw.$('#'+$new_div_id).find('input').attr('disabled','disabled');
  i++;
  
});
	//holder1.html(a);
	//holder1.children().attr('disabled','disabled');
	
	
}
	
}*/
</script>
<script  type="text/javascript">

	$(document).ready(function(){

mw.$('#<? print $params['id'] ?> .mw-ui-check').bind('click', function(e){
	// e.preventDefault(); //stop the default form action
	var names = [];
	mw.$('#<? print $params['id'] ?> .mw-ui-check-input-sel:checked').each(function() {
	names.push($(this).val());
	});

	if(names.length > 0){
	mw.$('#mw_cat_selected_{rand}').val(names.join(',')).change();
} else {
        mw.$('#mw_cat_selected_{rand}').val('__EMPTY_CATEGORIES__').change();
	}

	//mw.log(names);

	});



   // mw_append_pages_tree_controlls(mwd.querySelector('.mw-ui-category-selector'));

	});
</script>
<? if(!empty($cats__parents)): ?>
<?
  
$active_cats1 = array();
foreach ($cats__parents as $item1) {
 
 $active_cats1[] = $item1;
  $active_cats[] = $item1;
}
	$tree = array();
	 $tree['include_categories'] = 1;
	// $tree['subtype'] = 'dynamic';
	    $tree['parent'] = '[int]0';
//	  $tree['include_first'] = 1;
	//$tree['parent'] = $item1;
//	$tree['to_tabqqle_id'] = '[gte]0';
	
	 //  $tree['debug'] = 1;
	if (isset($params['add_ids'])) {
		//$tree['add_ids'] = $params['add_ids'];

	}
	if (isset($params['is_shop'])) {
		 $tree['is_shop'] = $params['is_shop'];

	}
	
	if (isset($params['active_ids'])) {
		 $tree['active_ids'] = $params['active_ids'];

	}
	
	
	if (isset($params['categories_active_ids'])) {
		 $tree['categories_active_ids'] = $params['categories_active_ids'];

	} else if (!empty($active_cats1)) {
 	 // $tree['categories_active_ids'] = $active_cats1;
		
	} else {
 
	
	}
	
	
	if (!empty($cats_ids)) {
  $cats_ids[] = $item1;
	// $tree['active_ids'] = $cats_ids;
		
	}
	
	
	$input_name = " name='parent' ";
	if(isset($params['input-name'])){
		$input_name = " name='{$params['input-name']}' ";
	}
	
	
	$input_name_cats = "  ";
	if(isset($params['input-name-categories'])){
		$input_name_cats = " name='{$params['input-name-categories']}' ";
	}
	
	
	
	$input_type_cats = "  type='checkbox'  ";
	if(isset($params['input-type-categories'])){
		$input_type_cats = " type='{$params['input-type-categories']}' ";
	}
	
	
	 $tree['active_code'] = 'checked="checked" ';
	 $tree['active_code'] = 'checked="checked" ';
 
 
  
 
 
	$tree['link'] = "<label class='mw-ui-check'><input type='radio' {$input_name}  {active_code} value='{id}'   class='mw-ui-check-input-check' ><span></span><span>{title}</span></label>";
 	$tree['categores_link'] = "<label class='mw-ui-check'><input {$input_type_cats}  {$input_name_cats}   {active_code} value='{id}'   class='mw-ui-check-input-sel' ><span></span><span>{title}</span></label>";
 
 // d($tree);
 
pages_tree($tree);
?>
<? endif; ?>
<?  if(isset($params['include_global_categories']) and $params['include_global_categories'] == true  and isset($params['include_global_categories'])){
	 
						
						
					 
						$str0 = 'table=table_taxonomy&limit=1000&data_type=category&' . 'parent_id=0&to_table_id=0&to_table=table_content';
		$fors = get($str0);
					//d($fors );
					
					
					if ($fors != false and is_array($fors) and !empty($fors)) {
			foreach ($fors as $cat) {
				$cat_params =$params;
				$pt_opts = array();
	$pt_opts['link'] = "<label class='mw-ui-check'><input type='checkbox'  {active_code} value='{id}' class='mw_cat_selector_{$rand}' ><span></span><span>{title}</span></label>";
 
 
						$pt_opts['parent'] =$cat['id'];
						//$cat_params['to_table'] = 'table_content';
					//	$cat_params['to_table_id'] = ' 0 ';
					// $cat_params['for'] = 'table_content';
				 $pt_opts['include_first'] = 1;
					 //$cat_params['debug'] = 1;
					// d($cat_params);
						// category_tree($pt_opts);
			}
		}
						
				
				
				 
					
					
 
	 
 }
  
  
   ?>
<? $cats_str = implode(',', $active_cats); ?>

<input type="hidden" name="categories" id="mw_cat_selected_{rand}" value="<? print $cats_str ?>" />
