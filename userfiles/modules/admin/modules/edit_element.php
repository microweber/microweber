
<?php
$id =  false;
if(isset($params["data-module-id"])){
$id = 	$params["data-module-id"];
	
}
$data = array();
if($id != false){
	
	$data = mw()->layouts_manager->get('limit=1&id='.$id);
	if(isset($data[0])){
	$data = $data[0];	
	}
}

  ?>
 <?php if(!empty($data)):  ?>
 
 <?php //$rand = uniqid().$data['id']; ?>
 
 <script  type="text/javascript">

mw.require('forms.js');
 

$(document).ready(function(){
	
	 
	 
	 mw.$('#module_admin_settings_form_{rand}').submit(function() { 

 
 mw.form.post(mw.$('#module_admin_settings_form_{rand}') , '<?php print site_url('api') ?>/layouts/save', function(){
	 
	 
	// mw.reload_module('[data-type="categories"]');
	 // mw.reload_module('[data-type="pages"]');
	 });

 return false;
 
 
 });
   
 


 
   
});
</script>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 <form  id="module_admin_settings_form_{rand}">
  <?php if(isset($data['icon'])):  ?>
 <img src="<?php print $data['icon'] ?>"> <br />
<?php endif; ?>

 <?php if(isset($data['name'])):  ?>
 Name : <?php print $data['name'] ?><br />
<?php endif; ?>

 <?php if(isset($data['description'])):  ?>
 description : <?php print $data['description'] ?><br />
<?php endif; ?>

 <?php if(isset($data['author'])):  ?>
 author : <?php print $data['author'] ?><br />
<?php endif; ?>

<?php if(isset($data['website'])):  ?>
 website : <?php print $data['website'] ?><br />
<?php endif; ?>

<?php if(isset($data['help'])):  ?>
 help : <?php print $data['help'] ?><br />
<?php endif; ?>
 
 

  
  <input type="hidden" name="id" value="<?php print $data['id'] ?>" />
 installed  <input type="text" name="installed" value="<?php print $data['installed'] ?>" />
  ui  <input type="text" name="ui" value="<?php print $data['ui'] ?>" />
 position
 <input type="text" name="position" value="<?php print $data['position'] ?>" />
 
  <microweber module="categories/selector" rel="elements"  rel_id="<?php print $data['id'] ?>" >
 
 
 <input name="save" type="submit" value="save">
</form>
 
 <?php endif; ?>