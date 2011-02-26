<?php /* Smarty version 2.6.26, created on 2011-02-09 23:49:52
         compiled from CoreHome/templates/top_bar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'CoreHome/templates/top_bar.tpl', 9, false),array('modifier', 'strtolower', 'CoreHome/templates/top_bar.tpl', 11, false),array('modifier', 'urlRewriteWithParameters', 'CoreHome/templates/top_bar.tpl', 11, false),)), $this); ?>
<div id="topBars">

<div id="topLeftBar">
<?php $_from = $this->_tpl_vars['topMenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['topMenu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['topMenu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['label'] => $this->_tpl_vars['menu']):
        $this->_foreach['topMenu']['iteration']++;
?>
    
        <?php if (isset ( $this->_tpl_vars['menu']['_html'] )): ?>
            <?php echo $this->_tpl_vars['menu']['_html']; ?>

        <?php elseif ($this->_tpl_vars['menu']['_url']['module'] == $this->_tpl_vars['currentModule']): ?>
            <span class="topBarElem"><b><?php echo ((is_array($_tmp=$this->_tpl_vars['label'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b></span> | 
        <?php else: ?>
            <span class="topBarElem"><a id="topmenu-<?php echo ((is_array($_tmp=$this->_tpl_vars['menu']['_url']['module'])) ? $this->_run_mod_handler('strtolower', true, $_tmp) : strtolower($_tmp)); ?>
" href="index.php<?php echo smarty_modifier_urlRewriteWithParameters($this->_tpl_vars['menu']['_url']); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['label'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></span> | 
        <?php endif; ?>
    
<?php endforeach; endif; unset($_from); ?>
</div>

<div id="topRightBar">
<span class="topBarElem"><?php echo ((is_array($_tmp='General_HelloUser')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['userLogin'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['userLogin'])."</strong>")); ?>
</span>
<?php if ($this->_tpl_vars['userLogin'] != 'anonymous'): ?>| <span class="topBarElem"><a href='index.php?module=CoreAdminHome'><?php echo ((is_array($_tmp='General_Settings')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></span><?php endif; ?>
| <span class="topBarElem"><?php if ($this->_tpl_vars['userLogin'] == 'anonymous'): ?><a href='index.php?module=<?php echo $this->_tpl_vars['loginModule']; ?>
'><?php echo ((is_array($_tmp='Login_LogIn')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a><?php else: ?><a href='index.php?module=<?php echo $this->_tpl_vars['loginModule']; ?>
&amp;action=logout'><?php echo ((is_array($_tmp='Login_Logout')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a><?php endif; ?></span>
</div>


</div>

<?php if ($this->_tpl_vars['showSitesSelection']): ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/sites_selection.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php endif; ?>