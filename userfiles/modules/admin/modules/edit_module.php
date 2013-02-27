<?
$id =  false;
if(isset($params["data-module-id"])){
$id = 	$params["data-module-id"];
	
}
$data = array();
if($id != false){
	
	$data = get_modules_from_db('ui=any&limit=1&id='.$id);
	if(isset($data[0])){
	$data = $data[0];	
	}
}

  ?>
<? if(!empty($data)):  ?>
<? //$rand = uniqid().$data['id']; ?>
<script  type="text/javascript">



$(document).ready(function(){

	 mw.$('#module_admin_settings_form_{rand}').submit(function() {

     mw.form.post(mw.$('#module_admin_settings_form_{rand}') , '<? print site_url('api') ?>/save_settings_md', function(){

	 });

     return false;
 
 
 });

 


	 
	 mw.$('#module_uninstall_{rand}').click(function() {
       var for_module = {}
       for_module.id =  $(this).attr('data-module-id');
       $.post('<? print site_url('api') ?>/uninstall_module/', for_module, function(data) {

       });
       return false;
	 });
	 mw.$('#module_install_{rand}').click(function() {
         var for_module = {}
         for_module.for_module =  $(this).attr('data-module-name');
         $.post('<? print site_url('api') ?>/install_module/', for_module,  function(data) {

         });

		 return false;
	 });

});
</script>

<form class="admin-modules-list-form" id="module_admin_settings_form_{rand}">



  <div class="admin-modules-list-image">
  
   <span class="ico iMove mw_admin_modules_sortable_handle"></span>
  
  
    <span class="mw_module_image_holder">
      <? if(isset($data['icon'])):  ?>
        <img src="<? print $data['icon'] ?>" alt="<? if(isset($data['name'])){ print addslashes($data['name']); }; ?> icon." />
      <? endif; ?>
        <s class="mw_module_image_shadow"></s>
    </span>
    <strong class="mw_module_new">new</strong>
  </div>
  <div class="admin-modules-list-description">
    <h2><? if(isset($data['name'])):  ?><? print $data['name'] ?><? endif; ?></h2>
    <small><? print $data['module'] ?></small>
    <p><? if(isset($data['description'])):  ?><? print $data['description'] ?><? endif; ?></p>
  </div>





<?php /*   <? if(isset($data['author'])):  ?>
    author : <? print $data['author'] ?><br />
  <? endif; ?>

  <? if(isset($data['website'])):  ?>
    website : <? print $data['website'] ?><br />
  <? endif; ?>

  <? if(isset($data['help'])):  ?>
    help : <? print $data['help'] ?><br />
  <? endif; ?> */ ?>

  <input type="hidden" name="id" value="<? print $data['id'] ?>" />
  <input type="hidden" name="installed" value="<? print $data['installed'] ?>" />
  <input type="hidden" name="ui" value="<? print $data['ui'] ?>" />
  <input type="hidden" name="ui_admin" value="<? print $data['ui_admin'] ?>" />
  <input type="hidden" name="position" value="<? print $data['position'] ?>" />

  <span class="admin-modules-list-buttons">

    <a href="<? print admin_url() ?>view:modules/load_module:<? print module_name_encode($data['module']) ?>" class="mw-ui-btn"><?php _e("Settings"); ?></a>
    <? if(strval($data['installed']) != '' and intval($data['installed']) == 0): ?>
      <input class="mw-ui-btn" name="install" type="button" id="module_install_{rand}" data-module-name="<? print $data['module'] ?>" value="<?php _e("Install"); ?>">
    <? else : ?>
      <input class="mw-ui-btn" name="uninstall" type="button" id="module_uninstall_{rand}" data-module-id="<? print $data['id'] ?>" value="<?php _e("Uninstall"); ?>">
    <? endif; ?>

  </span>
</form>
<? endif; ?>
