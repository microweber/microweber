<?
$id = $params['id'];



$form_values = get_page($id);

if(intval($id) == 0){
	$try_parent = $params['content_parent'];
	if(intval($try_parent) == 0){
		$try_parent = url_param('content_parent');;
	}
	if(intval($try_parent) != 0){
	$form_values['content_parent'] = $try_parent;
	}


}
//p($form_values);

?>
<script type="text/javascript">
// prepare the form when the DOM is ready
$(document).ready(function() {
    var save_page_options = {
        type:      'post',
		dataType: 'json',
		url:       '<? print site_url('api/content/save_page') ?>' ,
        beforeSubmit:  save_page_showRequest,  // pre-submit callback
        success:       save_page_showResponse  // post-submit callback
    };

    $('#save_page_form').submit(function() {
        $(this).ajaxSubmit(save_page_options);
        return false;
    });

	//call_layout_config_module();
});

// pre-submit callback
function save_page_showRequest(formData, jqForm, options) {
    var queryString = $.param(formData);
    return true;
}

// post-submit callback
function save_page_showResponse(responseText, statusText, xhr, $form)  {
//document.getElementById('edit_frame').contentWindow.location.reload();


   // alert('status: ' + statusText + '\n\nresponseText: \n' + responseText +    '\n\nThe output div should have already been updated with the responseText.');
}
</script>
<script type="text/javascript">
function set_layout($filename, $layout_name){

	 $('#content_layout_file').val($filename);

  $('#content_layout_name').val($layout_name);


  call_layout_config_module();
}

$(window).load(function(){
  call_layout_config_module();
  ajax_content_subtype_change();
});

function call_layout_config_module(){

 $file = $('#content_layout_file').val();
 $page_id = $('#page_id').val();


 $.ajax({
  url: '<? print site_url('api/module'); ?>',
   type: "POST",
      data: ({module : 'admin/pages/layout_config' ,page_id : $page_id, file: $file }),
     // dataType: "html",
      async:false,
	  success: function(resp) {
	   $('#layout_config_module_placeholder').html(resp);
	  }
    });


}

</script>
<script type="text/javascript">



$(document).ready(function () {
  //
   var flora_tabs = $(".flora").tabs();
 });







function ajax_content_subtype_change(){





	var content_subtype = $("#content_subtype").val();

	var content_subtype_value = $("#content_subtype_value").val();




	 $.ajax({
  url: '<? print site_url('api/module'); ?>',
   type: "POST",
      data: ({module : 'admin/content/pages_content_subtype' ,content_subtype : content_subtype, content_subtype_value: content_subtype_value }),
     // dataType: "html",
      async:true,

  success: function(resp) {
     // $("#content_subtype_changer").html(resp);
  }
    });



}





function ajax_content_subtype_change_set_form_value(val){


	 $("#content_subtype_value").setValue(val);

}







</script>

<mw module="admin/content/custom_fields_creator" page_id="<? print $form_values['id'] ?>" />



