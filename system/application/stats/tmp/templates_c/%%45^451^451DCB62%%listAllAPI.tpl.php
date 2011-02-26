<?php /* Smarty version 2.6.26, created on 2011-02-09 23:50:08
         compiled from C:%5Cxampp%5Cxampp%5Chtdocs%5Ccms%5Csystem%5Capplication%5Cstats%5Cplugins%5CAPI/templates/listAllAPI.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'C:\\xampp\\xampp\\htdocs\\cms\\system\\application\\stats\\plugins\\API/templates/listAllAPI.tpl', 11, false),)), $this); ?>
<?php $this->assign('showSitesSelection', true); ?>
<?php $this->assign('showPeriodSelection', false); ?>
<?php $this->assign('showMenu', false); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div class="page_api">
    <div class="top_controls_inner">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/period_select.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
    
    <h2><?php echo ((is_array($_tmp='API_QuickDocumentationTitle')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
    <p><?php echo ((is_array($_tmp='API_PluginDescription')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
    
    <?php if ($this->_tpl_vars['isSuperUser']): ?>
        <p><?php echo ((is_array($_tmp='API_GenerateVisits')) ? $this->_run_mod_handler('translate', true, $_tmp, 'VisitorGenerator', 'VisitorGenerator') : smarty_modifier_translate($_tmp, 'VisitorGenerator', 'VisitorGenerator')); ?>
</p>
    <?php endif; ?>
    
    <p><b><?php echo ((is_array($_tmp='API_MoreInformation')) ? $this->_run_mod_handler('translate', true, $_tmp, "<a target='_blank' href='?module=Proxy&action=redirect&url=http://dev.piwik.org/trac/wiki/API'>", "</a>", "<a target='_blank' href='?module=Proxy&action=redirect&url=http://dev.piwik.org/trac/wiki/API/Reference'>", "</a>") : smarty_modifier_translate($_tmp, "<a target='_blank' href='?module=Proxy&action=redirect&url=http://dev.piwik.org/trac/wiki/API'>", "</a>", "<a target='_blank' href='?module=Proxy&action=redirect&url=http://dev.piwik.org/trac/wiki/API/Reference'>", "</a>")); ?>
</b></p>
    
    <h2><?php echo ((is_array($_tmp='API_UserAuthentication')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
    <p>
    <?php echo ((is_array($_tmp='API_UsingTokenAuth')) ? $this->_run_mod_handler('translate', true, $_tmp, '<b>', '</b>', "<u><code>&amp;token_auth=".($this->_tpl_vars['token_auth'])."</code></u>") : smarty_modifier_translate($_tmp, '<b>', '</b>', "<u><code>&amp;token_auth=".($this->_tpl_vars['token_auth'])."</code></u>")); ?>
<br />
    <span id='token_auth'>token_auth = <b><?php echo $this->_tpl_vars['token_auth']; ?>
</b></span><br />
    <?php echo ((is_array($_tmp='API_KeepTokenSecret')) ? $this->_run_mod_handler('translate', true, $_tmp, '<b>', '</b>') : smarty_modifier_translate($_tmp, '<b>', '</b>')); ?>

    <p><i><?php echo ((is_array($_tmp='API_LoadedAPIs')) ? $this->_run_mod_handler('translate', true, $_tmp, $this->_tpl_vars['countLoadedAPI']) : smarty_modifier_translate($_tmp, $this->_tpl_vars['countLoadedAPI'])); ?>
</i></p>
    <?php echo $this->_tpl_vars['list_api_methods_with_links']; ?>

    <br />
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/piwik_tag.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>