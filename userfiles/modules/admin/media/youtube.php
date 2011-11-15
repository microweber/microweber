<? $rand_id = md5(serialize($params)); ?>
<script type="text/javascript">
                $(document).ready(function(){
                 	$("#mw_v_editor<? print $rand_id ?>").accordion({
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

<div id="mw_v_editor<? print $rand_id ?>">
  <h3><a href="#"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/icons/72-pin.png"  height="24" class="css_editor_accordeon_icon" />Paste youtube embed code</a></h3>
  <div>
    <div class="mw_tag_editor_item_holder">
      <table border="0" cellspacing="5" cellpadding="0" >
        <tr valign="middle">
          <td><label>Youtube video link</label>
            <input name="embed_code" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="media/youtube"  value="<?php print option_get('embed_code', $params['module_id']) ?>" /></td>
        </tr>
        <tr valign="middle">
          <td><label>Height</label>
            <input name="vid_h" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="media/youtube"  value="<?php print option_get('vid_h', $params['module_id']) ?>" /></td>
        </tr>
        <tr valign="middle">
          <td><label>Width</label>
            <input name="vid_w" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="media/youtube"  value="<?php print option_get('vid_w', $params['module_id']) ?>" /></td>
        </tr>
      </table>
    </div>
  </div>
</div>
