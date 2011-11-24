<?php /* Smarty version 2.6.26, created on 2011-11-22 17:56:26
         compiled from CoreHome/templates/period_select.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'loadJavascriptTranslations', 'CoreHome/templates/period_select.tpl', 1, false),array('function', 'url', 'CoreHome/templates/period_select.tpl', 14, false),array('function', 'ajaxLoadingDiv', 'CoreHome/templates/period_select.tpl', 19, false),array('modifier', 'translate', 'CoreHome/templates/period_select.tpl', 4, false),)), $this); ?>
<?php echo smarty_function_loadJavascriptTranslations(array('plugins' => 'CoreHome'), $this);?>


<div id="periodString">
	<div id="date"><?php echo ((is_array($_tmp='General_DateRange')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 <b><?php echo $this->_tpl_vars['prettyDate']; ?>
</b> <img src='themes/default/images/icon-calendar.gif' alt="" /></div>
	<div id="periodMore">
		<div class="period-date">
			<h6><?php echo ((is_array($_tmp='General_Date')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h6>
			<div id="datepicker"></div>
		</div>
		<div class="period-type">
			<h6><?php echo ((is_array($_tmp='General_Period')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h6>            
			<span id="otherPeriods">
			<?php $_from = $this->_tpl_vars['periodsNames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['label'] => $this->_tpl_vars['thisPeriod']):
?>
				<input type="radio" name="period" id="period_id_<?php echo $this->_tpl_vars['label']; ?>
" value="<?php echo smarty_function_url(array('period' => $this->_tpl_vars['label']), $this);?>
"<?php if ($this->_tpl_vars['label'] == $this->_tpl_vars['period']): ?> checked="checked"<?php endif; ?> />
				<label for="period_id_<?php echo $this->_tpl_vars['label']; ?>
" ><?php echo $this->_tpl_vars['thisPeriod']['singular']; ?>
</label><br />
			<?php endforeach; endif; unset($_from); ?>
			</span>
			<input tabindex="3" type="submit" value="<?php echo ((is_array($_tmp='General_ApplyDateRange')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" id="calendarRangeApply">
			<?php echo smarty_function_ajaxLoadingDiv(array('id' => 'ajaxLoadingCalendar'), $this);?>

		</div>
	</div>
</div>