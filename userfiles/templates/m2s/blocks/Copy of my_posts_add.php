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

function postsss()  { 
    
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

 

<h2 class="coment-title"><? print $title ?></h2>
<form class="add_content_form form" method="post" id="<? print $form_id ?>" action="#" enctype="multipart/form-data">
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
  <option value="<? print $cat["id"] ?>">    <? print $cat["taxonomy_value"] ?></option>
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

 <a href="javascript:mw.content.save('#<? print $form_id ?>', postsss);" class=""><span><? print $submit_btn_text ?></span></a>
</form>


<div class="box open width-55 right">
			<div class="box-title"><div>
				Other Layouts (55% width)
				<a href="#" class="box-toggle"></a>
			</div></div>
			<form action="#" method="post">
			<div class="box-content">
				<div class="col-45 left">
					<h2>Checkboxes</h2>
					<input type="checkbox" id="check-1" /> <label for="check-1">CSS</label><br />
					<input type="checkbox" id="check-2" /> <label for="check-2">xHTML</label><br />
					<input type="checkbox" id="check-3" /> <label for="check-3">jQuery</label><br />
					<input type="checkbox" id="check-4" /> <label for="check-4">Photoshop</label>
				</div>
				<div class="col-45 right">
					<h2>Radio buttons</h2>
					<input type="radio" name="radio-1" id="radio-1" /> <label for="radio-1">CSS</label><br />
					<input type="radio" name="radio-1" id="radio-2" /> <label for="radio-2">xHTML</label><br />
					<input type="radio" name="radio-1" id="radio-3" /> <label for="radio-3">jQuery</label><br />
					<input type="radio" name="radio-1" id="radio-4" /> <label for="radio-4">Photoshop</label>
				</div>
				<div class="clear"></div>
				<h2>Add Tags</h2>
				<p>
					<input type="text" />
					<cite class="hint">Seperate tags with coma</cite>
				</p>
				<p>
					<input type="submit" value="Add Tags" />
				</p>
			</div>
			</form>
		</div>
		<div class="clear"></div>
		<div class="box open">
			<div class="box-title"><div>
				Editor (feel free to include your favourite one)
				<a href="#" class="box-toggle"></a>
			</div></div>
			<div class="box-content">
			<form action="#" method="post">
				<p class="msg-error">
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
				</p>
				<p>
					<input type="text" />
					<img src="images/editor.gif" title="Your Favourite Text Editor Toolbar" alt="Your Favourite Text Editor Toolbar" />
					<textarea cols="" rows=""></textarea>
					<input type="reset" value="Cancel" />
					<input type="button" value="Preview" />
					<input type="submit" value="Publish" />
				</p>
			</form>
			</div>
		</div>























 