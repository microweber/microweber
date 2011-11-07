<script type="text/javascript">

$(document).ready(function () {
   ajax_content_subtype_change();
   
   //$('#tabs').corners({ radio: 5, inColor: '#016D93', outColor: '#ffffff'});

   var flora_tabs = $(".flora").tabs();

   $('#ccl1 a').click(function() {
        var cc_index = $('#ccl1 a').index(this);
        flora_tabs.tabs('select', cc_index);
        return false;
    });




});

function ajax_content_subtype_change(){
	var content_subtype = $("#content_subtype").val();
	var content_subtype_value = $("#content_subtype_value").val();
$.post("<?php print site_url('admin/content/pages_edit_ajax_content_subtype') ?>", { content_subtype: content_subtype, content_subtype_value: content_subtype_value },
  function(data){
    //alert("Data Loaded: " + data);
	$("#content_subtype_changer").html(data);
	
  });
}


function ajax_content_subtype_change_set_form_value(val){
	//alert('test');
	//return;
	//$("input[@name='users']")
	//var val  = this.val();
	
	//alert(val);
	 $("#content_subtype_value").setValue(val);
}



</script>



<table cellpadding="0" cellspacing="0" id="obj_pages_table">
<tr>

    <td style="width:250px;padding-left: 74px;" width="250px">


<div class="stabs" id="ccl1">
  	<a href="javascript:;">Settings</a>
     <a href="javascript:;">Content</a>
      <a href="javascript:;">Meta Tags</a>
      <a href="javascript:;">Custom</a>
</div>

    </td>
    <td>




<form action="<?php print site_url($this->uri->uri_string()); ?>" method="post" enctype="multipart/form-data" class="obj_pages_form">

  <input name="id" type="hidden" value="<?php print $form_values['id']; ?>">
  <!--<h1 style="padding:7px 0">Add/Edit page</h1>-->
   <a href="javascript:;"  class="objSave" style="float: left"><!--  --></a>
   <div class="clear" style="height: 2px;overflow: hidden"></div>

  <div id="tabs" class="flora object_notice">
    <ul class="tabs" style="display: none !important">
  	<li class="ui-state-active"><a href="#tab1">Settings</a></li>
     <li><a href="#tab2">Content</a></li>
      <li><a href="#tab3">Meta Tags</a></li>
      <li><a href="#tab_custom">Custom</a></li>
  </ul>
  
  <div id="tab1">
  <fieldset>
    <h2>Settings</h2>
    <label class="lbl">Content URL:  </label>
      <input name="content_url" type="text" value="<?php print $form_values['content_url']; ?>">
<?php $parent_from_url =  $this->core_model->getParamFromURL ( 'content_parent' ); 

?>
<?php if($parent_from_url != false): ?>
<?php if(intval($parent_from_url) != 0): ?>
<?php $form_values['content_parent']  = $parent_from_url; ?>


