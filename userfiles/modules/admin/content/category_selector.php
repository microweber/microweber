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
<style>
.cat_list {
	height:226px;
	overflow-x:hidden;
	overflow-y:auto;
	zoom:1;
	position: relative;
}
#taxonomy_categories {
}
.ie .cat_list {
	overflow-y:scroll;
}
#category_module_holder {
	width:50%;
	border: 1px solid #C5C5C5;
	float: left;
}
.cat_lis {
	padding: 0;
}
.cat_list li span, .cat_lis li span {
	display: block;
	padding: 6px;
	cursor: pointer;
	overflow: hidden;
	border-top: 1px solid #e2e2e2;
}
.cat_list li, .cat_lis li {
	list-style: none;
}
.cat_list li span {
	padding-left: 35px;
}
.cat_list li li span {
	padding-left: 55px;
}
.cat_list li li li span {
	padding-left: 55px;
}
.cat_list li li li li span {
	padding-left: 65px;
}
.cat_list li li li li li span {
	padding-left: 75px;
}
.cat_list li span.even {
	background: #F5F5F5;
}
.cat_list li span {
	display: block;
	position: relative;
}
.cat_list li span .btn_small {
	position: absolute;
	top: 3px;
	right: 10px;
	z-index: 2;
	display: none;
 color:
}
.cat_list li span:hover .btn_small {
	display: inline-block;
	color: #003F73;
}
.cat_list .placeholder {
	background: #FF8025
}
.cat_list {
	padding: 0;
}
#cat_sel_right {
	float: left;
	width:44%;
	text-align: center;
	margin:0 0 0 20px;
}
#cat_sel_right em {
	display: block;
	padding-bottom: 30px;
	padding-top: 30px;
}
#cat_sel_right h3 {
	padding-bottom: 30px;
}
.cat_list li span input, .cat_lis li span input {
	margin-right: 4px;
	position: relative;
	top: 2px;
	float: left;
}
.ie .cat_list li span input, .ie .cat_lis li span input {
	top: -2px;
}
.cat_list li span:hover, .cat_lis li span:hover {
	background: #F0F0F0
}
.cat_list li span.active {
	background-color: #FDFCDF;
	background-position: 10px center;
	background-image: url(../img/icons/117-todo_2.png);
	background-repeat: no-repeat;
}
.catmove {
	display: none;
	width:16px;
	height: 16px;
	overflow: hidden;
	position:absolute;
	top: 5px;
	left: 9px;
	background: url(../img/move.gif) no-repeat;
	cursor: move;
	text-indent: -9999px;
}
.cat_list li span:hover .catmove, .cat_lis li span:hover .catmove {
	display: block;
}
.cat_list li span strong, .cat_lis li span strong {
	display: block;
 height:
 padding-left: 20px;
	z-index: 1;
	position: relative;
	font-weight: normal;
	height: 16px;
}
</style>
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
	 $params['link'] = '<span {active_code} category_id="{id}" >{taxonomy_value}</span>';
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
 
  $(document).ready(function($) {
 //jQuery(document).find("<? print $params['update_field'] ?>").hide();
// $(".category_element", window.parent.frames[0].document).remove();
 
  
	
  });
  // Code that uses other library's $ can follow here.
</script>
  
  
  
  <? endif; ?>
</div>
  <script type="text/javascript">
  

						 
			  
  
  
 $(document).ready(function () {
							 
				  
bind_cat_sel_fields()
 

});
 

 
 
function bind_cat_sel_fields(){
	
	

	 
	
	

	
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


 $("#<? print $rand_id ?> span").die("click");

$("#<? print $rand_id ?> span").live("click", function(){ 
 
var val = $(this).attr("category_id");
 //alert(val);
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
 
})	
}
</script>