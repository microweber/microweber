<?php

 $criteria = array();
 $criteria['taxonomy_type'] = 'category';
 $categories =  $this->taxonomy_model->taxonomyGet($criteria, false, true, true); 
 
 ?>
<?php $categories_content_types = $this->core_model->optionsGetByKeyAsArray('content_types'); 	 ?>

<form id="edit_categories_ajax_form" method="post" enctype="multipart/form-data" onsubmit="return false;">
  <div class="modal_edit_save">  <a href="javascript:;" onClick="edit_category_do_ajax_save(true)" class="btn"><span>Save and close</span></a>  <a href="javascript:;" onClick="edit_category_do_ajax_save(false)" class="btn"><span>Save</span></a> </div>
  <input name="saving" type="hidden" value="yes">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><div class="the_edit_categories_ajax_container_accordion">
          <h3><a href="#">Basic settings</a></h3>
          <div>
            <label class="lbl">Category name:</label>
            <input style="width: 200px" name="taxonomy_value" type="text" value="<?php print $form_values['taxonomy_value']; ?>">
            <label class="lbl">Parent category:</label>
            <select name="parent_id">
              <option value="">&nbsp;None&nbsp;</option>
              <?php foreach($categories as $item) : ?>
              <option value="<?php print $item['id'] ?>" <?php if($form_values['parent_id'] == $item['id'] ): ?>  selected="selected" <?php endif; ?>  <?php if($form_values['id'] == $item['id'] ): ?>   disabled="disabled" <?php endif; ?> >
              &nbsp; <?php print $item['taxonomy_value'] ?> &nbsp;
              </option>
              <?php endforeach; ?>
            </select>
            
            
            <br />
<br />
  <label class="lbl">Category text</label>
            <textarea name="content_body" class="TaxEditor"  id="category_editor_<?php print $form_values['id']; ?>" rows="10" cols="100"><?php print $form_values['content_body']; ?></textarea>

            <div>&nbsp;</div>
            <a href="javascript:category_editor_setup('category_editor_<?php print $form_values['id']; ?>');">Add/Remove editor</a>

             
            <br />
<br />
 <label class="lbl">Pictures:</label>
            <input name="picture_0" type="file" />
            
            <?php if($form_values["media"]["pictures"][0]["urls"][96] != ''): ?>
            <img src="<?php print $form_values["media"]["pictures"][0]["urls"][96];  ?>" alt="" />
            <?php endif; ?>
           
            
            
          </div>
          <h3><a href="#">Advanced settings</a></h3>
          <div>
            
             <label class="lbl">Category content type:</label>
            <select name="taxonomy_content_type">
              <?php foreach($categories_content_types as $item) : ?>
              <option value="<?php print $item ?>" <?php if($form_values['taxonomy_content_type'] == $item ): ?>  selected="selected" <?php endif; ?>>&nbsp;<?php print $item ?>&nbsp;</option>
              <?php endforeach; ?>
            </select>
            <br />

            <label class="lbl">SILO linking keywords:<br />
              <small><i>(Comma separated)</i></small></label>
            <textarea style="width: 200px" name="taxonomy_silo_keywords" cols="5" rows="2"><?php print $form_values['taxonomy_silo_keywords'] ;  ?></textarea>
            <label class="lbl">Category description:</label>
            <textarea style="width: 200px" name="taxonomy_description" cols="5" rows="5"><?php print $form_values['taxonomy_description'] ;  ?></textarea>
            <label class="lbl">Can users create subcategories?:</label>
            <select name="users_can_create_subcategories">
              <option  <?php if($form_values['users_can_create_subcategories'] == 'n' ): ?>  selected="selected" <?php endif; ?>  value="n">&nbsp;No&nbsp;</option>
              <option <?php if($form_values['users_can_create_subcategories'] == 'y' ): ?>  selected="selected" <?php endif; ?>  value="y">&nbsp;Yes&nbsp;</option>
            </select>
            <input name="users_can_create_subcategories_user_level_required" type="hidden" value="10" />
            <label class="lbl">Can users create posts?:</label>
            <select name="users_can_create_content">
              <option  <?php if($form_values['users_can_create_content'] == 'n' ): ?>  selected="selected" <?php endif; ?>  value="n">&nbsp;No&nbsp;</option>
              <option <?php if($form_values['users_can_create_content'] == 'y' ): ?>  selected="selected" <?php endif; ?>  value="y">&nbsp;Yes&nbsp;</option>
            </select>
            <input name="users_can_create_content_user_level_required" type="hidden" value="10" />
            <input name="Save" value="Save" type="submit">
            <input name="id" type="hidden" value="<?php print $form_values['id']; ?>">
            <input name="taxonomy_type" type="hidden" value="category">
            <?php //  var_dump( $form_values) ; ?>
           
            <div style="height: 10px;overflow: hidden;clear: both">&nbsp;</div>
          </div>
          <h3><a href="#">Even more advanced settings</a></h3>
          <div>
          
            <label class="lbl">301 redirect link:</label>
            <input style="width: 200px" name="page_301_redirect_link" type="text" value="<?php print $form_values['page_301_redirect_link']; ?>">
            <label class="lbl">301 redirect to content id:</label>
            <input style="width: 200px" name="page_301_redirect_to_post_id" type="text" value="<?php print $form_values['page_301_redirect_to_post_id']; ?>">
            <label class="lbl">Custom category controller exclusive: </label>
            <input style="width: 200px" name="taxonomy_filename_exclusive" type="text" value="<?php print $form_values['taxonomy_filename_exclusive']; ?>">
            <label class="lbl">Custom category controller append:</label>
            <input style="width: 200px" name="taxonomy_filename" type="text" value="<?php print $form_values['taxonomy_filename']; ?>">
            
            <!-- <br />
       ne trii tova
        <label>Apply filename on child categories:
          <select name="taxonomy_filename_apply_to_child">
            <option  <?php if($form_values['taxonomy_filename_apply_to_child'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
            <option  <?php if($form_values['taxonomy_filename_apply_to_child'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
          </select>
        </label>-->
          <!--  <label class="lbl">Include in advanced search:</label>
            <select name="taxonomy_include_in_advanced_search">
              <option  <?php if($form_values['taxonomy_include_in_advanced_search'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">&nbsp;yes&nbsp;</option>
              <option  <?php if($form_values['taxonomy_include_in_advanced_search'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">&nbsp;no&nbsp;</option>
            </select>-->
         <!--   <br />
            <br />
            <br />
            <label class="lbl">Taxonomy params:</label>
            <textarea style="width:200px" name="taxonomy_params" cols="5" rows="5"><?php print $form_values['taxonomy_params']; ?></textarea>
            <label class="lbl">Category name 2:</label>
            <input style="width: 200px" name="taxonomy_value2" type="text" value="<?php print $form_values['taxonomy_value2']; ?>">
            <label class="lbl">Category name 3:</label>
            <input style="width: 200px" name="taxonomy_value3" type="text" value="<?php print $form_values['taxonomy_value3']; ?>">-->
          </div>
        </div></td>
    </tr>
  </table>
</form>
