<?
if(!isset($params['parent-module'])){
error('parent-module is required');	
	
}

if(!isset($params['parent-module-id'])){
error('parent-module-id');	
	
}
 $templates = module_templates($params['parent-module']);
//$params['type'];
//d($templates);
$cur_template = option_get('data-template', $params['parent-module-id']);
 ?><?  if(is_arr( $templates)): ?>

<select name="data-template"     class="mw_option_field" option_group="<? print $params['parent-module-id'] ?>"  data-refresh="<? print $params['parent-module-id'] ?>"  >
  <option  valie="none"   <? if(('none' == $cur_template)): ?>   selected="selected"  <? endif; ?>>None</option>
  <?  foreach($templates as $item):	 ?>
  <option value="<? print $item['layout_file'] ?>"   <? if(($item['layout_file'] == $cur_template)): ?>   selected="selected"  <? endif; ?>     > <? print $item['name'] ?> </option>
  <? endforeach; ?>
</select>
 
<? //d($templates); ?>
<? endif; ?>