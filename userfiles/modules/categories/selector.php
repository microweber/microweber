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
  
   $is_ex1 = get('limit=100&data_type=category_item&what=category_items&for='.$for.$to_table_id);
    $cats_str = array();
  if(isset($is_ex1[0])){
	  foreach($is_ex1 as $item){
		  $par = get('one&limit=1&data_type=category_item&what=category&for='.$for.'&id='.$item['parent_id']);
		  // d( $par);
		  if(isset($par['title'])){
			  		  $cats_str[] =   $par['title'];

		  }
		  
	  }
	  
	
  }
  $cats_str = implode(',', $cats_str);
  //d($is_ex1);
   ?>
  <? print $cats_str ?>
   </pre>
<textarea name="categories"><? print $cats_str ?></textarea>
