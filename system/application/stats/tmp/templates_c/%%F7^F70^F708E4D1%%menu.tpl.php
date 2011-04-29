<?php /* Smarty version 2.6.26, created on 2011-04-19 10:38:49
         compiled from CoreAdminHome/templates/menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'urlRewriteWithParameters', 'CoreAdminHome/templates/menu.tpl', 5, false),array('modifier', 'translate', 'CoreAdminHome/templates/menu.tpl', 5, false),)), $this); ?>
<?php if (count ( $this->_tpl_vars['menu'] ) > 1): ?>
	<div id="menu">
	<ul id="tablist">
	<?php $_from = $this->_tpl_vars['menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['menu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['menu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['url']):
        $this->_foreach['menu']['iteration']++;
?>
		<li> <a href='index.php<?php echo smarty_modifier_urlRewriteWithParameters($this->_tpl_vars['url']['_url']); ?>
' <?php if (isset ( $this->_tpl_vars['currentAdminMenuName'] ) && $this->_tpl_vars['name'] == $this->_tpl_vars['currentAdminMenuName']): ?>class='active'<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['name'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></li>	<?php endforeach; endif; unset($_from); ?>
	</ul>
	</div>
<?php endif; ?>