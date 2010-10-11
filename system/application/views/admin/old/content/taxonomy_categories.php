<?php

 $criteria = array();
 $criteria['taxonomy_type'] = 'category'; 
 //$criteria['taxonomy_type'] = 'category';
 //$categories =  $this->taxonomy_model->taxonomyGet($criteria, false, true); 

 ?>
  <?php $categories_content_types = $this->core_model->optionsGetByKeyAsArray('content_types');
		
		 ?>
<?php // var_dump($form_values);  ?>
<script>

    $(function(){
        $("#taxonomy_tabs").tabs();

    })
</script>
<form id="catform" action="<?php print site_url($this->uri->uri_string()); ?>" method="post" enctype="multipart/form-data">
  <div id="taxonomy_tabs">

    <ul class="tabs">
      <li class="ui-state-active"><a href="#tab1">Category settings</a></li>
      <li><a href="#tab_content_body">Content</a></li>
     <!-- <li><a href="#tab2">Map settings</a></li>-->
      <li><a href="#tab_advanced_options">Advanced options</a></li>
    </ul>

    <div id="tab1" class="lbl_object">
      <fieldset>
      <legend> Add/Edit categories </legend>
      <?php if($form_values["media"]["pictures"][0]["urls"][96] != ''): ?>
      <img src="<?php print $form_values["media"]["pictures"][0]["urls"][96];  ?>" alt="" />
      <?php endif; ?>
      <label><span>Category name:</span>
      <input name="taxonomy_value" type="text" value="<?php print $form_values['taxonomy_value']; ?>">
      </label>
      <?php $categories_content_types = $this->core_model->optionsGetByKeyAsArray('content_types'); 	
		
		 ?>
      <label><span>Category content type:</span>
      <select name="taxonomy_content_type">
        <?php foreach($categories_content_types as $item) : ?>
        <option value="<?php print $item ?>" <?php if($form_values['taxonomy_content_type'] == $item ): ?>  selected="selected" <?php endif; ?>><?php print $item ?></option>
        <?php endforeach; ?>
      </select>
      </label>
      <label><span>Category description:</span>
      <textarea name="taxonomy_description" cols="" rows=""><?php print $form_values['taxonomy_description'] ;  ?></textarea>
      </label>
      <label><span>Parent category:</span>r
      <select name="parent_id">
        <option value="">None</option>
        <?php foreach($categories as $item) : ?>
        <option value="<?php print $item['id'] ?>" <?php if($form_values['parent_id'] == $item['id'] ): ?>  selected="selected" <?php endif; ?>  <?php if($form_values['id'] == $item['id'] ): ?>   disabled="disabled" <?php endif; ?> >
        <?php print $item['taxonomy_value'] ?>
        </option>
        <?php endforeach; ?>
      </select>
      </label>
      <br />
      <label><span>Can users create subcategories?:</span>
      <select name="users_can_create_subcategories">
        <option  <?php if($form_values['users_can_create_subcategories'] == 'n' ): ?>  selected="selected" <?php endif; ?>  value="n">No</option>
        <option <?php if($form_values['users_can_create_subcategories'] == 'y' ): ?>  selected="selected" <?php endif; ?>  value="y">Yes</option>
      </select>
      </label>
      <input name="users_can_create_subcategories_user_level_required" type="hidden" value="10" />
      <label><span>Can users create posts?:</span>
      <select name="users_can_create_content">
        <option  <?php if($form_values['users_can_create_content'] == 'n' ): ?>  selected="selected" <?php endif; ?>  value="n">No</option>
        <option <?php if($form_values['users_can_create_content'] == 'y' ): ?>  selected="selected" <?php endif; ?>  value="y">Yes</option>
      </select>
      </label>
      <input name="users_can_create_content_user_level_required" type="hidden" value="10" />
      <br />
      <br />
      <br />
      <br />
      <script type="text/javascript">
	
function contentTagsGenerate(){	

var some_data = false;
	some_data = $("#content_body").val();
	some_data = some_data + $("#content_title").val();
	//some_data = some_data + $("#taxonomy_tags_csv").val();
$.post("<?php print site_url('admin/content/contentGenerateTagsForPost') ?>", {  data: some_data },
  function(data){
    $("#taxonomy_tags_csv").val('');
    $("#taxonomy_tags_csv").val(data);
  });
}



