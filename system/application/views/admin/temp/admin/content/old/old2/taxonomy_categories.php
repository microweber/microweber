<!--<?php if(!empty($taxonomy_items)): ?>

<table border="1" width="100%">
  <?php // foreach($taxonomy_items as $item) : ?>
  <tr>
    <td><?php print $item['id'] ;  ?></td>
    <td><?php print $item['taxonomy_type'] ;  ?></td>
    <td><?php print $item['taxonomy_value'] ;  ?></td>
    <td><?php print $item['taxonomy_description'] ;  ?></td>
    <td><a href="<?php print $this->uri->uri_string(); ?>/category_edit:<?php print $item['id'] ;  ?>">edit</a></td>
    <td><a href="<?php print $this->uri->uri_string(); ?>/delete_category_item:<?php print $item['id'] ;  ?>">delete</a></td>
  </tr>
  <?php // endforeach ;  ?>
</table>
<?php endif; ?>-->

<br />
<br />
<br />
<?php  
 $this->firecms = get_instance();
 $criteria = array();
 $criteria['taxonomy_type'] = 'category';
 $categories =  $this->firecms->content_model->taxonomyGet($criteria); 
 
 ?>
<?php // var_dump($form_values);  ?>
<form id="catform" action="<?php print $this->uri->uri_string(); ?>" method="post" enctype="multipart/form-data">
  <div id="tabs">
    <ul class="tabs">
      <li class="ui-state-active"><a href="#tab1">Category settings</a></li>
      <li><a href="#tab2">Map settings</a></li>
    </ul>
    <div id="tab1">
      <fieldset>
        <legend> Add/Edit categories </legend>
        <?php if($form_values["media"]["pictures"][0]["urls"][96] != ''): ?>
        <img src="<?php print $form_values["media"]["pictures"][0]["urls"][96];  ?>" alt="" />
        <?php endif; ?>
        <label><span>Category name:</span>
          <input name="taxonomy_value" type="text" value="<?php print $form_values['taxonomy_value']; ?>">
        </label>
        <label><span>Category description:</span>
          <textarea name="taxonomy_description" cols="" rows=""><?php print $form_values['taxonomy_description'] ;  ?></textarea>
        </label>
        <label><span>Parent category:</span>
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
        <br />
        <label><span>Custom category controller:</span>
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
        
        <label>Include in advanced search:
          <select name="taxonomy_include_in_advanced_search">
          <option  <?php if($form_values['taxonomy_include_in_advanced_search'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
            <option  <?php if($form_values['taxonomy_include_in_advanced_search'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
            
          </select>
        </label>
        
        
        <br />
        <br />
        <br />
        <label><span>taxonomy_params:</span>
          <textarea name="taxonomy_params" cols="" rows=""><?php print $form_values['taxonomy_params']; ?></textarea>
        </label>
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
    <div id="tab2"><?php include_once ('geodata_map.php') ; ?></div>
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
				//persist: "location"
			}
								   
								   
								   
								   
								   
								   
								   );
  });
  </script>







<div id="categories_tree">
  <?php $link = site_url('admin/content/taxonomy_categories/category_edit:');
	$link2 = site_url('admin/content/taxonomy_categories_delete/id:');
	$link3 = site_url('admin/content/taxonomy_categories_move/direction:up/id:');
	$link4 = site_url('admin/content/taxonomy_categories_move/direction:down/id:');
 $this->firecms = get_instance();
 //$this->firecms->content_model->content_helpers_getCaregoriesUlTree(0, "<a href='$link{id}'>Edit: {taxonomy_value}</a> | <a href='javascript:taxonomy_categories_delete({id})'    >Delete</a> | <a href='javascript:taxonomy_categories_select({id})'    >Select</a>"); 
 
 
 $this->firecms->content_model->content_helpers_getCaregoriesUlTree(0, "<div>&nbsp;&nbsp;{taxonomy_value} <a href='$link{id}'>Edit</a> | <a href='javascript:taxonomy_categories_delete({id})'    >Delete</a> | <a href='javascript:taxonomy_categories_select({id})'    >Select</a> | <a href='$link3{id}'>up</a> | <a href='$link4{id}'>down</a>  </div>");
 
 
 
 
 ?>
</div>