<?php endif; ?>
<?php endif; 
//var_dump($parent_from_url, $form_values['content_parent'] );
?>

    <label class="lbl">Content Parent:</label>

    <!--    <select name="content_parent">
        <option>none</option>
        <?php foreach($form_values_all_pages as $item): ?>
        <option <?php if($item['id']== $form_values['content_parent'] ): ?> selected="selected" <?php endif; ?> <?php if($item['id']== $form_values['id'] ): ?> disabled <?php endif; ?> value="<?php print $item['id']; ?>">
        <?php print $item['content_title']; ?>
        </option>
        <?php endforeach ?>
      </select>-->
    <div class="ullist lius">
      <ul>
        <li>
          <input type="radio" name="content_parent"  <?php if(intval($form_values['content_parent']) == 0 ): ?>   checked="checked"  <?php endif; ?>   value="0" />
          None</li>
      </ul>
      <?php

 $this->content_model->content_helpers_getPagesAsUlTree(0, "<input type='radio' name='content_parent'  {removed_ids_code}  {active_code}  value='{id}' />{content_title}", array($form_values['content_parent']), 'checked="checked"', array($form_values['id']) , 'disabled="disabled"' );  ?>
    </div>

    <br />
  </fieldset>
  </div><!--/tab1-->

    <div id="tab_custom" >
      <?php require_once (ADMINVIEWSPATH.'content/content_blocks/gallery.php')  ?>
     <table width="100%" border="0" cellspacing="0" cellpadding="0" style="display:none">
  


     <?php for ($i = 1; $i <= 10; $i++) : ?>
     <tr>
    <td>custom_field_<?php print $i ?>:</td>
    <td><textarea style="width:200px" name="custom_field_<?php print $i ?>"  rows="5" cols="50"><?php print $form_values['custom_field_'. $i]; ?></textarea></td>
  </tr>

    <?php endfor ; ?>    
    </table>
    </div>
  
  
  
  <div id="tab2">
  <fieldset>
    <h2>Content</h2>
    <label class="lbl">Content Filename:  </label>
      <input style="width:500px" name="content_filename" type="text" value="<?php print $form_values['content_filename']; ?>">

    <br />
    <label class="lbl">Content Title:  </label>
      <input style="width:500px" name="content_title" type="text" value="<?php print $form_values['content_title']; ?>">

    <br />
    <label class="lbl">Content Section Name:</label>
      <input style="width:500px" name="content_section_name" type="text" value="<?php print $form_values['content_section_name']; ?>">




    

    <label class="lbl">Content Section Name2:</label>
      <input style="width:500px" name="content_section_name2" type="text" value="<?php print $form_values['content_section_name2']; ?>">

    

    

  </fieldset>
  <fieldset>
    <!--<legend>Content sub type</legend>-->
    <label class="lbl">Content Subtype: </label>
      <select style="width:200px" name="content_subtype" id="content_subtype" onchange="ajax_content_subtype_change()">
        <option <?php if($form_values['content_subtype'] == '' ): ?> selected="selected" <?php endif; ?>  value="">None</option>
        <option <?php if($form_values['content_subtype'] == 'dynamic' ): ?> selected="selected" <?php endif; ?>  value="dynamic">Blog section</option>
        <option <?php if($form_values['content_subtype'] == 'module' ): ?> selected="selected" <?php endif; ?>  value="module">Module</option>
      </select>

    <label class="lbl">Content Subtype Value:</label>
      <input style="width:500px" name="content_subtype_value" id="content_subtype_value" type="text" value="<?php print $form_values['content_subtype_value']; ?>">

    <div id="content_subtype_changer"></div>
    

  </fieldset>
  <fieldset>
    <h2>Layout file</h2>
    <label class="lbl">Content Layout File:</label>
      <select name="content_layout_file">
      <option value="">Choose</option>
        <?php
  $this->firecms = get_instance();
  $files = $this->firecms->content_model->getLayoutFiles();
 ?>
        <?php foreach($files as $layout_file) : ?>
        <option title="<?php print addslashes($layout_file['description'] ) ?>" <?php if($form_values['content_layout_file'] == $layout_file['filename'] ): ?> selected="selected" <?php endif; ?>  value="<?php print $layout_file['filename'] ?>"><?php print $layout_file['filename'] ?> <?php print $layout_file['name'] ?></option>
        <?php endforeach; ?>
      </select>

  </fieldset>
  <fieldset>
    <label class="lbl">Is Home:</label>
      <select name="is_home">
        <option  <?php if($form_values['is_home'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">&nbsp;no&nbsp;</option>
        <option  <?php if($form_values['is_home'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">&nbsp;yes&nbsp;</option>
      </select>

  </fieldset>
  
  <fieldset>
    <label class="lbl">Is active:</label>
      <select name="is_active">
        <option  <?php if($form_values['is_active'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
        <option  <?php if($form_values['is_active'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
      </select>

  </fieldset>
  

<br />

    <fieldset>
    <label class="lbl">Page content</label>
  <textarea name="content_body" id="content_body" style="width: 500px;height:120px "><?php print $form_values['content_body']; ?></textarea>
    </fieldset>

  
    <fieldset>
    <label class="lbl"><b>Add this page to menus</b></label>

            <table cellpadding="0" cellspacing="0" style="width: auto">


   <?php  $this->firecms = get_instance();  $menus = $this->firecms->content_model->getMenus(array('item_type' => 'menu')); ?>
   <?php foreach($menus as $item): ?>
   <?php $is_checked = false; $is_checked = $this->firecms->content_model->content_helpers_IsContentInMenu($form_values['id'],$item['id'] ); ?>


        <tr>
            <td style="padding: 4px 2px"><label class="lbl" style="padding: 0px"><?php print $item['item_title'] ?></label></td>

      <td style="padding: 4px 2px">
       <input name="menus[]" type="checkbox" <?php if($is_checked  == true): ?> checked="checked"  <?php endif; ?> value="<?php print $item['id'] ?>" />
       </td>

       </tr>


   <?php endforeach; ?>
       </table>
   <?php //  var_dump( $menus);  ?>
  </fieldset>
  
  </div>
  <div id="tab3">
  <fieldset>
    <h2>Meta tags</h2>

    <label class="lbl">Content Meta Title:</label>
    <input style="width: 200px" name="content_meta_title" type="text" value="<?php print $form_values['content_meta_title']; ?>">

    <div style="height: 5px;overflow: hidden;clear: both"><!-- &nbsp; --></div>
     <label class="lbl">Content Meta Description:</label>
        <textarea style="width: 200px" name="content_meta_description"><?php print $form_values['content_meta_description']; ?></textarea>


     <label class="lbl">Content Meta Keywords: </label>
     <textarea style="width: 200px" name="content_meta_keywords"><?php print $form_values['content_meta_keywords']; ?></textarea>

<div style="height: 5px;overflow: hidden;clear: both"><!-- &nbsp; --></div>

<label class="lbl">Content Meta Other Code: </label>
     <textarea name="content_meta_other_code" style="width: 200px"><?php print $form_values['content_meta_other_code']; ?></textarea>

  </fieldset>
  

  <div style="height: 5px;overflow: hidden;clear: both"><!-- &nbsp; --></div>

  </div>
   </div>   
  <div class="clear"></div><br />

  <!--<input name="Save1" value="Save" type="submit">-->
  <a href="javascript:;" class="objbigSave" style="float: left">Save</a>
</form>




    </td>
</tr>


</table>