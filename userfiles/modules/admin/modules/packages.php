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
 });
  
 }

 function save_new_package_form(){
 mw.form.post('#add-package-form-<?php print $params['id'] ?>', '<?php print api_link('Packages/save_patch'); ?>',
			function(msg) {
mw.notification.msg(this);
 return false;
			});
            return false;
 }


</script>
<?php $required_packages = mw()->packages->get_required(); ?>

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
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>
<form  onSubmit="return save_new_package_form();" id="add-package-form-<?php print $params['id'] ?>">
  <input type="text" class="mw-ui-field mw-ui-field-medium" name="require_name">
  <input type="text" class="mw-ui-field mw-ui-field-medium" name="require_version">
  <button class="mw-ui-btn mw-ui-btn-medium" type="submit">Save config</button>
  <button type="button" onClick="apply_new_packages()">apply_new_packages</button>
</form>
