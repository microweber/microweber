<?php $authorId = intval($post['created_by']);?>
<?php if(intval($authorId ) != 0): ?>

<form id="demo_side_form" method="post" action="#" style="margin: 0">
  <!--  -->
</form>
<div class="sidebar-category-block">
  <h3 class="title" style="padding:17px 19px 12px;">More by <?php print $this->users_model->getPrintableName($authorId, $mode = 'first'); ?></h3>
  <div class="selectbox">
    <div id="recents-dropdown" class="recent-drop" style="display:none"> <span></span>
      <div class="recent-drop-list-holder">
        <?php $view = $this->core_model->getParamFromURL ( 'view' ); 
$link = false;
if($view == false){
//$link = $this->content_model->getContentURLById($page['id']).'/category:{taxonomy_value}' ;
$link = '{taxonomy_url}';
} else {
	//$link = $this->content_model->getContentURLById($page['id']).'/category:{taxonomy_value}/view:'. $view ;
	$link = '{taxonomy_url}/view:'. $view ;
}
$active = '  class="active"   ' ;
$actve_ids = $active_categories;
	if( empty($actve_ids ) == true){
		$actve_ids = array($page['content_subtype_value']);
	}
	
	$this->content_model->content_helpers_getCaregoriesUlTree(
		0, 
		"<a href='$link' {active_code}>{taxonomy_value}</a>" ,
		$actve_ids = $actve_ids, 
		$active_code = $active, 
		$remove_ids = false, 
		$removed_ids_code = false, 
		$ul_class_name = 'none', 
		$include_first = false
	); ?>
      </div>
    </div>
    
    <!--<h2 class="title" style="padding-top: 10px;">Recent Posts</h2>   -->
    
    <div id="sidebar_recent_posts_list">
      <?php require_once 'sidebar_recent_posts_list.php'; ?>
    </div>
  </div>
</div>
<?php endif; ?>
