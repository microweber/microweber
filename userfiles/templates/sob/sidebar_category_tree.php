<?php if($page['content_title'] == 'browse'): ?>
<h2 class="in-content-title">Everything in The School</h2>
<?php $include_first = false; ?>
<?php else :  ?>
<h2 class="in-content-title">Topics from <?php print $page['content_title'] ?> <a href="<?php print  site_url('main/rss/categories:').implode(',', $active_categories); ?>" target="_blank"><img src="<?php print TEMPLATE_URL; ?>img/rss.ico.jpg"></a></h2>
<?php $include_first = true; ?>
<?php endif; ?>
<?php $view = $this->core_model->getParamFromURL ( 'view' ); 
	$type = $this->core_model->getParamFromURL ( 'type' ); 
	$link = false;
	if($view == false){
		$link = $this->content_model->getContentURLById($page['id']).'/category:{taxonomy_value}' ;
	} else {
		$link = $this->content_model->getContentURLById($page['id']).'/category:{taxonomy_value}/view:'. $view ;
	}
	
		if($type != false){
		$link = $link . '/type:'.$type;
	} 
	
	
	
	$active = '  class="active"   ' ;
	$actve_ids = $active_categories;
	if( empty($actve_ids ) == true){
		$actve_ids = array($page['content_subtype_value']);
	}
	$this->content_model->content_helpers_getCaregoriesUlTree(
		$page['content_subtype_value'], 
		"<a href='$link' {active_code} >{taxonomy_value}</a>" , 
		$actve_ids, 
		$active, 
		$remove_ids = false, 
		$removed_ids_code = false, 
		$ul_class_name = 'category-nav', 
		$include_first = $include_first
	); 
?>
