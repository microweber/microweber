<?php only_admin_access(); ?>

<?php require isset($params['backend']) ? 'admin_backend.php' : 'admin_live_edit.php'; ?>

<script type="text/javascript" src="<?php echo $config['url_to_module']; ?>langs.js"></script>
<script type="text/javascript">mw.require('options.js');</script>
<script type="text/javascript">

$(document).ready(function() {
 mw.options.form('#<?php echo $params['id']; ?>', function() {
   mw.notification.success("<?php _e("Settings are saved!"); ?>");
 });
});

function reload_after_save() {
  mw.reload_module('#<?php echo $params['id']; ?>');
  mw.reload_module_parent('#<?php echo $params['id']; ?>');
  mw.notification.success("<?php _e("Language added"); ?>");
}

$('#default_lang').on('change', function() {
  $.post('/api/multilang_set_default', { lang: $(this).val() }, reload_after_save);
});

$('#add_lang').click(function(e) {
  e.preventDefault();
  var data = { lang: $('#site_langs').val() };
  if(data.lang) $.post('/api/multilang_add', data, reload_after_save);
});

for(var lk in MULTILANG_LOCALES) {
  $('#site_langs').append($('<option></option>').val(lk).text(MULTILANG_LOCALES[lk]));
}
</script>
