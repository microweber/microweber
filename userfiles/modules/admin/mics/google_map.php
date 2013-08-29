<?php $rand_id = md5(serialize($params)); ?>
<script type="text/javascript">
                $(document).ready(function(){
                 	$("#mw_map_editor<?php print $rand_id ?>").accordion({
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

<div id="mw_map_editor<?php print $rand_id ?>">
  <h3><a href="#"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/icons/72-pin.png"  height="24" class="css_editor_accordeon_icon" />Map settings</a></h3>
  <div>
    <div class="mw_tag_editor_item_holder">
      <table border="0" cellspacing="5" cellpadding="0" >
        <tr valign="middle">
          <td><div class="mw_tag_editor_label_wide">Map address</div>
            <input name="map_address" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider" option_group="<?php print $params['module_id'] ?>" type="text" refresh_modules="mics/google_map"  value="<?php print get_option('map_address', $params['module_id']) ?>" /></td>
        </tr>
        <tr valign="middle">
          <td><label>Map zoom</label>
            <input name="map_zoom" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider" option_group="<?php print $params['module_id'] ?>" type="text" refresh_modules="mics/google_map"  value="<?php print get_option('map_zoom', $params['module_id']) ?>" /></td>
        </tr>
        
         <tr valign="middle">
          <td><label>Map height</label>
            <input name="map_h" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider" option_group="<?php print $params['module_id'] ?>" type="text" refresh_modules="mics/google_map"  value="<?php print get_option('map_h', $params['module_id']) ?>" /></td>
        </tr>
        
          <tr valign="middle">
          <td><label>Map width</label>
            <input name="map_w" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider" option_group="<?php print $params['module_id'] ?>" type="text" refresh_modules="mics/google_map"  value="<?php print get_option('map_w', $params['module_id']) ?>" /></td>
        </tr>
      </table>
    </div>
  </div>
</div>
