<div class="hright">
  <?php $content_item = $this->content_model->contentGetById($page['id']) ;
		  
		
		  
		 ?>
  <h2 class="gtitle"><?php print $content_item['content_title'] ?></h2>
  <?php if(!empty($content_item)){
			if(($content_item['content_subtype']  == 'blog_section' ) and (intval($content_item['content_subtype_value'])  != 0 ) ){
				
			 $view = $this->core_model->getParamFromURL ( 'view' ); 
			 	 $is_special = $this->core_model->getParamFromURL ( 'is_special' ); 
			 
			 
$link = false;
if($view == false){
$link = $this->content_model->getContentURLByIdAndCache($content_item['id']).'/category:{taxonomy_value}/view:all' ;
} else {
	$link = $this->content_model->getContentURLByIdAndCache($content_item['id']).'/category:{taxonomy_value}/view:'. $view ;
}

if($is_special != false){
$link = $link. '/is_special:'.$is_special;
} 



$active = '  class="active"   ' ;
$actve_ids = $active_categories;

$temp1234=   $this->core_model->getParamFromURL ( 'selected_categories' );
if($temp1234){
$temp1234 = explode(',', $temp1234);	
$actve_ids = $temp1234 ;
}
if( empty($actve_ids ) == true){
$actve_ids = array($page['content_subtype_value']);
}

//	function content_helpers_getCaregoriesUlTree($content_parent, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $orderby = false, $only_with_content = false) {

$this->content_model->content_helpers_getCaregoriesUlTree($content_item['content_subtype_value'], "<a href='$link'  {active_code}    ><strong>{taxonomy_value}</strong></a>" , $actve_ids = $actve_ids, $active_code = $active, $remove_ids = false, $removed_ids_code = false, $ul_class_name = 'sec-nav', $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $order = array('taxonomy_value', 'asc'), $only_with_content = true);

 	  
			  
		  }
		  }

	?>
  <?php include (ACTIVE_TEMPLATE_DIR."sidebar_contacts.php"); ?>
</div>
