<?php only_admin_access(); ?>
<?php $posts_parent_page = get_option('data-parent', $params['id']); ?>



<label class="right mw-ui-label">
			Choose page
		</label>
	
			<select name="data-parent" class="mw-ui-field mw-ui-field-big w100 mw_option_field">
				<option
                    valie="0"   <?php if ((0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?>>
				<?php _e("None"); ?>
				</option>
				<?php
                $pt_opts = array();
                $pt_opts['link'] = "{empty}{title}";
                $pt_opts['list_tag'] = " ";
                $pt_opts['list_item_tag'] = "option";

                $pt_opts['active_ids'] = $posts_parent_page;

                $pt_opts['active_code_tag'] = '   selected="selected"  ';

                pages_tree($pt_opts);


                ?>
				<?php if (defined('PAGE_ID')): ?>
				<option value="<?php print PAGE_ID; ?>">[use current page]</option>
				<?php endif; ?>
			</select>
            
         <br><br>
            

<module type="custom_fields" view="admin" content_id="<?php print $posts_parent_page; ?>" />