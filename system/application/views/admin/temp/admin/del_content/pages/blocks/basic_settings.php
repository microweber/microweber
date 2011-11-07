
<script type="text/javascript">

$(document).ready(function () {
   ajax_content_subtype_change();
   
   //$('#tabs').corners({ radio: 5, inColor: '#016D93', outColor: '#ffffff'});

   var flora_tabs = $(".flora").tabs();



   
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



      
<?php $parent_from_url =  $this->core_model->getParamFromURL ( 'content_parent' ); ?>
<?php if($parent_from_url != false): ?>
<?php if(intval($parent_from_url) != 0): ?>
<?php $form_values['content_parent']  = $parent_from_url; ?>
<?php endif; ?>
<?php endif; ?>
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
          <input style="float: none" type="radio" name="content_parent"  <?php if(intval($form_values['content_parent']) == 0 ): ?>   checked="checked"  <?php endif; ?>   value="0" />
          None</li>
      </ul> </div>
      <div class="PagesAsUlTree">

      <?php

 $this->content_model->content_helpers_getPagesAsUlTree(0, "<input type='radio' name='content_parent'  {removed_ids_code}  {active_code}  value='{id}' />{content_title}", array($form_values['content_parent']), 'checked="checked"', array($form_values['id']) , 'disabled="disabled"' );  ?>
    </div>

 <br />
<br />
<br />
<h2>Content</h2>
    <label class="lbl">Content Filename:  </label>
      <input style="width:500px" name="content_filename" type="text" value="<?php print $form_values['content_filename']; ?>">

    <br />
<!--               <label class="lbl">content filename sync with editor?</label>
<input name="content_filename_sync_with_editor" type="radio" value="y" <?php if($form_values['content_filename_sync_with_editor'] != 'n') : ?> checked="checked" <?php endif; ?> />Yes<br />
<input name="content_filename_sync_with_editor" type="radio" value="n" <?php if($form_values['content_filename_sync_with_editor'] == 'n') : ?> checked="checked" <?php endif; ?> />No<br />
 <br />
            -->      
    
    <br />    <br />
    <br />
    <label class="lbl">Content Section Name:</label>
      <input style="width:500px" name="content_section_name" type="text" value="<?php print $form_values['content_section_name']; ?>">


    <br />    <br />

    

    <label class="lbl">Content Section Name2:</label>
      <input style="width:500px" name="content_section_name2" type="text" value="<?php print $form_values['content_section_name2']; ?>">
<br />
<br />
<br>
<br>
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
      
      <br>
<br>
 <fieldset>
    <!--<legend>Content sub type</legend>-->
    <label class="lbl">Content Subtype: </label>
      <select style="width:200px" name="content_subtype" id="content_subtype" onchange="ajax_content_subtype_change()">
        <option <?php if(($form_values['content_subtype'] == '' ) or ($form_values['content_subtype'] == 'none' )): ?> selected="selected" <?php endif; ?>  value="">None</option>
        <option <?php if($form_values['content_subtype'] == 'dynamic' ): ?> selected="selected" <?php endif; ?>  value="dynamic">Blog section</option>
        <option <?php if($form_values['content_subtype'] == 'module' ): ?> selected="selected" <?php endif; ?>  value="module">Module</option>
      </select>

    <label class="lbl">Content Subtype Value:</label>
      <input style="width:200px" name="content_subtype_value" id="content_subtype_value" type="text" value="<?php print $form_values['content_subtype_value']; ?>">

    <div id="content_subtype_changer"></div>
    

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
  