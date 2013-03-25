<?
$for = $config['module'];
$for_module_id = $params['id'];

if(get_option('data-use-from-post', $params['id']) =='y'){
 

 
 if(POST_ID != false){
	
	$params['content-id'] = POST_ID;
	 } else {
		 	$params['content-id'] = PAGE_ID;

	 }	 
}

 
if(isset($params['content-id'])){
	$for_module_id = $for_id = $params['content-id'];
	 $for = 'content';
} else {
	$for_module_id = $for_id = $params['id']; 
	 $for = 'table_modules';
}
  ?>

<div class="mw_simple_tabs mw_tabs_layout_simple">
  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="active">My pictures</a></li>
    <li><a href="javascript:;">Skin/Template</a></li>
  </ul>
  <div class="tab">

 
  <label class="mw-ui-check">
      <input type="checkbox" name="data-use-from-post" value="y" class="mw_option_field" <? if(get_option('data-use-from-post', $params['id']) =='y'): ?>   checked="checked"  <? endif; ?> data-also-reload="<? print $config['the_module'] ?>" />
      <span></span><span>Use pictures from post</span></label>

          <microweber module="pictures/admin_backend" for="<? print $for ?>" for-id="<? print $for_id ?>" >

       
  </div>
  <div class="tab"> <strong>Skin/Template</strong>
    <module type="admin/modules/templates"  />
    <microweber module="settings/list"     for_module="<? print $config['module'] ?>" for_module_id="<? print $params['id'] ?>" >
  </div>
</div>
<div class="mw_clear"></div>
<div class="vSpace"></div>
<div class="vSpace"></div>
