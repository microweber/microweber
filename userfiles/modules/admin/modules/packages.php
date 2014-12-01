<?php only_admin_access(); ?>
<script  type="text/javascript">
    mw.require('forms.js', true);
</script>
<script type="text/javascript">


 function apply_new_packages(){
$.ajax({
  url: "<?php print api_link('Packages/apply_patch'); ?>"
}).done(function(msg) {
	
	
	
	
	mw.notification.msg(msg);
	if(typeof(msg.try_again) != 'undefined'){
		apply_new_packages();
	}
 });
  
 }
 function prepare_new_packages(){
$.ajax({
  url: "<?php print api_link('Packages/prepare_patch'); ?>"
}).done(function(msg) {
	//mw.notification.msg(msg,30000);
		if(typeof(msg.message) != undefined){
					$('#remote_patch_log').html(msg.message);
				}
 }).fail(function(msg) {
	 prepare_new_packages()
 });
  
 }
 function save_new_package_form(){
 mw.form.post('#add-package-form-<?php print $params['id'] ?>', '<?php print api_link('Packages/save_patch'); ?>',
			function(msg) {
			
				
 mw.notification.msg(this);
 reload_changes();
 return false;
			});
            return false;
 }
function remove_patch_item($key){
	$.post( "<?php print api_link('Packages/save_patch'); ?>", { require_name: $key, require_version: "delete" })
  .done(function( msg ) {
  // mw.notification.msg(this);
   reload_changes();
  });
	
}
function reload_changes(){
	mw.reload_module('#<?php print $params['id'] ?>');
	
}
</script>
<?php $required_packages = mw()->packages->get_required(); ?>
<?php $patch =  mw()->packages->get_patch_require(); ?>
<pre id="remote_patch_log"></pre>
<table width="100%" cellspacing="0" cellpadding="0" class="mw-ui-table">
  <thead>
    <tr>
      <th>Package </th>
      <th>Version</th>
      <th> </th>
    </tr>
  </thead>
  <tbody>
    <?php if(!empty($required_packages)): ?>
    <tr>
      <?php foreach($required_packages as $key=>$val): ?>
      <td><?php print $key; ?></td>
      <td><?php print $val; ?></td>
       <td></td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    
      <?php if(!empty($patch)): ?>
      <thead>
    <tr>
      <th>Update Patch </th>
      <th>Version</th>
      <th> </th>
    </tr>
  </thead>
    <tr>
      <?php foreach($patch as $key=>$val): ?>
      <td><?php print $key; ?></td>
      <td><?php print $val; ?></td>
      <td><button type="button" onClick="remove_patch_item('<?php print $key; ?>')">x</button>
</td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    
    
  </tbody>
</table>
<form  onSubmit="return save_new_package_form();" id="add-package-form-<?php print $params['id'] ?>">
  <input type="text" class="mw-ui-field mw-ui-field-medium" name="require_name">
  <input type="text" class="mw-ui-field mw-ui-field-medium" name="require_version">
  <button class="mw-ui-btn mw-ui-btn-medium" type="submit">Save config</button>
    <button type="button" onClick="prepare_new_packages()">prepare_new_packages</button>

  <button type="button" onClick="apply_new_packages()">apply_new_packages</button>
</form>
