<?php only_admin_access(); ?>
<div class="settings-wrapper">
	<div class="module-live-edit-settings">

		<h3>Multilingual Support</h3>

    <div>
  		<label class="mw-ui-check">
  			<input name="is_multilang" type="checkbox" class="mw_option_field" value="1" option-group="website" <?php if( get_option('is_multilang', 'website') ) echo 'checked'; ?>>
  			<span></span>
  			<span>This site has multilingual content</span>
  		</label>
    </div>

    <div>
  		<label class="mw-ui-check">
  			<input name="mutilang_no_fallback" class="mw_option_field" type="checkbox" value="1" option-group="website" <?php if( get_option('mutilang_no_fallback', 'website') ) echo 'checked'; ?>>
  			<span></span>
  			<span>Don't show the original content if translation is unavailable</span>
  		</label>
    </div>

		<h3>Supported Languages</h3>
		<?php $langs = multilang_locales(); ?>

		<ul class="menu">
			<?php foreach($langs as $lang): ?>
			<li class="menu_element">
				<div class="module_item">
					<span class="mw-language-tag"><?php echo $lang; ?></span>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>

		<select id="site_langs" class="mw-ui-field">
      <option>Select a language</option>
    </select>

    <a href="#" class="mw-ui-btn pull-right" id="add_lang">
      <span class="mw-icon mw-icon-plus"></span>
      Add selected
    </a>

		<label class="mw-ui-label">Default Language</label>
		<select id="default_lang" type="text" class="mw-ui-field mw-language">
    <?php foreach($langs as $lang): ?>
      <option value="<?php echo $lang; ?>" <?php if($lang == config('app.fallback_locale')) echo 'selected'; ?>></option>
    <?php endforeach; ?>
    </select>

		<?php if(isset($params['backend'])): ?>

		<?php $translations = DB::table('translations')->get(); ?>

		<h3>
			Available Translations
			(<?php echo count($translations); ?>)
		</h3>

		<table width="100%">
		<thead>
			<tr>
				<th>Language</th>
				<th>Source ID</th>
				<th>Source Type</th>
				<th>Translated Data</th>
			</tr>
		<thead>
		<?php if(count($translations)): ?>
		<?php foreach($translations as $translation): ?>
			<tr>
				<td>
					<?php echo $translation->lang; ?>
					<div class="mw-language-tag"><?php echo $translation->lang; ?></div>
				</td>
				<td align="center"><?php echo $translation->translatable_id; ?></td>
				<td align="center"><?php echo $translation->translatable_type; ?></td>
				<td>
<pre>
<?php
$json = json_decode($translation->translation);
var_dump((array)$json);
?>
</pre>
				</td>
			</tr>
    <?php endforeach; ?>
		<?php endif; ?>
		</table>

		<?php endif; ?>

	</div>
</div>

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
