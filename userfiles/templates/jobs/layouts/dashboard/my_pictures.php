<? $dashboard_user = user_id_from_url(); ?>

<div id="wall" class="wall">
  <? if(url_param('id') == false): ?>
  <? include TEMPLATE_DIR.'dashboard/my_pictures_upload.php'; ?>
  <div class="galleries">
    <?

$params = array(); 
 
     $params['display'] = 'dashboard/my_pictures_albums_list_item.php'; //curent result page
  	$params['selected_categories'] = array(12); //if false will get the articles from the curent category. use 'all' to get all articles from evrywhere
  	$params['items_per_page'] = 1000; //limits the results by paging
	$params['created_by'] = $dashboard_user; //curent result page
	$params['without_custom_fields'] = true; //if true it will get only basic posts info. Use this parameter for large queries
   $pics = get_posts($params);
 
 
?>
<? if(empty($pics)): ?>

<h3><? print user_name($dashboard_user); ?> doesn't have any pictures yet.</h3>
<? endif; ?>

  </div>
  <? else: ?>
  <? //include TEMPLATE_DIR.'dashboard/my_pictures_upload_to_gallery.php'; ?>
  <? include TEMPLATE_DIR.'dashboard/my_pictures_upload.php'; ?>
  <? 
  
  
 
  include(TEMPLATE_DIR.'dashboard/my_pictures_albums_view.php');
  
  ?>
  <? endif; ?>
</div>
<!-- /#wall -->
