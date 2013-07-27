<?php //$rand = uniqid(); ?>
<?php $pages = get_content('content_type=page&limit=1000');   ?>
<?php $posts_parent_page =  mw('option')->get('data-parent', $params['id']); ?>
<?php $posts_maxdepth =  mw('option')->get('maxdepth', $params['id']); ?>
<?php $include_categories =  mw('option')->get('include_categories', $params['id']); ?>


<div class="mw_simple_tabs mw_tabs_layout_simple">
	<ul style="margin: 0;" class="mw_simple_tabs_nav">
	<li><a class="active" href="javascript:;"><?php _e("Options"); ?></a></li>
		<li><a href="javascript:;"><?php _e("Skin/Template"); ?></a></li>
	</ul>
	<div class="tab">
		<label class="left mw-ui-label"><?php _e("Pages & Sub-Pages From"); ?></label>
		<label class="right mw-ui-label"><?php _e("Show Categories from page"); ?></label>
		<div class="left mw-ui-select" style="width: 205px;">
			<select name="data-parent" class="mw_option_field"  >
				<option  valie="0"   <?php if((0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?>><?php _e("None"); ?></option>


        <?php
          $pt_opts = array();
          $pt_opts['link'] = "{empty}{title}";
          $pt_opts['list_tag'] = " ";
          $pt_opts['list_item_tag'] = "option";

          $pt_opts['active_ids'] =$posts_parent_page;

          $pt_opts['active_code_tag'] = '   selected="selected"  ';

          mw('content')->pages_tree($pt_opts);


          ?>

			</select>
		</div>
		<span class="left label-arrow" style="margin-left: 45px;"></span>
		<div class="right mw-ui-select" style="width: 75px; min-width: 0;">
        	<select name="include_categories" class="mw_option_field">
        		<option value="y"  <?php if('y'==  $include_categories): ?>   selected="selected"  <?php endif; ?> ><?php _e("Yes"); ?></option>
	        	<option value="n"  <?php if ('y'!= $include_categories): ?>   selected="selected"  <?php endif; ?> ><?php _e("No"); ?></option>
        	</select><br />
		</div>
		<div class="mw_clear vSpace"></div>
		<label class="mw-ui-label"><?php _e("Max depth"); ?></label>
		<div class="left mw-ui-select" style="width: 100px; min-width: 0;">
        	<select name="maxdepth" class="mw_option_field">
        		<option value="none" selected><?php _e("Default"); ?></option>
        		<?php for($i = 1; $i<10; $i++): ?>
				<option value="<?php print $i ?>" <?php if(($i == $posts_maxdepth)): ?>   selected="selected"  <?php endif; ?>> <?php print $i ?></option>
				<?php endfor; ?>
        	</select><br />
		</div><br /><br />
	</div>
	<div class="tab semi_hidden">
		<module type="admin/modules/templates"  />
	</div>
</div>
