<? $pages = get_content('content_type=page&subtype=dynamic&limit=1000');   ?>
<?php $posts_parent_page =  get_option('data-content-id', $params['id']); ?>

<div class="mw_simple_tabs mw_tabs_layout_simple">
	<ul style="margin: 0;" class="mw_simple_tabs_nav">
	<li><a class="active" href="javascript:;">Options</a></li>
		<li><a href="javascript:;">Skin/Template</a></li>
	</ul>
	<div class="tab">
		<label class="mw-ui-label">Show Categories From</label>

		<div class="mw-ui-select" style="width: 100%">

			<select name="data-content-id" class="mw_option_field">


      <option value="0"   <? if((0 == intval($posts_parent_page))): ?>   selected="selected"  <? endif; ?> title="None">None</option>
          <?
$pt_opts = array();
          $pt_opts['link'] = "{empty}{title}";
          $pt_opts['list_tag'] = " ";
          $pt_opts['list_item_tag'] = "option";

      $pt_opts['active_ids'] =$posts_parent_page;


$pt_opts['include_categories'] =true;
          $pt_opts['active_code_tag'] = '   selected="selected"  ';



          pages_tree($pt_opts);


          ?>
			</select>
		</div>
	</div>
	<div class="tab semi_hidden">
		<module type="admin/modules/templates"  />
	</div>
</div>