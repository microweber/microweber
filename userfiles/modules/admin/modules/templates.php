<?

if(!isset($params['parent-module'])){
error('parent-module is required');	
	
}

if(!isset($params['parent-module-id'])){
error('parent-module-id');	
	
}
 $templates = module_templates($params['parent-module']);
//$params['type'];

$cur_template = get_option('data-template', $params['parent-module-id']);
 ?><?  if(is_arr( $templates)): ?>
<label class="mw-ui-label">Current Skin / Template</label>
<div class="mw-ui-select" style="width: 100%"><select name="data-template"     class="mw_option_field" option_group="<? print $params['parent-module-id'] ?>"  data-refresh="<? print $params['parent-module-id'] ?>"  >

<option  value="default"   <? if(('default' == $cur_template)): ?>   selected="selected"  <? endif; ?>>Default</option>

  <?  foreach($templates as $item):	 ?>
  <? if((strtolower($item['name']) != 'default')): ?>
  <option value="<? print $item['layout_file'] ?>"   <? if(($item['layout_file'] == $cur_template)): ?>   selected="selected"  <? endif; ?>     > <? print $item['name'] ?> </option>
  <? endif; ?>
  <? endforeach; ?>
</select></div>


<label class="mw-ui-label">
    <hr>
    <small>
        Need more designs?<br>
        You can use all templates you like and change the skin.
    </small>
</label>

<a class="mw-ui-link" href="javascript:;">Browse Templates</a>

<? //d($templates); ?>
<? endif; ?>