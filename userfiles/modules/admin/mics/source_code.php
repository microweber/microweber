<? $rand_id = md5(serialize($params)); ?>
<script type="text/javascript">
                $(document).ready(function(){
                 	$("#mw_email_source_code_editor<? print $rand_id ?>").accordion({
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

<div id="mw_email_source_code_editor<? print $rand_id ?>">
  <h3><a href="#"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/icons/12-eye.png"  width="24" class="css_editor_accordeon_icon" />Form settings</a></h3>
  <div>
    <div class="mw_tag_editor_item_holder">
      <table border="0" cellspacing="5" cellpadding="0" >
        <tr valign="middle">
          <td><div class="mw_tag_editor_label_wide">source code language</div>
            <input name="source_code_language" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="forms/mail_form"  value="<?php print option_get('source_code_language', $params['module_id']) ?>" /></td>
        </tr>
        <tr valign="middle">
          <td><label>source code</label>
            <br />
            <textarea name="source_code" cols=""  class="mw_option_field mw_tag_editor_textarea" refresh_modules="mics/source_code"   option_group="<? print $params['module_id'] ?>" rows="2"><?php print option_get('source_code', $params['module_id']) ?></textarea></td>
        </tr>
       
      </table>
    </div>
  </div>
</div>
