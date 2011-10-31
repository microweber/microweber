<?php /* Smarty version 2.6.26, created on 2011-06-14 12:50:25
         compiled from CoreHome/templates/js_disabled_notice.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'CoreHome/templates/js_disabled_notice.tpl', 3, false),)), $this); ?>
<noscript>
<div id="javascriptDisable">
<?php echo ((is_array($_tmp='CoreHome_JavascriptDisabled')) ? $this->_run_mod_handler('translate', true, $_tmp, '<a href="">', '</a>') : smarty_modifier_translate($_tmp, '<a href="">', '</a>')); ?>

</div>
</noscript>