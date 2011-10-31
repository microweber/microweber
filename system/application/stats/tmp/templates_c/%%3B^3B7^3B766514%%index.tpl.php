<?php /* Smarty version 2.6.26, created on 2011-07-03 13:52:27
         compiled from /home/microweber/public_html/system/application/stats/plugins/VisitorInterest/templates/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', '/home/microweber/public_html/system/application/stats/plugins/VisitorInterest/templates/index.tpl', 2, false),)), $this); ?>

<h2><?php echo ((is_array($_tmp='VisitorInterest_VisitsPerDuration')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
<?php echo $this->_tpl_vars['dataTableNumberOfVisitsPerVisitDuration']; ?>


<h2><?php echo ((is_array($_tmp='VisitorInterest_VisitsPerNbOfPages')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
<?php echo $this->_tpl_vars['dataTableNumberOfVisitsPerPage']; ?>

