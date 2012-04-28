
<div id="wall" class="wall">
  
  <? if(url_param('id') == false): ?>
  <? include TEMPLATE_DIR.'dashboard/add_item.php'; ?>
 
 
 
 <div class="items-list">
    <?

$params = array(); 
 
     $params['display'] = 'dashboard/my_pictures_albums_list_item.php'; //curent result page
  	$params['selected_categories'] = array(37); //if false will get the articles from the curent category. use 'all' to get all articles from evrywhere
  	$params['items_per_page'] = 1000; //limits the results by paging
	$params['created_by'] = user_id(); //curent result page
	$params['without_custom_fields'] = true; //if true it will get only basic posts info. Use this parameter for large queries
   $pics = get_posts($params);
 
?>
  </div>
  
  
  
  
  
  
  
  <? else: ?>
  <? include TEMPLATE_DIR.'dashboard/my_pictures_upload_to_gallery.php'; ?>
  <? 
  
  
 
  include(TEMPLATE_DIR.'dashboard/my_pictures_albums_view.php');
  
  ?>
  
   
  <? endif; ?>
</div>
<!-- /#wall -->
