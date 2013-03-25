
<?
$id =  false;
if(isset($params["data-module-id"])){
$id = 	$params["data-module-id"];
	
}
$data = array();
if($id != false){
	
	$data = get_elements_from_db('limit=1&id='.$id);
	if(isset($data[0])){
	$data = $data[0];	
	}
}

  ?>
 <? if(!empty($data)):  ?>
 
 <? //$rand = uniqid().$data['id']; ?>
 
 <script  type="text/javascript">

mw.require('forms.js');
 

$(document).ready(function(){
	
	 
	 
	 mw.$('#module_admin_settings_form_{rand}').submit(function() { 

 
 mw.form.post(mw.$('#module_admin_settings_form_{rand}') , '<? print site_url('api') ?>/save_settings_el', function(){
	 
	 
	// mw.reload_module('[data-type="categories"]');
	 // mw.reload_module('[data-type="pages"]');
	 });

 return false;
 
 
 });
   
 


 
   
});
</script>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 <form  id="module_admin_settings_form_{rand}">
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
 installed  <input type="text" name="installed" value="<? print $data['installed'] ?>" />
  ui  <input type="text" name="ui" value="<? print $data['ui'] ?>" />
 position
 <input type="text" name="position" value="<? print $data['position'] ?>" />
 
  <microweber module="categories/selector" rel="elements"  rel_id="<? print $data['id'] ?>" >
 
 
 <input name="save" type="submit" value="save">
</form>
 
 <? endif; ?>