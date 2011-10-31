<? $rand_id = md5(serialize($params)); ?>
<script type="text/javascript">
                $(document).ready(function(){
                 	$("#mw_pages_tree_form_editor<? print $rand_id ?>").accordion({
			autoHeight: false,
			clearStyle: true,
			collapsible: true,
				 
				animated: false,
				icons: { header: "ui-icon-triangle-1-w",
			headerSelected: "ui-icon-triangle-1-s" },
				navigation: true
										   
									   
		})
                });
            </script>
<?php $fr = option_get('from', $params['module_id']) ?>
<div id="mw_pages_tree_form_editor<? print $rand_id ?>">
  <h3><a href="#"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/icons/12-eye.png"  width="24" class="css_editor_accordeon_icon" />Pages tree</a></h3>
  <div>
    <div class="mw_tag_editor_item_holder">
      <table border="0" cellspacing="5" cellpadding="0" >
        <tr valign="middle">
          <td><div class="mw_tag_editor_label_wide">Main page</div>
          <select name='from' class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider" option_group="<? print $params['module_id'] ?>"   refresh_modules="content/pages_tree"   >
            <option value="">none</option>
              <?php

 CI::model('content')->content_helpers_getPagesAsUlTree(0, "<option   {removed_ids_code}  {active_code}  value='{id}'>{content_title}</option>", array($fr), 'checked="checked"', array(0) , 'disabled="disabled"' );

 ?>
            </select>
             </td>
        </tr>
       
      </table>
    </div>
  </div>
    
</div>
