<?php /* Smarty version 2.6.26, created on 2011-07-03 13:40:03
         compiled from PDFReports/templates/add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'PDFReports/templates/add.tpl', 3, false),array('modifier', 'escape', 'PDFReports/templates/add.tpl', 69, false),)), $this); ?>
<div class='entityAddContainer' style='display:none'>
<div class='entityCancel'>
	<?php echo ((is_array($_tmp='PDFReports_CancelAndReturnToPDF')) ? $this->_run_mod_handler('translate', true, $_tmp, "<a class='entityCancelLink'>", "</a>") : smarty_modifier_translate($_tmp, "<a class='entityCancelLink'>", "</a>")); ?>

</div>
<div class='clear'></div>
<form id='addEditReport'>
<table class="dataTable entityTable">
	<thead>
		<tr class="first">
			<th colspan="2"><?php echo ((is_array($_tmp='PDFReports_CreateAndSchedulePDFReport')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
		<tr>
	</thead>
	<tbody>
		<tr>
            <td class="first"><?php echo ((is_array($_tmp='General_Website')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 </th>
			<td  style="width:650px">
				<?php echo $this->_tpl_vars['siteName']; ?>

			</td>
		</tr>
		<tr>
            <td class="first"><?php echo ((is_array($_tmp='General_Description')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 </th>
			<td>
			<textarea cols="30" rows="3" id="report_description" class="inp"></textarea>
			<div class="entityInlineHelp">
				<?php echo ((is_array($_tmp='PDFReports_DescriptionWillBeFirstPage')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

			</div>
			</td>
		</tr>
		<tr>
			<td class="first"><?php echo ((is_array($_tmp='PDFReports_EmailSchedule')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td>
				<select id="report_period" class="inp">
				<?php $_from = $this->_tpl_vars['periods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['periodId'] => $this->_tpl_vars['period']):
?>
                    <option value="<?php echo $this->_tpl_vars['periodId']; ?>
"><?php echo $this->_tpl_vars['period']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
				</select>
				
				<div class="entityInlineHelp">
					<?php echo ((is_array($_tmp='PDFReports_WeeklyScheduleHelp')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

					<br/>
					<?php echo ((is_array($_tmp='PDFReports_MonthlyScheduleHelp')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

				</div>
			</td>
		</tr>
		<tr>
			<td style='width:240px;' class="first"><?php echo ((is_array($_tmp='PDFReports_SendReportTo')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

			</td>
			<td>
				<input type="checkbox" id="report_email_me" />
				<label for="report_email_me"><?php echo ((is_array($_tmp='PDFReports_SentToMe')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 (<i><?php echo $this->_tpl_vars['currentUserEmail']; ?>
</i>) </label>
				<br/><br/>
				<?php echo ((is_array($_tmp='PDFReports_AlsoSendReportToTheseEmails')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
<br/>
				<textarea cols="30" rows="3" id="report_additional_emails" class="inp"></textarea>
			</td>
		</tr>
		<tr>
			<td class="first"><?php echo ((is_array($_tmp='PDFReports_ReportsIncludedInPDF')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td>
			<div id='reportsList'>
				<?php $this->assign('countReports', 0); ?>
				<div id='leftcolumn'>
				<?php $_from = $this->_tpl_vars['reportsByCategory']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['reports'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['reports']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['category'] => $this->_tpl_vars['reports']):
        $this->_foreach['reports']['iteration']++;
?>
					<?php if ($this->_tpl_vars['countReports'] >= $this->_tpl_vars['newColumnAfter'] && $this->_tpl_vars['newColumnAfter'] != 0): ?>
						<?php $this->assign('newColumnAfter', 0); ?>
						</div><div id='rightcolumn'>
					<?php endif; ?>
					<div class='reportCategory'><?php echo $this->_tpl_vars['category']; ?>
</div><ul class='listReports'>
					<?php $_from = $this->_tpl_vars['reports']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['report']):
?>
						<li><input type="checkbox" id="<?php echo $this->_tpl_vars['report']['uniqueId']; ?>
" /><label for="<?php echo $this->_tpl_vars['report']['uniqueId']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['report']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</label></li>
						<?php $this->assign('countReports', $this->_tpl_vars['countReports']+1); ?>
					<?php endforeach; endif; unset($_from); ?>
					</ul>
					<br/>
				<?php endforeach; endif; unset($_from); ?>
				</div>
			</div>
			</td>
		</tr>
		
	</tbody>
</table>
<input type="hidden" id="report_idreport" value="">
<input type="submit" value="<?php echo ((is_array($_tmp='PDFReports_CreatePDFReport')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" name="submit" id="report_submit" class="submit" />
</form>
<div class='entityCancel'>
	<?php echo ((is_array($_tmp='General_OrCancel')) ? $this->_run_mod_handler('translate', true, $_tmp, "<a class='entityCancelLink'>", "</a>") : smarty_modifier_translate($_tmp, "<a class='entityCancelLink'>", "</a>")); ?>

</div>
</div>
