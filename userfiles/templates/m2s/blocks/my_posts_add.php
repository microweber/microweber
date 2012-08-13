<?

/**
 * 
 * 
 * Generic module to add content form
 * @author Peter Ivanov
 * @package content



 
 
 

 @param $category | id of the category you want to add post in | default:false
 @param $title | the title of the form | default:'Add new content'
 


 */

?>
<?
if ($params ['post_id']) {
	$params ['id'] = $params ['post_id'];
}

if ($params ['id']) {
			$the_post = get_post ( $params ['id'] );
			if (($the_post ['created_by']) != user_id ()) {
				 $the_post = false;
			}
		}


$form_id = md5(serialize(json_encode($params))); ?>
<script type="text/javascript">
$(document).ready(function() { 
    var options = { 
       url:       '<? print site_url('api/content/save_post'); ?>',
	   type: 'post',
	   
	   //target:        '#output2',   // target element(s) to be updated with server response 
        beforeSubmit:  showRequest,  // pre-submit callback 
		
        success:       showResponse  // post-submit callback 
 
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
function showRequest(formData, jqForm, options) { 
    // formData is an array; here we use $.param to convert it to a string to display it 
    // but the form plugin does this for you automatically when it submits the data 
    var queryString = $.param(formData); 
 
    // jqForm is a jQuery object encapsulating the form element.  To access the 
    // DOM element for the form do this: 
    // var formElement = jqForm[0]; 
 
   // alert('About to submit: \n\n' + queryString); 
 
    // here we could return false to prevent the form from being submitted; 
    // returning anything other than false will allow the form submit to continue 
    return $('#<? print $form_id ?>').isValid();
} 
 
// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  { 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
//  modal.close(); 
  //alert(responseText);
  <? if($redirect_on_success == false): ?>
  window.location.reload(true);
  <? else: ?>
  window.location.href= '<? print $redirect_on_success; ?>';
  <? endif; ?>
  
    // if the ajaxSubmit method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 

    // if the ajaxSubmit method was passed an Options Object with the dataType
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server 
 
   // alert('status: ' + statusText + '\n\nresponseText: \n' + responseText +    '\n\nThe output div should have already been updated with the responseText.'); 
} 

 



</script>
<script>

$(document).ready(function(){
    val = $(".taxonomy_categories:last").val();
    $("#cat_select").val(val);
    $("#cat_select").change(function(){
       val = $(this).val();
       $(".taxonomy_categories").val(val)
    });
});

</script>

<h1 class="add-post-title"><? print $title ?></h1>
<form class="add_content_form form" method="post" id="<? print $form_id ?>" action="#" enctype="multipart/form-data">
  <? if($params['category'] != false): ?>
  <input name="taxonomy_categories[]" type="hidden" value="<?  print $params['category'] ?>" />
  <? endif; ?>
  <? if($the_post['id'] != false): ?>
  <? $cats = get_instance()->taxonomy_model->getCategoriesForContent($the_post['id'], true); ?>
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
  <?
  ksort($params);
  foreach($params as $k => $v): 

 
 
  ?>
  <? if(( stristr($k, 'display_') )): ?>
  <? include_once($v.'.php'); ?>
  <? endif; ?>
  <? endforeach; ?>
  <div class="box open">
    <div class="box-content">
      <label>Select category:</label>
      <? 
		
		
		
		if($category): ?>
      <? if(!$master_category){
	
$master_category = $category;	
}?>
      <? $subcats = get_instance()->taxonomy_model->getChildrensRecursiveAndCache($master_category, $type = 'category', $visible_on_frontend = false); 
	 ?>
      <? if(!empty($subcats)): ?>
      <select id="cat_select">
        <? $cat = get_category($master_category);  ?>
        <? if($cat["users_can_create_content"] == 'y'): ?>
        <option value="<? print $cat["id"] ?>"><? print $cat["taxonomy_value"] ?></option>
        <? endif; ?>
        <? foreach($subcats as $subcat): ?>
        <? $cat = get_category($subcat);  ?>
        <option value="<? print $cat["id"] ?>">&nbsp;&nbsp;<? print $cat["taxonomy_value"] ?></option>
        <? endforeach; ?>
      </select>
      <? endif; ?>
      <? endif; ?>
    </div>
  </div>
  <div class="clear"></div>
  <div class="box open">
    <div class="box-content">
      <!--		<p class="msg-error">
					<strong>Error:</strong> This is an example of an error message.
					<a href="#" title="Remove" class="remove-btn">Remove</a>
				</p>
				<p class="msg-success">
					<strong>Congratulation:</strong> This is an example of an success message.
					<a href="#" title="Remove" class="remove-btn">Remove</a>
				</p>
				<p class="msg-attention">
					<strong>Attention:</strong> This is an example of an attention message.
					<a href="#" title="Remove" class="remove-btn">Remove</a>
				</p>
				<p class="msg-info">
					<strong>Information:</strong> This is an example of an information message.
					<a href="#" title="Remove" class="remove-btn">Remove</a>
				</p>-->
      <p>
        <label><? print $title_label; ?></label>
        <br />
        <input class="required" name="content_title" type="text" value="<? print $the_post['content_title']; ?>" style="width:300px;" />
      <div class="clear"></div>
      <label> <? print $body_label; ?></label>
      <div class="clear"></div>
      <textarea  name="content_body" cols="" rows="" ><? print $the_post['content_body']; ?></textarea>
      <? if($the_post['created_by'] == user_id()): ?>
      <a class="del right" href="javascript:mw.content.del(<? print $the_post['id']; ?>, function(){window.location.reload(true)});">Delete</a>
      <? endif; ?>
      <input type="button" onclick="mw.content.save('#<? print $form_id ?>', post_after_save);" value="<? print $submit_btn_text ?>" />
      </p>
    </div>
  </div>
</form>
