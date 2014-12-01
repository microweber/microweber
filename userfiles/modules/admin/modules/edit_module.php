<?php
$id =  false;
if(isset($params["data-module-id"])){
$id = 	$params["data-module-id"];

}
$data = array();
if($id != false){

	$data = mw()->modules->get('ui=any&limit=1&id='.$id);
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
		 
		 
		 var r=confirm("Are you sure you want to UNINSTALL this module?");
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

<form
    class="admin-modules-list-form <?php if(strval($data['installed']) != '' and intval($data['installed']) != 0){ print 'module-installed'; } else{ print 'module-uninstalled'; } ?> "
    id="module_admin_settings_form_<?php print $params['id']; ?>">
  <div class="admin-modules-list-form-content" <?php if(strval($data['installed']) != '' and intval($data['installed']) != 0): ?>onclick="window.location.href = '<?php print admin_url() ?>view:modules/load_module:<?php print module_name_encode($data['module']) ?>';"<?php endif; ?>>
    <div class="bgimg admin-modules-list-image" style="<?php if(isset($data['icon'])):  ?> background-image: url(<?php print $data['icon'] ?>); <?php endif; ?>" ></div>
    <div class="admin-modules-list-description">
      <h2 title="<?php print $data['module'] ?>">
        <?php if(isset($data['name'])):  ?>
        <?php print($data['name']); ?>
        <?php endif; ?>
      </h2>
    </div>
    <input type="hidden" name="id" value="<?php print $data['id'] ?>" />
    <input type="hidden" name="installed" value="<?php print $data['installed'] ?>" />
    <input type="hidden" name="ui" value="<?php print $data['ui'] ?>" />
    <input type="hidden" name="ui_admin" value="<?php print $data['ui_admin'] ?>" />
    <input type="hidden" name="position" value="<?php print $data['position'] ?>" />
  </div>
  <?php if(strval($data['installed']) != '' and intval($data['installed']) != 0): ?>
  <input
            class="mw-ui-btn mw-ui-btn-medium module-uninstall-btn tip"
            data-tip="<?php _e("Uninstall"); ?>"
            name="uninstall" type="button" id="module_uninstall_<?php print $params['id']; ?>" data-module-name="<?php print $data['module'] ?>" data-module-id="<?php print $data['id'] ?>" value="đ" />
  
  <!-- <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert module-ctrl-btn"><?php _e("Open"); ?></span>-->
  
  <?php else:  ?>
  <input  class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert module-ctrl-btn" name="install" type="button" id="module_install_<?php print $params['id']; ?>" data-module-name="<?php print $data['module'] ?>" data-module-id="<?php print $data['id'] ?>" value="<?php _e("Install"); ?>" />
  <?php endif; ?>
</form>
<?php endif; ?>
