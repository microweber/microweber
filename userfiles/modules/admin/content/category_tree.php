



<span class="mw_sidebar_module_box_title">Settings</span>
<div class="mw_admin_rounded_box">
  <div class="mw_admin_box_padding">
    <table width="100%" border="0" cellspacing="4" cellpadding="0">
      <tr>
        <td colspan="2"><label>Title</label>
          <input name="title" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="content/category_tree"  value="<?php print option_get('title', $params['module_id']) ?>" />
          </td>
      </tr>
      <tr>
        <td colspan="2"><label>Description</label>
          <textarea name="description" cols=""  class="mw_option_field" refresh_modules="content/category_tree"   option_group="<? print $params['module_id'] ?>" rows="2"><?php print option_get('description', $params['module_id']) ?></textarea></td>
      </tr>
      
       
      <tr>
        <td></td>
        <td>&nbsp;</td>
      </tr>
    </table>
 
  </div>
</div>



   
   
 
   <? p($params); ?>
   
 
   
   <? //category_tree($params) ?>
 