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
<? $rand = uniqid().$data['id']; ?>
<script  type="text/javascript">

mw.require('forms.js');
 

$(document).ready(function(){
	
	 
	 
	 mw.$('#module_admin_settings_form_<? print $rand ?>').submit(function() { 

 
 mw.form.post(mw.$('#module_admin_settings_form_<? print $rand ?>') , '<? print site_url('api') ?>/save_settings_md', function(){
	 
	 
	// mw.reload_module('[data-type="categories"]');
	 // mw.reload_module('[data-type="pages_menu"]');
	 });

 return false;
 
 
 });
   
 


	 
	 mw.$('#module_uninstall_<? print $rand ?>').click(function() { 

 
 var for_module = {}
 for_module.id =  $(this).attr('data-module-id');
 
 
 $.post('<? print site_url('api') ?>/uninstall_module/', for_module, function(data) {
   
});


 
 
 

		 return false;
		 
		 
		 });
		 
		 
		 
		  
	 mw.$('#module_install_<? print $rand ?>').click(function() { 

 
 
 var for_module = {}
 for_module.for_module =  $(this).attr('data-module-name');
// for_module = url
 $.post('<? print site_url('api') ?>/install_module/', for_module,  function(data) {
   
});


 
 
 

		 return false;
		 
		 
		 });





 
   
});
</script>

<form  id="module_admin_settings_form_<? print $rand ?>">
  <? if(isset($data['icon'])):  ?>
  <img src="<? print $data['icon'] ?>"> <br />
  <? endif; ?>
  <? if(isset($data['name'])):  ?>
  Name : <? print $data['name'] ?><br />
  <? endif; ?>
  <? if(isset($data['description'])):  ?>
  description : <? print $data['description'] ?><br />
  <? endif; ?>
  <? if(isset($data['author'])):  ?>
  author : <? print $data['author'] ?><br />
  <? endif; ?>
  <? if(isset($data['website'])):  ?>
  website : <? print $data['website'] ?><br />
  <? endif; ?>
  <? if(isset($data['help'])):  ?>
  help : <? print $data['help'] ?><br />
  <? endif; ?>
  <input type="hidden" name="id" value="<? print $data['id'] ?>" />
  installed
  <input type="text" name="installed" value="<? print $data['installed'] ?>" />
  ui
  <input type="text" name="ui" value="<? print $data['ui'] ?>" />
  position
  <input type="text" name="position" value="<? print $data['position'] ?>" />
  <microweber module="categories/selector" to_table="table_modules"  to_table_id="<? print $data['id'] ?>" >
  <a href="<? print curent_url() ?>/load_module:<? print encode_var( $data['module']) ?>">Open</a>
  <input name="save" type="submit" value="save">
  <? if(strval($data['installed']) != '' and intval($data['installed']) == 0): ?>
  <input name="install" type="button" id="module_install_<? print $rand ?>" data-module-name="<? print $data['module'] ?>" value="install">
  <? else : ?>
  <input name="uninstall" type="button" id="module_uninstall_<? print $rand ?>" data-module-id="<? print $data['id'] ?>" value="uninstall">
  <? endif; ?>
</form>
<? endif; ?>
