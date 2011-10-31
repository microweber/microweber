<?php

/*
type: module
name: Category selector
description: Module to display dropdown of categories in the admin.

*/



?>
<?

/**
 *
 *
 * Generic module to make categories select box
 * @author Peter Ivanov
 * @package categories


Example:
 @example:

 <microweber module="admin/content/category_selector" active_category="<? print implode(',',$category_ids); ?>" update_field="#taxonomy_categories"  multiselect=true />


 //params for the data
 @param $multiselect | if true it will allow multiselect
 @param $update_field | jquery selelctor to update the filt with the category id
 @param $for_page | get subats for page

 */

?>
<?


$orig_params = $params;
//var_dump($params);

$rand_id = "cat_list_".rand();
?>
<?

if($params['multiselect']){
	 $multiselect = 'cat_list_multiselect';
	 }

?>
<?
 $sortable = false;
if($params['sortable']){
	 $sortable = true;
	 }















	 if($params['link'] == false){
	 $params['link'] = '<span {active_code} category_id="{id}">{taxonomy_value}</span>';
	 }

	 if($params['link_base64']){
	 $params['link'] = base64_decode($params['link_base64']);;
	 }

	  if(!$params['content_parent']){
	 $params['content_parent'] = 0;
	 }

	   if(!$params['active_code']){
	 $params['active_code'] = "  class='active'  ";
	 }

	 if($params['active_category']){
	 $params['actve_ids'] =explode(',',$params['active_category']);
	 }

	 	 if($params['active_categories']){
	 $params['actve_ids'] =($params['active_categories']);
	 }
	 
	 	 if($params['active_categories']){
	 $params['actve_ids'] =($params['active_categories']);
	 }


 $params ['include_first'] = true;



 if($params['ul_class_name']){
	 $params['ul_class_name'] =($params['ul_class_name']);
	 } else {
		 
		 $params ['ul_class_name'] = 'category_tree'; 
	 }
	 
	 
	 
	  if($params['holder_class_name']){
	 $params['holder_class_name'] =($params['holder_class_name']);
	 } else {
		 
		 $params ['holder_class_name'] = 'cat_list'; 
	 }


?>
<? if($sortable == true):?>
<? endif; ?>

<div class="<? print $params ['holder_class_name'] ?> <? print $multiselect; ?> <? print $params ['ul_class_name'] ?>" id="<? print $rand_id ?>">
  <?
 
    // $params = array();
    // $params['content_parent'] = 0; //parent id


 //	p($params);



/*$actve_ids = ($params ['actve_ids']) ? $params ['actve_ids'] : false;
	$active_code = ($params ['active_code']) ? $params ['active_code'] : false;
	$remove_ids = ($params ['remove_ids']) ? $params ['remove_ids'] : false;
	$removed_ids_code = ($params ['removed_ids_code']) ? $params ['removed_ids_code'] : false;
	$ul_class_name = ($params ['ul_class_name']) ? $params ['ul_class_name'] : false;
	$include_first = ($params ['include_first']) ? $params ['include_first'] : false;
	$content_type = ($params ['content_type']) ? $params ['content_type'] : false;
	$add_ids = ($params ['add_ids']) ? $params ['add_ids'] : false;
	$orderby = ($params ['orderby']) ? $params ['orderby'] : false;*/

category_tree( $params ) ;
 
 
 if( $params['for_page'] ){
	 $params['not_for_page'] = $params['for_page'];
	  $params['for_page'] = false;
	 category_tree( $params ) ;
 }



?>
  <? if($params['update_field']):  ?>
  <script type="text/javascript">
 $(document).ready(function () {

  //$("<? print $params['update_field'] ?>").click(function(){alert(this.value)})


   /*var Catval = "";
    $("#<? print $rand_id ?> span.active").each(function(){
      if(Catval==""){
        Catval = Catval + $(this).attr("category_id")
      }
      else{
        Catval = Catval + "," + $(this).attr("category_id")
      }
    });

	$("<? print $params['update_field'] ?>").val(Catval)
*/


/*var val = $("<? print $rand_id ?> span.active").attr("category_id");

 $("<? print $params['update_field'] ?>").val(val);*/

$("#<? print $rand_id ?> span").live("click", function(){


  $(this).toggleClass("active");

  if($(this).parents("#<? print $rand_id ?>").hasClass("cat_list_multiselect")){
    var Catval = "";
    $("#<? print $rand_id ?> span.active").each(function(){
      if(Catval==""){
        Catval = Catval + $(this).attr("category_id")
      }
      else{
        Catval = Catval + "," + $(this).attr("category_id")
      }
    });
    $("<? print $params['update_field'] ?>").val(Catval).change()

  }
  else{
	var val = $(this).attr("category_id");
	$("<? print $params['update_field'] ?>").val(val).change()
  }
;
})

});

</script>
  <? endif; ?>
</div>
