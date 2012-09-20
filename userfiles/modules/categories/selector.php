<?

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





 ?>

categories
<pre>
  <?
  if($to_table_id != ''){
   $is_ex1 = get('limit=100&what=category_items&for='.$for.$to_table_id);
  
  
  } else {
	  
	     $is_ex1 = get('limit=100&what=category&parent_id=0&for='.$for);
  
  }
  
  
  $cats_str = array();
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
			  		  $cats_str[] =   $par['title'];
					  $cats__parents[] = $par['id'];

		  }
		  
	  }
	  
	
  }
  $cats_str = implode(',', $cats_str);
d($cats__parents);
   ?>
  <? print $cats_str ?>
   </pre>
   <? if(!empty($cats__parents)): ?>
   <? 
   foreach($cats__parents as $item1){
	   category_tree('parent='.$item1);
	   
   }
   
   ?>
   <? endif; ?>
<textarea name="categories"><? print $cats_str ?></textarea>
