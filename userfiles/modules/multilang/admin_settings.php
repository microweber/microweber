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

<a href="#" class="mw-ui-btn" id="add_lang">
  <span class="mw-icon mw-icon-plus"></span>
  Add selected
</a>

<h3>Primary Language</h3>
<select id="default_lang" type="text" class="mw-ui-field mw-language">
<?php foreach($langs as $lang): ?>
  <option value="<?php echo $lang; ?>" <?php if($lang == config('app.fallback_locale')) echo 'selected'; ?>></option>
<?php endforeach; ?>
</select>
