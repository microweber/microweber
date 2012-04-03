<? $form_id = md5(serialize(json_encode($params))); ?>
<script type="text/javascript">
$(document).ready(function() { 
    var options = { 
       url:       '<? print site_url('api/content/save_post'); ?>',
	   type: 'post',
	   
	   //target:        '#output2',   // target element(s) to be updated with server response 
        beforeSubmit:  showRequest<? print $form_id ?>,  // pre-submit callback 
		
        success:       showResponse<? print $form_id ?>  // post-submit callback 
 
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
 
    // bind to the form's submit event 
    $('#<? print $form_id ?>').submit(function() { 
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit 
        $(this).ajaxSubmit(options); 
 
        // !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    }); 
}); 
 
// pre-submit callback 
function showRequest<? print $form_id ?>(formData, jqForm, options) { 
    var queryString = $.param(formData); 
    return $('#<? print $form_id ?>').isValid();
} 
 
// post-submit callback 
function showResponse<? print $form_id ?>(responseText, statusText, xhr, $form)  { 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
//  modal.close(); 
 
} 

function posts_after_save<? print $form_id ?>($msg)  {
  if($msg.id != undefined){
      <? if($redirect_on_success == false): ?>
      window.location.reload(true);
      <? else: ?>
      window.location.href= '<? print $redirect_on_success; ?>';
      <? endif; ?>
  }
} 



</script>
<?      
if($params['custom_fields']){
$add_fields = 	decode_var($params['custom_fields']);
}


if($params['post_id']){
$the_post =   get_post($params['post_id']);
//p($the_post)
}


if($params['category']){
$category = 	($params['category']);
}



?>




<form class="add_content_form form form-horizontal" method="post" id="<? print $form_id ?>" action="#" enctype="multipart/form-data">
   <? if($id != false): ?>
  <input name="id" type="hidden" value="<? print $id; ?>" />
  <? endif; ?>
  
  
  <? if(intval($the_post['content_parent']) == 0): ?>
    <? if($category != false): ?>
    <? $page_for_cat  = (get_page_id_for_category_id($category) );    ?>
  <? endif; ?>
<? else : ?>
<? $page_for_cat  = $the_post['content_parent'];    ?>
   <? endif; ?>



   <? if(intval($page_for_cat) > 0 ): ?>
  <input name="content_parent" type="hidden" value="<? print $page_for_cat; ?>" />
  <? endif; ?>


  
  <fieldset>
    <legend><? print $title ?></legend>
    <div class="control-group">
      <label for="input01" class="control-label"><? print $title_label; ?></label>
      <div class="controls">
        <input type="text" id="input01"  name="content_title"  value="<? print $the_post['content_title']; ?>" class="required input-xlarge">
      </div>
    </div>
     
    <div class="control-group">
      <label for="select01" class="control-label">Select category</label>
      <div class="controls">
        <? if($the_post['id'] != false): ?>
<? $cats = CI::model('taxonomy')->getCategoriesForContent($the_post['id'], true); ?>
<?

  
  if(!empty($cats)): 
  $category = ($cats[0]);
  
  
   $master_category = $category;
  $master_cats = CI::model('taxonomy')->getParents($category);
  if(!empty($master_cats)){
	    
	   $master_category = ($master_cats[0]);
	   if(intval($master_category) == 0){
		   $master_category = $category;
	   }
	   
	   
  }
 $subcats = CI::model('taxonomy')->getChildrensRecursiveAndCache($master_category, $type = 'category', $visible_on_frontend = false);
 if(empty($subcats)){
	 $subcats = CI::model('taxonomy')->getChildrensRecursiveAndCache($category, $type = 'category', $visible_on_frontend = false);
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
	 $subcats = CI::model('taxonomy')->getChildrensRecursiveAndCache($category, $type = 'category', $visible_on_frontend = false);
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
<? $subcats = CI::model('taxonomy')->getChildrensRecursiveAndCache($master_category, $type = 'category');  
   
  ?>
<? $cat = get_category($master_category); 
  
   ?>
<? if(!empty($subcats)): ?>
<select id="cat_select">
  <? if($cat["users_can_create_content"] == 'y'): ?>
  <option value="<? print $cat["id"] ?>"><? print $cat["taxonomy_value"] ?></option>
  <? foreach($subcats as $subcat): ?>
  <? $cat = get_category($subcat);  ?>
  <option value="<? print $cat["id"] ?>"><? print $cat["taxonomy_value"] ?></option>
  <? endforeach; ?>
</select>
<? endif; ?>
<? endif; ?>
<? endif; ?>
      </div>
    </div>
     
     
    <div class="control-group">
      <label for="textarea" class="control-label"><? print $body_label; ?></label>
      <div class="controls">
        <textarea rows="3"  name="content_body" ><? print $the_post['content_body']; ?></textarea>
      </div>
    </div>
    <? if(!empty($add_fields )): ?>
    
    
    
    
    <? foreach($add_fields as $add_field ): 
	$cfv = cf_val($the_post['id'], url_title($add_field['name']) , $use_vals_array = false);
	 if($cfv  != false){
		 $add_field['value'] = $cfv;
	 }
	?>
   
   
   <?php switch(strtolower($add_field['type'])): ?>
<?php case 'textarea': ?>
  <div class="control-group">
      <label for="inputtextarea<? print md5($add_field['name']) ?>" class="control-label"><? print $add_field['name'] ?></label>
      <div class="controls">
         
        <textarea class="input-xlarge" id="inputtextarea<? print  md5($add_field['name']) ?>"  name="custom_field_<? print url_title($add_field['name']); ?>"  rows="4"><? print $add_field['value'] ?></textarea>
        
        
        
      </div>
    </div>
    
    




 
<?php break;?>
<?php default: ?>
  <div class="control-group">
      <label for="input01<? print $add_field['name'] ?>" class="control-label"><? print $add_field['name'] ?></label>
      <div class="controls">
        <input type="text" id="input_<? print url_title($add_field['name']) ?>"  name="custom_field_<? print url_title($add_field['name']); ?>"  value="<? print $add_field['value'] ?>" class="required input-xlarge">
      </div>
    </div>
<?php break;?>
<?php endswitch;?>
   
   
   
    
    
  
    
    
    
    
    
    
    <? endforeach; ?>
    <? endif; ?>
    <div class="form-actions">
      <button class="btn-primary save-post-btn" onclick="mw.content.save('#<? print $form_id ?>', posts_after_save<? print $form_id ?>);" type="button"><? print $submit_btn_text ?></button>
      
    </div>
  </fieldset>
</form>
