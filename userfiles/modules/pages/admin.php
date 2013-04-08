<? //$rand = uniqid(); ?>
<? $pages = get_content('content_type=page&limit=1000');   ?>
<?php $posts_parent_page =  get_option('data-parent', $params['id']); ?>
<?php $posts_maxdepth =  get_option('maxdepth', $params['id']); ?>
<?php $include_categories =  get_option('include_categories', $params['id']); ?>


<div id="tabsDEMO" class="mw_simple_tabs mw_tabs_layout_simple">
	<ul style="margin: 0;" class="mw_simple_tabs_nav">
	<li><a class="active" href="javascript:;">Options</a></li>
		<li><a href="javascript:;">Skin/Template</a></li>
	</ul>
	<div class="tab">
		<label class="left mw-ui-label">Pages & Sub-Pages From</label>
		<label class="right mw-ui-label">Show Categories from page</label>
		<div class="left mw-ui-select" style="width: 205px;">
			<select name="data-parent" class="mw_option_field"  >
				<option  valie="0"   <? if((0 == intval($posts_parent_page))): ?>   selected="selected"  <? endif; ?>>None</option>
				<?  foreach($pages as $item):	 ?>
				<option value="<? print $item['id'] ?>"   <? if(($item['id'] == $posts_parent_page)): ?>   selected="selected"  <? endif; ?>     > <? print $item['title'] ?> </option>
				<? endforeach; ?>
			</select>
		</div>
		<span class="left label-arrow" style="margin-left: 45px;"></span>
		<div class="right mw-ui-select" style="width: 75px; min-width: 0;">
        	<select name="include_categories" class="mw_option_field">
        		<option value="y"  <? if('y'==  $include_categories): ?>   selected="selected"  <? endif; ?>   selected>Yes</option>
	        	<option value="n"  <? if ('y'!= $include_categories): ?>   selected="selected"  <? endif; ?>  selected>No</option>
        	</select><br />
		</div>
		<div class="mw_clear vSpace"></div>
		<label class="mw-ui-label">Max depth</label>
		<div class="left mw-ui-select" style="width: 75px; min-width: 0;">
        	<select name="maxdepth" class="mw_option_field">
        		<option value="none" selected>Default</option>
        		<? for($i = 1; $i<10; $i++): ?>
				<option value="<? print $i ?>" <? if(($i == $posts_maxdepth)): ?>   selected="selected"  <? endif; ?>     > <? print $i ?></option>
				<? endfor; ?>
        	</select><br />
		</div><br /><br />
	</div>
	<div class="tab semi_hidden">
		<module type="admin/modules/templates"  />
	</div>
</div>
