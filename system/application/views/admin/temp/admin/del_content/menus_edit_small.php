<?php $form_id = date("Y-m-d H:i:s"); $form_id = md5($form_id); ?>

<script type="text/javascript"> 
        $(document).ready(function() { 
            $('#form_<?php print $form_id; ?>').ajaxForm(function() { 
               // alert("Thanks!"); 
			   
			   self.parent.menu_item_update_placeholder(<?php print $form_values['id']; ?>);
            }); 
        }); 
    </script> 


<form action="<?php print site_url('admin/content/menus_edit_small') ?>/id:<?php print $form_values['id'] ;  ?>" id="form_<?php print $form_id; ?>" method="post" enctype="multipart/form-data">
    <h1>Add/Edit menu</h1>

<table width="100%" border="0" cellspacing="0">
  <tr>
    <td>Menu title:</td>
    <td><input name="item_title" type="text" value="<?php print $form_values['item_title']; ?>"></td>
  </tr>
  <tr>
    <td>Active?:</td>
    <td><select name="is_active">
        <option  <?php if($form_values['is_active'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
        <option  <?php if($form_values['is_active'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
      </select></td>
  </tr>
  <tr>
    <td><input name="(x)close" value="Exit" type="button" onClick="self.parent.tb_remove()"></td>
    <td><input name="id" type="hidden" value="<?php print $form_values['id']; ?>"><input name="Save" value="Save" type="submit"></td>
  </tr>
</table>

  </fieldset>
</form>
