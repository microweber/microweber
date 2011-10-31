<?php /* Smarty version 2.6.26, created on 2011-06-14 12:50:25
         compiled from CoreHome/templates/menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'urlRewriteWithParameters', 'CoreHome/templates/menu.tpl', 4, false),array('modifier', 'urlRewriteBasicView', 'CoreHome/templates/menu.tpl', 4, false),array('modifier', 'translate', 'CoreHome/templates/menu.tpl', 4, false),array('modifier', 'escape', 'CoreHome/templates/menu.tpl', 8, false),)), $this); ?>
<ul class="nav">
<?php $_from = $this->_tpl_vars['menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['menu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['menu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['level1'] => $this->_tpl_vars['level2']):
        $this->_foreach['menu']['iteration']++;
?>
<li>
	<a name='<?php echo smarty_modifier_urlRewriteWithParameters($this->_tpl_vars['level2']['_url']); ?>
' href='index.php<?php echo smarty_modifier_urlRewriteBasicView($this->_tpl_vars['level2']['_url']); ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['level1'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
	<ul>
	<?php $_from = $this->_tpl_vars['level2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['level2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['level2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['urlParameters']):
        $this->_foreach['level2']['iteration']++;
?>
		<?php if (strpos ( $this->_tpl_vars['name'] , '_' ) !== 0): ?>
		<li><a name='<?php echo smarty_modifier_urlRewriteWithParameters($this->_tpl_vars['urlParameters']['_url']); ?>
' href='index.php<?php echo smarty_modifier_urlRewriteBasicView($this->_tpl_vars['urlParameters']['_url']); ?>
'><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['name'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a></li>
		<?php endif; ?>
 	<?php endforeach; endif; unset($_from); ?>
 	</ul>
</li>
<?php endforeach; endif; unset($_from); ?>
</ul>