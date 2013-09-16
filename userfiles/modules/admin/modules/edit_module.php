<?php
$id =  false;
if(isset($params["data-module-id"])){
$id = 	$params["data-module-id"];

}
$data = array();
if($id != false){

	$data = mw('module')->get('ui=any&limit=1&id='.$id);
	if(isset($data[0])){
	$data = $data[0];
	}
}

  ?>
<?php if(!empty($data)):  ?>
<?php //$rand = uniqid().$data['id']; ?>
<script  type="text/javascript">



$(document).ready(function(){

	 mw.$('#module_admin_settings_form_<?php print $params['id']; ?>').submit(function() {

     mw.form.post(mw.$('#module_admin_settings_form_<?php print $params['id']; ?>') , '<?php print site_url('api') ?>/module/save', function(){

	 });

     return false;


 });




	   mw.$('#module_uninstall_<?php print $params['id']; ?>').unbind('click');
	 mw.$('#module_uninstall_<?php print $params['id']; ?>').click(function() {
		 
		 
		 var r=confirm("Are you sure you want to uninstall this module?");
if (r==true)
  {
  
   var for_module = {}
       for_module.id =  $(this).attr('data-module-id');
       $.post('<?php print site_url('api') ?>/uninstall_module/', for_module, function(data) {
$('#module_uninstall_<?php print $params['id']; ?>').hide();
$('#module_install_<?php print $params['id']; ?>').show();
$('#module_open_<?php print $params['id']; ?>').hide();
  mw.notification.success("Module uninstalled");

       });
       return false;
  
  
  
  }
		 
		 
      
	   
	   
	   
	   
	   
	 });
	  mw.$('#module_install_<?php print $params['id']; ?>').unbind('click');
	 mw.$('#module_install_<?php print $params['id']; ?>').click(function() {
		   mw.notification.success("Installing... please wait");

         var for_module = {}
         for_module.for_module =  $(this).attr('data-module-name');
         $.post('<?php print site_url('api') ?>/install_module/', for_module,  function(data) {
$('#module_install_<?php print $params['id']; ?>').hide();

$('#module_uninstall_<?php print $params['id']; ?>').show();
$('#module_open_<?php print $params['id']; ?>').show();
  mw.notification.success("Module is installed");

         });

		 return false;
	 });


	 mw.$('#module_update_<?php print $params['id']; ?>').unbind('click');
	  mw.$('#module_update_<?php print $params['id']; ?>').click(function() {
       //  var for_module = {}




       var  for_module =  $(this).attr('data-module-name');
	   	     mw.notification.warning("Installing update for module: "+for_module + '');

         $.post('<?php print admin_url() ?>view:modules?add_module='+for_module,   function(data) {
  mw.notification.success("New update for module <b>"+for_module + '</b> is installed');

         });

		 return false;
	 });

});
</script>

<form class="admin-modules-list-form" id="module_admin_settings_form_<?php print $params['id']; ?>">
	<div class="admin-modules-list-image"> <span class="ico iMove mw_admin_modules_sortable_handle"></span> <span class="mw_module_image_holder">
		<?php if(isset($data['icon'])):  ?>
		<img src="<?php print $data['icon'] ?>" alt="<?php if(isset($data['name'])){ print addslashes($data['name']); }; ?> icon." /> <s class="mw_module_image_shadow"></s>
		<?php endif; ?>
		</span> </div>
	<div class="admin-modules-list-description">
		<h2 title="<?php print $data['module'] ?>">
			<?php if(isset($data['name'])):  ?>
			<?php _e($data['name']); ?>
			<?php endif; ?>
		</h2>
		<?php if(isset($data['description'])):  ?>
		<small title="<?php print addslashes(mw('format')->limit($data['description'],1200)); ?>"> <?php print mw('format')->limit($data['description'],120); ?> </small>
		<?php endif; ?>
		<p> </p>
	</div>
	<?php /*   <?php if(isset($data['author'])):  ?>
    author : <?php print $data['author'] ?><br />
  <?php endif; ?>

  <?php if(isset($data['website'])):  ?>
    website : <?php print $data['website'] ?><br />
  <?php endif; ?>

  <?php if(isset($data['help'])):  ?>
    help : <?php print $data['help'] ?><br />
  <?php endif; ?> */ ?>
	<input type="hidden" name="id" value="<?php print $data['id'] ?>" />
	<input type="hidden" name="installed" value="<?php print $data['installed'] ?>" />
	<input type="hidden" name="ui" value="<?php print $data['ui'] ?>" />
	<input type="hidden" name="ui_admin" value="<?php print $data['ui_admin'] ?>" />
	<input type="hidden" name="position" value="<?php print $data['position'] ?>" />
	<span class="admin-modules-list-buttons"> <a id="module_open_<?php print $params['id']; ?>" href="<?php print admin_url() ?>view:modules/load_module:<?php print module_name_encode($data['module']) ?>" class="mw-ui-btn">
	<?php _e("Open"); ?>
	</a>
	<input <?php if(strval($data['installed']) != '' and intval($data['installed']) != 0): ?> style="display:none" <?php endif; ?> class="mw-ui-btn" name="install" type="button" id="module_install_<?php print $params['id']; ?>" data-module-name="<?php print $data['module'] ?>" value="<?php _e("Install"); ?>" />
	<input  <?php if(strval($data['installed']) != '' and intval($data['installed']) == 0): ?> style="display:none" <?php endif; ?> class="mw-ui-btn" name="uninstall" type="button" id="module_uninstall_<?php print $params['id']; ?>" data-module-id="<?php print $data['id'] ?>" value="<?php _e("Uninstall"); ?>" />
	<?php if(strval($data['installed']) != '' and intval($data['installed']) == 0): ?>
	<?php else : ?>
	<?php endif; ?>
	</span>
</form>
<?php endif; ?>
