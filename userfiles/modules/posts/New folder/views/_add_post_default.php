<? include_once('_add_post_default_scripts.php'); ?>

<h2 class="coment-title"><? print $title ?></h2>
<form class="add_content_form form" method="post" id="<? print $form_id ?>" action="#" enctype="multipart/form-data">
  <? if($the_post['id'] != false): ?>
  <?
   // $CI = get_instance ();
  $cats = get_instance()->taxonomy_model->getCategoriesForContent($the_post['id'], true); ?>
  <?

  
  if(!empty($cats)): 
  $category = ($cats[0]);
  
  
   $master_category = $category;
  $master_cats = get_instance()->taxonomy_model->getParents($category);
  if(!empty($master_cats)){
	    
	   $master_category = ($master_cats[0]);
	   if(intval($master_category) == 0){
		   $master_category = $category;
	   }
	   
	   
  }
 $subcats = get_instance()->taxonomy_model->getChildrensRecursiveAndCache($master_category, $type = 'category', $visible_on_frontend = false);
 if(empty($subcats)){
	 $subcats = get_instance()->taxonomy_model->getChildrensRecursiveAndCache($category, $type = 'category', $visible_on_frontend = false);
 }
  ?>




  <? foreach($cats as $cat): ?>
  <? if($cat != false): 
  
  ?>




  <input class="taxonomy_categories" name="taxonomy_categories[]" type="hidden" value="<?  print $cat ?>" />
  <? endif; ?>
  <? endforeach; ?>
  <? else: ?>
  <?
   if(empty($subcats)){
	 $subcats = get_instance()->taxonomy_model->getChildrensRecursiveAndCache($category, $type = 'category', $visible_on_frontend = false);
 }
  
  ?>
  <input class="taxonomy_categories" name="taxonomy_categories[]" type="hidden" value="<?  print $category ?>" />
  <? endif; ?>
  <input class="" name="id" type="hidden" value="<? print $the_post['id']; ?>" />
  <? else: ?>
  <input class="taxonomy_categories" name="taxonomy_categories[]" type="hidden" value="<?  print $category ?>" />
  <? endif; ?>
  
  
  
  
  
  
  
  
  
  
 
  
<? if($category): ?>

<? if(!$master_category){
	
$master_category = $category;	
}?>

  <? $subcats = get_instance()->taxonomy_model->getChildrensRecursiveAndCache($master_category, $type = 'category', $visible_on_frontend = false);  ?>
  <? if(!empty($subcats)): ?>
  <select id="cat_select">
  
   <? $cat = get_category($master_category);  ?>
  <? if($cat["users_can_create_content"] == 'y'): ?>
  <option value="<? print $cat["id"] ?>"><? print $cat["taxonomy_value"] ?></option>
  <? endif; ?>
  
  
  <? foreach($subcats as $subcat): ?>
  <? $cat = get_category($subcat);  ?>
  <? if($cat["users_can_create_content"] == 'y'): ?>
  <option value="<? print $cat["id"] ?>"><? print $cat["taxonomy_value"] ?></option>
  <? endif; ?>
   <? endforeach; ?>
  </select>
  <? endif; ?>
<? endif; ?>
  
  
  <label><? print $title_label; ?></label>
  <span>
  <input class="required" name="content_title" type="text" value="<? print $the_post['content_title']; ?>" style="width:300px;" />
  </span>
  <label><? print $body_label; ?></label>
  <span>
  <textarea  name="content_body" cols="" rows="" ><? print $the_post['content_body']; ?></textarea>
  </span>

 
  
  <?
  ksort($params);
  foreach($params as $k => $v): 

 
 
  ?>

   <? if(( stristr($k, 'display_') )): ?>

  <? include_once($v.'.php'); ?>
  <? endif; ?>
  
  <? endforeach; ?>
  <div class="c" style="padding-bottom: 5px;">&nbsp;</div>
  <? if($the_post['created_by'] == user_id()): ?>
  <a class="del" href="javascript:mw.content.del(<? print $the_post['id']; ?>, function(){window.location.reload(true)});">Delete</a>
  <? endif; ?>

  <a href="javascript:;" class="mw_btn_x right" onclick="$(this).parents('form').submit();"><span><? print $submit_btn_text ?></span></a>
</form>
