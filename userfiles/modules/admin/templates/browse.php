<? only_admin_access(); ?>
<script  type="text/javascript">
$(document).ready(function(){

  mw.options.form('.<? print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("Default template saved"); ?>.");
    });

 

});



modulePreview = function(el){
   mw.tools.modal.frame({
     url:el.href,
     name:el.id,
     title: 'Preview - ' + el.title
   });
}


</script>
<?
if(isset($params['for'])){
	$params['parent-module'] = $params['for'];
	$params['parent-module-id'] =  $params['for'];
}
if(!isset($params['parent-module'])){
error('parent-module is required');
	
}

if(!isset($params['parent-module-id'])){
error('parent-module-id is required');	
	
}
 $curent_module = $params['parent-module'];
 $curent_module_url = module_name_encode($params['parent-module']);
 $templates = module_templates($params['parent-module']);
//$params['type'];

$cur_template = get_option('data-template', $params['parent-module-id']);
 ?>
<?  if(is_arr( $templates)): ?>

<label class="mw-ui-label">Set default skin for the whole website</label>
<div class="mw-ui-select" style="width: 70%">
  <select name="data-template"     class="mw_option_field" option_group="<? print $params['parent-module-id'] ?>"  data-refresh="<? print $params['parent-module-id'] ?>"  >
    <option  value="default"   <? if(('default' == $cur_template)): ?>   selected="selected"  <? endif; ?>>Default</option>
    <?  foreach($templates as $item):	 ?>
    <? if((strtolower($item['name']) != 'default')): ?>
    <option value="<? print $item['layout_file'] ?>"   <? if(($item['layout_file'] == $cur_template)): ?>   selected="selected"  <? endif; ?>     > <? print $item['name'] ?> </option>
    <? endif; ?>
    <? endforeach; ?>
  </select>
</div>
<a class="mw-ui-btn mw-ui-btn-green " href="javascript:;">Get more templates</a>
<div class="mw-admin-templates-browse-holder">
  <? if(isarr($templates )): ?>
  <? $i = 1; foreach($templates  as $item): ?>
  <? if(isset($item['name'])): ?>
  <h2><? print $item['name'] ?></h2>
   <? if(isset($item['description'])): ?>
  <h4><? print $item['description'] ?></h4>
  <? endif; ?>
  

  
  <? if(isset($item['icon'])): ?>
  <img src="<? print $item['icon'] ?>" height="30" />
  <? endif; ?>
  <? if(isset($item['image'])): ?>
  <img src="<? print $item['image'] ?>" height="30" />
  <? endif; ?>
  <a onclick="modulePreview(this); return false;" title="<? print $item['name'] ?>" id="skin_num_<? print $i.md5($curent_module); ?>" href="<? print site_url('clean') ?>/preview_module:<? print ($curent_module_url) ?>/preview_module_template:<? print module_name_encode($item['layout_file']) ?>/preview_module_id:skin_num_<? print $i.md5($curent_module); ?>"  class="mw-ui-btn">Preview</a>
  
  <? //d($item); ?>

  
  
  
  <? endif; ?>
  <?  $i++; endforeach ; ?>
  <? endif; ?>
</div>
<? endif; ?>
