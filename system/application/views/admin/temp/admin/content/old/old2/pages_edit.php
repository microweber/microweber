<script type="text/javascript">

$(document).ready(function () {
   ajax_content_subtype_change();
   
   //$('#tabs').corners({ radio: 5, inColor: '#016D93', outColor: '#ffffff'});

   
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




<form action="<?php print $this->uri->uri_string(); ?>" method="post" enctype="multipart/form-data">
  
  <input name="id" type="hidden" value="<?php print $form_values['id']; ?>">
  <h1 style="padding:7px 0">Add/Edit page</h1>
  <input name="Save" value="Save" type="submit">
  <div id="tabs" class="flora">
    <ul class="tabs">
  	<li class="ui-state-active"><a href="#tab1">Settings</a></li>
     <li><a href="#tab2">Content</a></li>
      <li><a href="#tab3">Meta Tags</a></li>
  </ul>
  
  <div id="tab1">
  <fieldset>
    <legend>Settings</legend>
    <label>content_url:
      <input name="content_url" type="text" value="<?php print $form_values['content_url']; ?>">
    </label>
    <br />
    <label>
    content_parent:
    <!--    <select name="content_parent">
        <option>none</option>
        <?php foreach($form_values_all_pages as $item): ?>
        <option <?php if($item['id']== $form_values['content_parent'] ): ?> selected="selected" <?php endif; ?> <?php if($item['id']== $form_values['id'] ): ?> disabled <?php endif; ?> value="<?php print $item['id']; ?>">
        <?php print $item['content_title']; ?>
        </option>
        <?php endforeach ?>
      </select>-->
    <div class="ullist">
      <ul>
        <li>
          <input type="radio" name="content_parent"  <?php if(intval($form_values['content_parent']) == 0 ): ?>   checked="checked"  <?php endif; ?>   value="0" />
          None</li>
      </ul>
      <?php  
 $this->firecms = get_instance();
 $this->firecms->content_model->content_helpers_getPagesAsUlTree(0, "<input type='radio' name='content_parent'  {removed_ids_code}  {active_code}  value='{id}' />{content_title}", array($form_values['content_parent']), 'checked="checked"', array($form_values['id']) , 'disabled="disabled"' );  ?>
    </div>
    </label>
    <br />
  </fieldset>
  </div><!--/tab1-->
  <div id="tab2">
  <fieldset>
    <legend>Content</legend>
    <label>content_filename:
      <input name="content_filename" type="text" value="<?php print $form_values['content_filename']; ?>">
    </label>
    <br />
    <label>content_title:
      <input name="content_title" type="text" value="<?php print $form_values['content_title']; ?>">
    </label>
    <br />
  </fieldset>
  <fieldset>
    <legend>Content sub type</legend>
    <label>content_subtype:
      <select name="content_subtype" id="content_subtype" onchange="ajax_content_subtype_change()">
        <option <?php if($form_values['content_subtype'] == '' ): ?> selected="selected" <?php endif; ?>  value="">None</option>
        <option <?php if($form_values['content_subtype'] == 'dynamic' ): ?> selected="selected" <?php endif; ?>  value="dynamic">Blog section</option>
        <option <?php if($form_values['content_subtype'] == 'module' ): ?> selected="selected" <?php endif; ?>  value="module">Module</option>
      </select>
    </label>
    <label>content_subtype_value:
      <input name="content_subtype_value" id="content_subtype_value" type="text" value="<?php print $form_values['content_subtype_value']; ?>">
    </label>
    <div id="content_subtype_changer"></div>
    
    
  </fieldset>
  <fieldset>
    <legend>Layout file</legend>
    <label>content_layout_file:
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
    </label>
  </fieldset>
  <fieldset>
    <legend>Homepage?</legend>
    <label>is_home:
      <select name="is_home">
        <option  <?php if($form_values['is_home'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
        <option  <?php if($form_values['is_home'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
      </select>
    </label>
  </fieldset>
  
  <fieldset>
    <legend>Is active?</legend>
    <label>is_active:
      <select name="is_active">
        
        <option  <?php if($form_values['is_active'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
        <option  <?php if($form_values['is_active'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
      </select>
    </label>
  </fieldset>
  
   
<br />

    <fieldset>
    <legend>Page content</legend>
  <textarea name="content_body" id="content_body"><?php print $form_values['content_body']; ?></textarea>  
    </fieldset>
  
  
    <fieldset>
    <legend>Add this page to menus</legend>
   <?php  $this->firecms = get_instance();  $menus = $this->firecms->content_model->getMenus(array('item_type' => 'menu')); ?>
   <?php foreach($menus as $item): ?>
   <?php $is_checked = false; $is_checked = $this->firecms->content_model->content_helpers_IsContentInMenu($form_values['id'],$item['id'] ); ?>
   <label><?php print $item['item_title'] ?>&nbsp;<input name="menus[]" type="checkbox" <?php if($is_checked  == true): ?> checked="checked"  <?php endif; ?> value="<?php print $item['id'] ?>" /></label><br />

   <?php endforeach; ?>
   <?php //  var_dump( $menus);  ?>
  </fieldset>
  
  </div>
  <div id="tab3">
  <fieldset>
    <legend><h2>Meta tags</h2></legend>
    <label>content_meta_title:
      <input name="content_meta_title" type="text" value="<?php print $form_values['content_meta_title']; ?>">
    </label>
    
    <br />
<br />

     <label>content_meta_description:
     <textarea name="content_meta_description"><?php print $form_values['content_meta_description']; ?></textarea>
      
    </label>
    <br />
<br />

         <label>content_meta_keywords:
     <textarea name="content_meta_keywords"><?php print $form_values['content_meta_keywords']; ?></textarea>
      
    </label>
    
    <br />
<br />

      <label>content_meta_other_code:
     <textarea name="content_meta_other_code"><?php print $form_values['content_meta_other_code']; ?></textarea>
      
    </label>
    
    
    
  </fieldset>
  
  
  
  <br />
<br />
  </div>
   </div>   
  <div class="clear"></div><br />

  <input name="Save1" value="Save" type="submit">
</form>