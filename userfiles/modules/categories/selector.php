<?
$rand = uniqid();
if(!isset($params['for'])){
	
$for = 'content';	
} else {
	$for = $params['for'];	
}


if(!isset($params['to_table_id'])){
 	$to_table_id = '';
} else {
	$to_table_id = '&to_table_id='.$params['to_table_id'];
}


if(!isset($params['to_table_id'])){
 	$to_table_id = '';
} else {
	$to_table_id = '&to_table_id='.$params['to_table_id'];
}






 ?>



  <?
  
  if($to_table_id != ''){
   $is_ex1 = get('limit=100&what=category_items&for='.$for.$to_table_id);
  
  
  } else {
	  
	     $is_ex1 = get('limit=100&what=category&parent_id=0&for='.$for);
  
  }
  
  
  $cats_str = array();
   $cats_ids = array();
   $cats__parents = array();
  if(isset($is_ex1[0])){
	  foreach($is_ex1 as $item){
		  if($to_table_id != ''){
		  $par = get('&one&limit=1&what=category&for='.$for.'&id='.$item['parent_id']);
		  } else {
			  $par = $item;
		  }
		 //   d( $par);
		  if(isset($par['title'])){
			   if($to_table_id != ''){
			  		  $cats_str[] =   $par['title'];
					  $cats_ids[] = $par['id'];
					 // d($par);
					  if(intval($par['parent_id']) == 0){
					   // $cats__parents[] = $par['id'];
					  } else {
						   $cats__parents[] = $par['parent_id'];
					  }
			   } else {
					  $cats__parents[] = $par['id'];
			   }

		  }
		  
	  }
	  
	
  }
  $cats_str = implode(',', $cats_ids);
  
  if(empty( $cats__parents)){
	   $is_ex1 = get('limit=100&what=category&parent_id=0&for='.$for);
	    foreach($is_ex1 as $item){
			$cats__parents[] = $item['id'];
		}
  }

   ?>
  <? //print $cats_str ?>

   <? if(!empty($cats__parents)): ?>
   
   
   <script  type="text/javascript">


 

$(document).ready(function(){
	
	 
	$('.mw_cat_selector_<? print $rand ?>').on('click', function(e){
   // e.preventDefault(); //stop the default form action
    var names = [];
    $('.mw_cat_selector_<? print $rand ?>:checked').each(function() {
        names.push($(this).val());
    });

 if(names.length > 0){
$('#mw_cat_selected_<? print $rand ?>').val(names.join(',')).change();
 } else {
	$('#mw_cat_selected_<? print $rand ?>').val('__EMPTY_CATEGORIES__').change(); 
	 
 }



   //mw.log(names);

});
   
 


 
   
});
</script>
   
   
   <? 
   foreach($cats__parents as $item1){
	   //d($item1); 
	   $tree = array();
	   $tree['include_first'] = 1;
	    $tree['parent'] = $item1;
		
		if(isset($params['add_ids'])){
			 $tree['add_ids'] = $params['add_ids'];
			
		}
		
		
		
		
		if(!empty($cats_ids)){
			$tree['actve_ids'] = $cats_ids;
			$tree['active_code'] = 'checked="checked" ';
		}
		
		
		
		$tree['link'] = "<input type='checkbox'  {active_code} value='{id}' class='mw_cat_selector_{$rand}' >{title}";
		//  d($tree);
	   category_tree( $tree);
	   
   }
   
   ?>
   <? endif; ?>
<input type="hidden" name="categories" id="mw_cat_selected_<? print $rand ?>" value="<? print $cats_str ?>" />
