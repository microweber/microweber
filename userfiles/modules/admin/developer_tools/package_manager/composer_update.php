<?php
if (!user_can_access('module.marketplace.index')) {
    return;
}
?>
<script type="text/javascript">
    mw.require('forms.js', true);
</script>
<script type="text/javascript">
    function apply_new_packages() {

		var url = "<?php print api_link('mw_composer_run_update'); ?>";

        $.ajax({
            url: url,
			beforeSend: function(){
          		$("#run_composer_button_lock").attr("disabled", "disabled");
   			},

			error: function (request, status, error) {
			$('#remote_patch_log').append('...');
			  setTimeout(apply_new_packages, 3000);
			}
        }).done(function (resp) {
			$("#run_composer_button_lock").removeAttr("disabled");

			if (typeof(resp.message) != 'undefined') {
               $('#remote_patch_log').html(resp.message);
            }

		    if (typeof(resp.try_again) != 'undefined') {
                apply_new_packages();
            }
			if (typeof(resp.move_vendor) != 'undefined') {
                replace_old_packages();
            }
        });

    }

	 function replace_old_packages(mode) {

		var url = "<?php print api_link('mw_composer_replace_vendor_from_cache'); ?>";

        $.ajax({
            url: url,
			beforeSend: function(){
          		$("#run_composer_button_lock").attr("disabled", "disabled");
   			},

			error: function (request, status, error) {
			$('#remote_patch_log').append('...');
			  setTimeout(replace_old_packages, 3000);
			}
        }).done(function (resp) {
			$("#run_composer_button_lock").removeAttr("disabled");

			if (typeof(resp.message) != 'undefined') {
               $('#remote_patch_log').html(resp.message);
            }

		    if (typeof(resp.try_again) != 'undefined') {
                replace_old_packages();
            }
			if (typeof(resp.move_vendor) != 'undefined') {
                replace_old_packages();
            }
        });

    }

    function save_new_package_form() {
        mw.form.post('#add-package-form-<?php print $params['id'] ?>', '<?php print api_link('mw_composer_save_package'); ?>',
            function (msg) {
                mw.notification.msg(this);
                reload_changes();
                return false;
            });
        return false;
    }

    function remove_patch_item($key) {
        $.post("<?php print api_link('mw_composer_save_package'); ?>", { require_name: $key, require_version: "delete" })
            .done(function (msg) {
                // mw.notification.msg(this);
                reload_changes();
            });

    }

    function reload_changes() {
        mw.reload_module('#<?php print $params['id'] ?>');
    }
</script>
<?php $required_packages = mw()->update->composer_get_required(); ?>
<?php //  $other_packages = mw()->update->composer_get_required(); ?>

<pre id="remote_patch_log"></pre>
<table width="100%" cellspacing="0" cellpadding="0" class="mw-ui-table">
  <thead>
    <tr>
      <th>Package</th>
      <th>Version</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($required_packages)): ?>
    <tr>
      <?php foreach ($required_packages as $key => $val): ?>
      <td><?php print $key; ?></td>
      <td><?php print $val; ?></td>
      <td><button type="button" onClick="remove_patch_item('<?php print $key; ?>')">x</button></td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>
<form onSubmit="return save_new_package_form();" id="add-package-form-<?php print $params['id'] ?>">
  <input type="text" class="mw-ui-field mw-ui-field-medium" name="require_name">
  <input type="text" class="mw-ui-field mw-ui-field-medium" name="require_version">
  <button class="mw-ui-btn mw-ui-btn-medium" type="submit">Save config</button>
  <button type="button" id="run_composer_button_lock" onClick="apply_new_packages()"><?php _e('Run composer update'); ?></button>
</form>