</script>
      <?php // var_dump( $form_values) ; 
	if(!empty($form_values["taxonomy_data"]['tag'])){
	foreach($form_values["taxonomy_data"]['tag'] as $temp){
		$thetags[] = $temp['taxonomy_value'];
		
	}
		$thetags = implode(', ',$thetags);
	} else {
	$thetags = false;	
	}
	
	//var_dump($thetags);
	?>
      <script type="text/javascript">
	function tags_append_csv($tag){
		$the_val = $("#taxonomy_tags_csv").val();
		
 $("#taxonomy_tags_csv").val($the_val+ ", "+ $tag);
  
 
	}
</script>
      <div id="tagCloudhiddenModalContent" style="display:none">
        <script type="text/javascript">
  $(document).ready(function(){
    $("#alphabetic_tags_tabs").tabs();
  });
  </script>
        <div id="alphabetic_tags_tabs">
          <ul>
            <li><a href="#alphabetic_tags_tabs_all">All</a></li>
            <?php $letters = $this->taxonomy_model->taxonomyTagsGetExisingTagsFirstLetters(); ?>
            <?php foreach($letters  as $letter_item): ?>
            <li><a href="#alphabetic_tags_tabs_<?php print md5($letter_item) ?>"><?php print $letter_item ?></a></li>
            <?php endforeach;  ?>
          </ul>
          <div id="alphabetic_tags_tabs_all">
            <?php $this->taxonomy_model->taxonomy_helpers_generateTagCloud("javascript:tags_append_csv('{taxonomy_value}')");  ?>
          </div>
          <?php foreach($letters  as $letter_item): ?>
          <div id="alphabetic_tags_tabs_<?php print md5($letter_item) ?>">
            <?php $this->taxonomy_model->taxonomy_helpers_generateTagCloud("javascript:tags_append_csv('{taxonomy_value}')", false,$letter_item );  ?>
          </div>
          <?php endforeach;  ?>
        </div>
        <table width="90%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><input type="button" value="Guess tags" onclick="contentTagsGenerate()" /></td>
            <td><input type="button" value="Close" onclick="tb_remove()" /></td>
          </tr>
        </table>
      </div>
      <br />
      <br />
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>Tags:</td>
          <td><a href="#TB_inline?height=500&width=505&inlineId=tagCloudhiddenModalContent&modal=false" id="tagCloudhiddenModalContent_controller"    class="thickbox"><img src="<?php print_the_static_files_url() ; ?>/icons/tag_blue_edit.png" alt=" " border="0">Edit tags</a><br />
            <textarea name="taxonomy_tags_csv" id="taxonomy_tags_csv" wrap="virtual"  style="width:400px; padding:0px; height:60px; overflow:scroll;" cols="10" rows="10"><?php print $thetags; ?></textarea></td>
        </tr>
      </table>
      <br />
      <br />
      <br />
      <div style="clear:both">
        <!-- -->
      </div>
      <input name="Save" value="Save" type="submit">
      <input name="id" type="hidden" value="<?php print $form_values['id']; ?>">
      <input name="taxonomy_type" type="hidden" value="category">
      <?php //  var_dump( $form_values) ; ?>
      <br />
      <br />
      <label>Pictures:
      <input name="picture_0" type="file" />
      </label>
      </fieldset>
    </div>
   <!-- <div id="tab2">
      <?php // include_once ('geodata_map.php') ; ?>
    </div>-->
    <div id="tab_content_body">
      <textarea name="content_body" id="content_body" rows="100" cols=""><?php print $form_values['content_body']; ?></textarea>
    </div>
    <div id="tab_advanced_options">
      <label class="lbl"><span>301 redirect link:</span>
      <input name="page_301_redirect_link" type="text" value="<?php print $form_values['page_301_redirect_link']; ?>">
      </label>
      <label class="lbl"><span>301 redirect to content id:</span>
      <input name="page_301_redirect_to_post_id" type="text" value="<?php print $form_values['page_301_redirect_to_post_id']; ?>">
      </label>
      <label class="lbl"><span>Custom category controller exclusive:</span>
      <input name="taxonomy_filename_exclusive" type="text" value="<?php print $form_values['taxonomy_filename_exclusive']; ?>">
      </label>
      <label class="lbl"><span>Custom category controller append:</span>
      <input name="taxonomy_filename" type="text" value="<?php print $form_values['taxonomy_filename']; ?>">
      </label>
      <!-- <br />
       ne trii tova
        <label>Apply filename on child categories:
          <select name="taxonomy_filename_apply_to_child">
            <option  <?php if($form_values['taxonomy_filename_apply_to_child'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
            <option  <?php if($form_values['taxonomy_filename_apply_to_child'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
          </select>
        </label>-->
      <label class="lbl"><span>Include in advanced search: </span>
      <select name="taxonomy_include_in_advanced_search">
        <option  <?php if($form_values['taxonomy_include_in_advanced_search'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
        <option  <?php if($form_values['taxonomy_include_in_advanced_search'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
      </select>
      </label>
      <br />
      <br />
      <br />
      <label class="lbl"><span>taxonomy_params:</span>
      <textarea name="taxonomy_params" cols="" rows=""><?php print $form_values['taxonomy_params']; ?></textarea>
      </label>
      <br />
      <br />
      <br />
      <label class="lbl"><span>Category name 2:</span>
      <input name="taxonomy_value2" type="text" value="<?php print $form_values['taxonomy_value2']; ?>">
      </label>
      <label class="lbl"><span>Category name 3:</span>
      <input name="taxonomy_value3" type="text" value="<?php print $form_values['taxonomy_value3']; ?>">
      </label>
      <br />
      <br />
      <br />
      <br />
    </div>
  </div>
</form>
<script type="text/javascript">
function taxonomy_categories_delete($id){
	
	
			$(function() {
			$("#deleteContentItem_dialog").dialog({
				bgiframe: true,
				resizable: false,
				height:140,
				modal: true,
				overlay: {
					backgroundColor: '#000',
					opacity: 0.5
				},
				buttons: {
					'Delete!': function() {
						
						 $(this).dialog('close');
						window.location.href='<?php print site_url('admin/content/taxonomy_categories_delete/id:'); ?>' + $id;
						
					},
					Cancel: function() {
						$(this).dialog('close');
					}
				}
			});
		});
	
	
	
	
	
	
	

}


function taxonomy_categories_select($id){

$("select[name='parent_id']").val($id);


}


</script>
<script>
  $(document).ready(function(){
    $("#categories_tree").treeview(
								   
								   
								   
								   {
				collapsed: false,
				animated: "medium",
				//control:"#sidetreecontrol",
				 persist: "cookie",
   cookieId: "navigationtree_<?php print md5(uri_string() ) ; ?>"

			}
								   
								   
								   
								   
								   
								   
								   );
  });
  </script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="categories_tree">
        <?php $link = site_url('admin/content/taxonomy_categories/category_edit:');
	$link2 = site_url('admin/content/taxonomy_categories_delete/id:');
	$link3 = site_url('admin/content/taxonomy_categories_move/direction:up/id:');
	$link4 = site_url('admin/content/taxonomy_categories_move/direction:down/id:');
 $this->firecms = get_instance();
 //$this->firecms->content_model->content_helpers_getCaregoriesUlTree(0, "<a href='$link{id}'>Edit: {taxonomy_value}</a> | <a href='javascript:taxonomy_categories_delete({id})'    >Delete</a> | <a href='javascript:taxonomy_categories_select({id})'    >Select</a>"); 
 
 
 $this->firecms->content_model->content_helpers_getCaregoriesUlTree(0, "<div>&nbsp;&nbsp;{taxonomy_value} <a href='$link{id}'>Edit</a> | <a href='javascript:taxonomy_categories_delete({id})'    >Delete</a> | <a href='javascript:taxonomy_categories_select({id})'    >Select</a> | <a href='$link3{id}'>up</a> | <a href='$link4{id}'>down</a> | id: {id}  </div>");
 
 
 
 
 ?>
      </div></td>
  </tr>
</table>
