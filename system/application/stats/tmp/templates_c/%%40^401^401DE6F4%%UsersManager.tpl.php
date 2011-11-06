<?php /* Smarty version 2.6.26, created on 2011-07-04 08:03:10
         compiled from /home/microweber/public_html/system/application/stats/plugins/UsersManager/templates/UsersManager.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'loadJavascriptTranslations', '/home/microweber/public_html/system/application/stats/plugins/UsersManager/templates/UsersManager.tpl', 4, false),array('function', 'url', '/home/microweber/public_html/system/application/stats/plugins/UsersManager/templates/UsersManager.tpl', 37, false),array('function', 'ajaxErrorDiv', '/home/microweber/public_html/system/application/stats/plugins/UsersManager/templates/UsersManager.tpl', 54, false),array('function', 'ajaxLoadingDiv', '/home/microweber/public_html/system/application/stats/plugins/UsersManager/templates/UsersManager.tpl', 55, false),array('modifier', 'translate', '/home/microweber/public_html/system/application/stats/plugins/UsersManager/templates/UsersManager.tpl', 34, false),)), $this); ?>
<?php $this->assign('showSitesSelection', false); ?>
<?php $this->assign('showPeriodSelection', false); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreAdminHome/templates/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo smarty_function_loadJavascriptTranslations(array('plugins' => 'UsersManager'), $this);?>


<?php echo '
<style type="text/css">
.dialog {
	display: none;
	padding:20px 10px;
	color:#7A0101;
	cursor:wait;
	font-size:1.2em;
	font-weight:bold;
	text-align:center;
}
.editable:hover, .addrow:hover, .updateAccess:hover, .accessGranted:hover, .adduser:hover, .edituser:hover, .deleteuser:hover, .updateuser:hover, .cancel:hover{
	cursor: pointer;
}
.addrow {
	font-color:#3A477B;
	padding:1em;
	font-weight:bold;
}
.addrow a {
	text-decoration: none;
}
.addrow img {
	vertical-align: middle;
}
</style>
'; ?>


<h2><?php echo ((is_array($_tmp='UsersManager_ManageAccess')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
<p><?php echo ((is_array($_tmp='UsersManager_MainDescription')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
<div id="sites">
<form method="post" action="<?php echo smarty_function_url(array('action' => 'index'), $this);?>
" id="accessSites">
	<p><?php echo ((is_array($_tmp='UsersManager_Sites')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <select id="selectIdsite" name="idsite" onchange="changeSite()">
	
	<optgroup label="<?php echo ((is_array($_tmp='UsersManager_AllWebsites')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
">
		<option label="<?php echo ((is_array($_tmp='UsersManager_AllWebsites')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" value="all" <?php if ($this->_tpl_vars['idSiteSelected'] == 'all'): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='UsersManager_ApplyToAllWebsites')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
	</optgroup>
	
	<optgroup label="<?php echo ((is_array($_tmp='UsersManager_Sites')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
">
		<?php $_from = $this->_tpl_vars['websites']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
			<option value="<?php echo $this->_tpl_vars['info']['idsite']; ?>
" <?php if ($this->_tpl_vars['idSiteSelected'] == $this->_tpl_vars['info']['idsite']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['info']['name']; ?>
</option>
		<?php endforeach; endif; unset($_from); ?>
	</optgroup>
	
	</select></p>
</form>
</div>

<?php echo smarty_function_ajaxErrorDiv(array(), $this);?>

<?php echo smarty_function_ajaxLoadingDiv(array(), $this);?>

<div id="accessUpdated" class="ajaxSuccess"><?php echo ((is_array($_tmp='General_Done')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
!</div>

<div class="entityContainer" style='width:500px'>
	<table class="entityTable dataTable" id="access">
		<thead>
		<tr>
			<th class='first'><?php echo ((is_array($_tmp='UsersManager_User')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
			<th><?php echo ((is_array($_tmp='UsersManager_Alias')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
			<th><?php echo ((is_array($_tmp='UsersManager_PrivNone')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
			<th><?php echo ((is_array($_tmp='UsersManager_PrivView')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
			<th><?php echo ((is_array($_tmp='UsersManager_PrivAdmin')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
		</tr>
		</thead>
		
		<tbody>
		<?php $this->assign('accesValid', "<img src='plugins/UsersManager/images/ok.png' class='accessGranted' />"); ?>
		<?php $this->assign('accesInvalid', "<img src='plugins/UsersManager/images/no-access.png' class='updateAccess' />"); ?>
		<?php $_from = $this->_tpl_vars['usersAccessByWebsite']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['login'] => $this->_tpl_vars['access']):
?>
		<tr>
			<td id='login'><?php echo $this->_tpl_vars['login']; ?>
</td>
			<td><?php echo $this->_tpl_vars['usersAliasByLogin'][$this->_tpl_vars['login']]; ?>
</td>
			<td id='noaccess'><?php if ($this->_tpl_vars['access'] == 'noaccess' && $this->_tpl_vars['idSiteSelected'] != 'all'): ?><?php echo $this->_tpl_vars['accesValid']; ?>
<?php else: ?><?php echo $this->_tpl_vars['accesInvalid']; ?>
<?php endif; ?>&nbsp;</td>
			<td id='view'><?php if ($this->_tpl_vars['access'] == 'view' && $this->_tpl_vars['idSiteSelected'] != 'all'): ?><?php echo $this->_tpl_vars['accesValid']; ?>
<?php else: ?><?php echo $this->_tpl_vars['accesInvalid']; ?>
<?php endif; ?>&nbsp;</td>
			<td id='admin'><?php if ($this->_tpl_vars['access'] == 'admin' && $this->_tpl_vars['idSiteSelected'] != 'all'): ?><?php echo $this->_tpl_vars['accesValid']; ?>
<?php else: ?><?php echo $this->_tpl_vars['accesInvalid']; ?>
<?php endif; ?>&nbsp;</td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		</tbody>
	</table>
</div>

<div class="dialog" id="confirm"> 
	<p><?php echo ((is_array($_tmp='UsersManager_ChangeAllConfirm')) ? $this->_run_mod_handler('translate', true, $_tmp, "<span id='login'></span>") : smarty_modifier_translate($_tmp, "<span id='login'></span>")); ?>
</p>
	<input id="yes" type="button" value="<?php echo ((is_array($_tmp='General_Yes')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
	<input id="no" type="button" value="<?php echo ((is_array($_tmp='General_No')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
</div> 

<?php if ($this->_tpl_vars['userIsSuperUser']): ?>
	<br />
	<h2><?php echo ((is_array($_tmp='UsersManager_UsersManagement')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
	<p><?php echo ((is_array($_tmp='UsersManager_UsersManagementMainDescription')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 
	<?php echo ((is_array($_tmp='UsersManager_ThereAreCurrentlyNRegisteredUsers')) ? $this->_run_mod_handler('translate', true, $_tmp, "<b>".($this->_tpl_vars['usersCount'])."</b>") : smarty_modifier_translate($_tmp, "<b>".($this->_tpl_vars['usersCount'])."</b>")); ?>
</p>

	<?php echo smarty_function_ajaxErrorDiv(array('id' => 'ajaxErrorUsersManagement'), $this);?>

	<?php echo smarty_function_ajaxLoadingDiv(array('id' => 'ajaxLoadingUsersManagement'), $this);?>


	<div class="entityContainer" style='margin-bottom:50px'>
	<table class="entityTable dataTable" id="users">
		<thead>
			<tr>
				<th><?php echo ((is_array($_tmp='General_Username')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
				<th><?php echo ((is_array($_tmp='UsersManager_Password')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
				<th><?php echo ((is_array($_tmp='UsersManager_Email')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
				<th><?php echo ((is_array($_tmp='UsersManager_Alias')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
				<th>token_auth</th>
				<th><?php echo ((is_array($_tmp='General_Edit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
				<th><?php echo ((is_array($_tmp='General_Delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
			</tr>
		</thead>
		
		<tbody>
			<?php $_from = $this->_tpl_vars['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['user']):
?>
				<?php if ($this->_tpl_vars['user']['login'] != 'anonymous'): ?>
				<tr class="editable" id="row<?php echo $this->_tpl_vars['i']; ?>
">
					<td id="userLogin" class="editable"><?php echo $this->_tpl_vars['user']['login']; ?>
</td>
					<td id="password" class="editable">-</td>
					<td id="email" class="editable"><?php echo $this->_tpl_vars['user']['email']; ?>
</td>
					<td id="alias" class="editable"><?php echo $this->_tpl_vars['user']['alias']; ?>
</td>
					<td id="alias"><?php echo $this->_tpl_vars['user']['token_auth']; ?>
</td>
					<td><span class="edituser link_but" id="row<?php echo $this->_tpl_vars['i']; ?>
"><img title="<?php echo ((is_array($_tmp='General_Edit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" src='themes/default/images/ico_edit.png' /> <?php echo ((is_array($_tmp='General_Edit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 </span></td>
					<td><span class="deleteuser link_but" id="row<?php echo $this->_tpl_vars['i']; ?>
"><img title="<?php echo ((is_array($_tmp='General_Delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" src='themes/default/images/ico_delete.png' /> <?php echo ((is_array($_tmp='General_Delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 </span></td>
				</tr>
				<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
		</tbody>
	</table>
	<div class="addrow"><img src='plugins/UsersManager/images/add.png' /> <?php echo ((is_array($_tmp='UsersManager_AddUser')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div>
	</div>	
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreAdminHome/templates/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>