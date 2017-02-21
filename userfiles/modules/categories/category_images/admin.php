<?php  

    $selected_category = get_option('fromcategory', $params['id']);
    $show_category_header = get_option('show_category_header', $params['id']);


?>

<div class="module-live-edit-settings">
  <style type="text/css" scoped="scoped">

        #parentcat .depth-1{
          padding-left: 10px;
        }
        #parentcat .depth-2{
          padding-left: 20px;
        }

        #parentcat .depth-3{
          padding-left: 30px;
        }

        #parentcat .depth-4{
          padding-left: 40px;
        }

    </style>
  <?php  $trees = get_categories('limit=1000&parent_id=0&rel=content');  ?>
  <label class="mw-ui-label">Select parent category</label>
  <select name="fromcategory" class="mw_option_field mw-ui-field w100" id="parentcat">
    <option  <?php if((0 == intval($selected_category))): ?>   selected="selected"  <?php endif; ?>>
    <?php _e("None"); ?>
    </option>
    <option  <?php if(('current' == $selected_category)): ?>   selected="selected"  <?php endif; ?> value="current">
    <?php _e("Current"); ?>
    </option>
    <?php
  

		if ($trees != false and is_array($trees) and !empty($trees)) {
			foreach ($trees as $cat) {
				$cat_params =$params;
				$pt_opts = array();
                $pt_opts['link'] = "{title}";
                $pt_opts['list_tag'] = " ";
                $pt_opts['list_item_tag'] = "option";
				$pt_opts['parent'] =$cat['id'];
				$pt_opts['include_first'] = 1;
				category_tree($pt_opts);
			}
		}
 
?>
  </select>
  
  
 
  
  
  
</div>
<input type="hidden" name="settings" id="settingsfield" value="" class="mw_option_field" />