<form action="" method="post" id="save_page_form">
  <input class="btn" name="save" type="submit" value="save" />


  <input name="id" id="page_id" type="hidden" value="<? print $form_values['id'] ?>" />


  <input name="content_type" type="hidden" value="page" />




  <div class="formitem">

  <label>Page File</label>
  <div class="formfield"><input name="content_layout_file" type="text" id="content_layout_file" value="<? print $form_values['content_layout_file'] ?>" /></div>
  </div>
  <div class="formitem">
   <label>Layout</label>
  <span class="formfield"><input name="content_layout_name" type="text" id="content_layout_name" value="<? print $form_values['content_layout_name'] ?>" /> </span>
  </div>
  <div id="layout_config_module_placeholder"></div>
  <? $layouts = CI::model('template')->layoutsList();  ?>
  <? if(!empty($layouts)): ?>
  <? foreach($layouts as $layout): ?>
  <? if($layout['screenshot']): ?>
  <!-- <a href="<? print $layout['screenshot'] ?>"> <img src="<? print $layout['screenshot'] ?>" height="100" /></a>-->
  <? endif; ?>
  <input type="radio"  value="<? print $layout['filename'] ?>" name="layoutsList" onclick="set_layout(this.value, '<? print $layout['layout_name'] ?>')"  />
  <? print $layout['name'] ?> <? print $layout['description'] ?>

  <? endforeach; ?>
  <? endif; ?>

  <label>Page title</label>
  <span class="formfield"><input name="content_title" onchange="mw.buildURL(this.value, '#content_url')" type="text" value="<? print $form_values['content_title'] ?>" /></span>

  <label>Content URL </label>
  <span class="formfield"><input id="content_url" name="content_url" type="text" value="<? print $form_values['content_url'] ?>" /></span>

  <label>Description</label>
  <span class="formfield"><input name="content_description" type="text" value="<? print $form_values['content_description'] ?>" /></span>

  <label>Content</label>
  <span class="formfield"><textarea name="content_body" cols="" rows=""><? print $form_values['content_body'] ?></textarea></span>





  <h1>Advanced</h1>

  <label>Filename</label>
  <input name="content_filename" type="text" value="<? print $form_values['content_filename'] ?>" />


    <!--<legend>Content sub type</legend>-->
    <label class="lbl">Content Subtype: </label>
    <select   name="content_subtype" id="content_subtype" onchange="ajax_content_subtype_change()">
      <option <?php if($form_values['content_subtype'] == '' ): ?> selected="selected" <?php endif; ?>  value="">None</option>
      <option <?php if($form_values['content_subtype'] == 'blog_section' ): ?> selected="selected" <?php endif; ?>  value="blog_section">Blog section</option>
      <option <?php if($form_values['content_subtype'] == 'module' ): ?> selected="selected" <?php endif; ?>  value="module">Module</option>
    </select>
    <label class="lbl">Content Subtype Value:</label>
    <input   name="content_subtype_value" id="content_subtype_value" type="text" value="<?php print $form_values['content_subtype_value']; ?>">
    <div id="content_subtype_changer"></div>

<h2>  parent page </h2>
  <?php

 CI::model('content')->content_helpers_getPagesAsUlTree(0, "<input type='radio' name='content_parent'  {removed_ids_code}  {active_code}  value='{id}' />{content_title}", array($form_values['content_parent']), 'checked="checked"', array($form_values['id']) , 'disabled="disabled"' );  ?>

  <label>Required Login </label>
  <span class="formfield"><input name="require_login" type="text" value="<? print $form_values['require_login'] ?>" /> </span>

  <label>original_link</label>
  <span class="formfield"><input name="original_link" type="text" value="<? print $form_values['original_link'] ?>" /></span>
 <label>Is Home</label>
  <span class="formfield"><input name="is_home" type="text" value="<? print $form_values['is_home'] ?>" /> </span>

  <h2>Meta</h2>

  <label>Meta Title</label>
  <span class="formfield"><input name="content_meta_title" type="text" value="<? print $form_values['content_meta_title'] ?>" /> </span>

 <label>Meta description</label>
  <span class="formfield"><input name="content_meta_description" type="text" value="<? print $form_values['content_meta_description'] ?>" /></span>

  <label>Meta Keywords</label>
  <span class="formfield"><input name="content_meta_keywords" type="text" value="<? print $form_values['content_meta_keywords'] ?>" /></span>

  <fieldset>
    <legend>Add this page to menus</legend>
    <?php  CI::model('content')->content_model->getMenus(array('item_type' => 'menu')); ?>
    <?php foreach($menus as $item): ?>
    <?php $is_checked = false; $is_checked = CI::model('content')->content_helpers_IsContentInMenu($form_values['id'],$item['id'] ); ?>
    <label class="lbl"> <?php print $item['item_title'] ?>&nbsp;
      <input name="menus[]" type="checkbox" <?php if($is_checked  == true): ?> checked="checked"  <?php endif; ?> value="<?php print $item['id'] ?>" />
    </label>
    <?php endforeach; ?>
    <?php //  var_dump( $menus);  ?>
  </fieldset>
</form>
