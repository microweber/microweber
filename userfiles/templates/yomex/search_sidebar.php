<div class="hright">
  <?php $content_item = $this->content_model->contentGetById($page['id']) ;
		  
		
		  
		 ?>
       
  <h2 class="gtitle">Категории</h2>
  
 
    
    
    
  <?php $link = false;
if($view == false){
$link = $this->content_model->getContentURLByIdAndCache($content_item['id']).'/categories:{id}' ;
} else {
	$link = $this->content_model->getContentURLByIdAndCache($content_item['id']).'/categories:{id}/view:'. $view ;
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

}
$actve_ids = array($this->core_model->getParamFromURL ( 'categories' ));
//	function content_helpers_getCaregoriesUlTree($content_parent, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $orderby = false, $only_with_content = false) {

$this->content_model->content_helpers_getCaregoriesUlTree(2161, "<a href='$link'  {active_code}    ><strong>{taxonomy_value}</strong></a>" , $actve_ids = $actve_ids, $active_code = $active, $remove_ids = false, $removed_ids_code = false, $ul_class_name = 'sec-nav', $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $order = array('taxonomy_value', 'asc'), $only_with_content = true);

 	  
			  
		   
	 

	?>
    
    
    
     
 
  
</div>
