<?php if(!empty($data)):  ?>
<?php $rand = uniqid().$data['id']; ?>
<script  type="text/javascript">

$(document).ready(function(){
	 mw.$('#module_admin_settings_form_<?php echo $rand;?>').submit(function() {
     mw.form.post(mw.$('#module_admin_settings_form_<?php echo $rand;?>') , '<?php print site_url('api') ?>/module/save', function(){
	 });
     return false;
 });

	 mw.$('#module_uninstall_<?php echo $rand;?>').click(function() {
       var for_module = {}
       for_module.id =  $(this).attr('data-module-id');
       $.post('<?php print site_url('api') ?>/uninstall_module/', for_module, function(data) {

       });
       return false;
	 });
	 mw.$('#module_install_<?php echo $rand;?>').click(function() {
         var for_module = {}
         for_module.for_module =  $(this).attr('data-module-name');
         $.post('<?php print site_url('api') ?>/install_module/', for_module,  function(data) {
         });
		 return false;
	 });
});
</script>
<form class="admin-modules-list-form" id="module_admin_settings_form_<?php echo $rand;?>">
  <div class="admin-modules-list-image">
    <?php if(isset($data['icon'])):  ?>
    <img src="<?php print $data['icon'] ?>" alt="<?php if(isset($data['name'])){ print addslashes($data['name']); }; ?> icon." />
    <?php endif; ?>
    </div>
  <div class="admin-modules-list-description">
    <h2>
      <?php if(isset($data['name'])):  ?>
      <?php print $data['name'] ?>
      <?php endif; ?>
    </h2>
    <small><?php print $data['module'] ?></small>
      <?php if(isset($data['description'])):  ?>
       <p>
      <?php print $data['description'] ?>
      </p>
      <?php endif; ?>
    <a href="<?php print admin_url() ?>view:modules/add_module:<?php print module_name_encode($data['module']) ?>" class="mw-ui-btn">
    <?php _e("Install"); ?>
    </a></div>
</form>
<?php endif; ?>
