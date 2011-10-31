<?php /* Smarty version 2.6.26, created on 2011-06-14 12:50:25
         compiled from LanguagesManager/templates/languages.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'LanguagesManager/templates/languages.tpl', 6, false),)), $this); ?>
<span class="topBarElem" style="padding-right:70px">
	<span id="languageSelection" style="display:none;position:absolute">
		<form action="index.php?<?php if ($this->_tpl_vars['currentModule'] != ''): ?>module=LanguagesManager&amp;<?php endif; ?>action=saveLanguage" method="get">
		<select name="language">
			<option value="<?php echo $this->_tpl_vars['currentLanguageCode']; ?>
"><?php echo $this->_tpl_vars['currentLanguageName']; ?>
</option>
			<option href='?module=Proxy&action=redirect&url=http://piwik.org/translations/'><?php echo ((is_array($_tmp='LanguagesManager_AboutPiwikTranslations')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
			<?php $_from = $this->_tpl_vars['languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['language']):
?>
			<option value="<?php echo $this->_tpl_vars['language']['code']; ?>
" title="<?php echo $this->_tpl_vars['language']['name']; ?>
 (<?php echo $this->_tpl_vars['language']['english_name']; ?>
)"><?php echo $this->_tpl_vars['language']['name']; ?>
</option>
			<?php endforeach; endif; unset($_from); ?>
		</select>
		<input type="submit" value="go" />
		</form>
	</span>
	
	<script type="text/javascript">
	piwik.languageName = "<?php echo $this->_tpl_vars['currentLanguageName']; ?>
";
	<?php echo '
	$(document).ready(function() {
		$("#languageSelection").fdd2div({CssClassName:"formDiv"});
		$("#languageSelection").show();
		$("#languageSelection ul").hide();
	});</script>
	'; ?>

</span>