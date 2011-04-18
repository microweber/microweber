<?php /* Smarty version 2.6.26, created on 2011-04-18 13:14:55
         compiled from C:%5Cxampp%5Cxampp%5Chtdocs%5Cmicroweber%5Csystem%5Capplication%5Cstats%5Cplugins%5CLogin/templates/login.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'C:\\xampp\\xampp\\htdocs\\microweber\\system\\application\\stats\\plugins\\Login/templates/login.tpl', 8, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Login/templates/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="login">

<?php if ($this->_tpl_vars['form_data']['errors']): ?>
<div id="login_error">	
	<?php $_from = $this->_tpl_vars['form_data']['errors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		<strong><?php echo ((is_array($_tmp='General_Error')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</strong>: <?php echo $this->_tpl_vars['data']; ?>
<br />
	<?php endforeach; endif; unset($_from); ?>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['AccessErrorString']): ?>
<div id="login_error"><strong><?php echo ((is_array($_tmp='General_Error')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</strong>: <?php echo $this->_tpl_vars['AccessErrorString']; ?>
<br /></div>
<?php endif; ?>

<form <?php echo $this->_tpl_vars['form_data']['attributes']; ?>
>
	<p>
		<label><?php echo ((is_array($_tmp='General_Username')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:<br />
		<input type="text" name="form_login" id="form_login" class="input" value="" size="20" tabindex="10" />
		<input type="hidden" name="form_nonce" id="form_nonce" value="<?php echo $this->_tpl_vars['nonce']; ?>
" /></label>
	</p>

	<p>
		<label><?php echo ((is_array($_tmp='Login_Password')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:<br />
		<input type="password" name="form_password" id="form_password" class="input" value="" size="20" tabindex="20" /></label>
	</p>
	<p class="forgetmenot">
		<label><input name="form_rememberme" type="checkbox" id="form_rememberme" value="1" tabindex="90" <?php if ($this->_tpl_vars['form_data']['form_rememberme']['value']): ?>checked="checked" <?php endif; ?>/> <?php echo ((is_array($_tmp='Login_RememberMe')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 </label>
	</p>
	<p class="submit">
		<input type="submit" value="<?php echo ((is_array($_tmp='Login_LogIn')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" tabindex="100" />
	</p>
</form>

<p id="nav">
<a href="index.php?module=Login&amp;action=lostPassword" title="<?php echo ((is_array($_tmp='Login_LostYourPassword')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
"><?php echo ((is_array($_tmp='Login_LostYourPassword')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
</p>

</div>

</body>
</html>