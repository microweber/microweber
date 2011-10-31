<?php /* Smarty version 2.6.26, created on 2011-07-03 13:40:03
         compiled from PDFReports/templates/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'PDFReports/templates/list.tpl', 5, false),array('modifier', 'replace', 'PDFReports/templates/list.tpl', 39, false),array('function', 'url', 'PDFReports/templates/list.tpl', 42, false),)), $this); ?>
<div id='entityEditContainer'>
	<table class="dataTable entityTable">
	<thead>
	<tr>
        <th class="first"><?php echo ((is_array($_tmp='General_Description')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
        <th><?php echo ((is_array($_tmp='PDFReports_EmailSchedule')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
        <th><?php echo ((is_array($_tmp='PDFReports_SendReportTo')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
        <th><?php echo ((is_array($_tmp='General_Download')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
        <th><?php echo ((is_array($_tmp='General_Edit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
        <th><?php echo ((is_array($_tmp='General_Delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
	</tr>
	</thead>
	
	<?php if ($this->_tpl_vars['userLogin'] == 'anonymous'): ?>
		<tr><td colspan=6> 
		<br/>
		<?php echo ((is_array($_tmp='PDFReports_YouMustBeLoggedIn')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

		<br/>&rsaquo; <a href='index.php?module=<?php echo $this->_tpl_vars['loginModule']; ?>
'><?php echo ((is_array($_tmp='Login_LogIn')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></strong>   
		<br/><br/> 
		</td></tr>
		</table>
	<?php elseif (empty ( $this->_tpl_vars['reports'] )): ?>
		<tr><td colspan=6> 
		<br/>
		<?php echo ((is_array($_tmp='PDFReports_ThereIsNoPDFReportToManage')) ? $this->_run_mod_handler('translate', true, $_tmp, $this->_tpl_vars['siteName']) : smarty_modifier_translate($_tmp, $this->_tpl_vars['siteName'])); ?>
. 
		<br/><br/>
		<a onclick='' id='linkAddReport'>&rsaquo; <?php echo ((is_array($_tmp='PDFReports_CreateAndSchedulePDFReport')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
		<br/><br/> 
		</td></tr>
		</table>
	<?php else: ?>
		<?php $_from = $this->_tpl_vars['reports']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['report']):
?>
			<tr>
				<td class="first"><?php echo $this->_tpl_vars['report']['description']; ?>
</td>
		        <td><?php echo $this->_tpl_vars['periods'][$this->_tpl_vars['report']['period']]; ?>

		        	<!-- Last sent on <?php echo $this->_tpl_vars['report']['ts_last_sent']; ?>
 -->
		        </td>
				<td><?php if ($this->_tpl_vars['report']['email_me'] == 1): ?><?php echo $this->_tpl_vars['currentUserEmail']; ?>
<?php if (! empty ( $this->_tpl_vars['report']['additional_emails'] )): ?><br/><?php endif; ?><?php endif; ?> 
					<?php echo ((is_array($_tmp=$this->_tpl_vars['report']['additional_emails'])) ? $this->_run_mod_handler('replace', true, $_tmp, ",", ' ') : smarty_modifier_replace($_tmp, ",", ' ')); ?>

					<br/><a href='#' idreport='<?php echo $this->_tpl_vars['report']['idreport']; ?>
' name='linkEmailNow' class="link_but" style='margin-top:3px'><img border=0 src='themes/default/images/email.png'> <?php echo ((is_array($_tmp='PDFReports_SendReportNow')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
					</td>
				<td><a href="<?php echo smarty_function_url(array('module' => 'API','token_auth' => $this->_tpl_vars['token_auth'],'method' => 'PDFReports.generateReport','idSite' => $this->_tpl_vars['idSite'],'date' => $this->_tpl_vars['rawDate'],'idReport' => $this->_tpl_vars['report']['idreport'],'outputType' => $this->_tpl_vars['pdfDownloadOutputType'],'language' => $this->_tpl_vars['language']), $this);?>
" target="_blank" name="linkDownloadReport" id="<?php echo $this->_tpl_vars['report']['idreport']; ?>
" class="link_but"><img src='plugins/UserSettings/images/plugins/pdf.gif' border="0" /> <?php echo ((is_array($_tmp='General_Download')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></td>
				<td><a href='#' name="linkEditReport" id="<?php echo $this->_tpl_vars['report']['idreport']; ?>
" class="link_but"><img src='themes/default/images/ico_edit.png' border="0" /> <?php echo ((is_array($_tmp='General_Edit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></td>
				<td><a href='#' name="linkDeleteReport" id="<?php echo $this->_tpl_vars['report']['idreport']; ?>
" class="link_but"><img src='themes/default/images/ico_delete.png' border="0" /> <?php echo ((is_array($_tmp='General_Delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></td>
			</tr>
		<?php endforeach; endif; unset($_from); ?>
		</table>
		<?php if ($this->_tpl_vars['userLogin'] != 'anonymous'): ?>
			<br/>
			<a onclick='' id='linkAddReport'>&rsaquo; <?php echo ((is_array($_tmp='PDFReports_CreateAndSchedulePDFReport')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
		<?php endif; ?>
	<?php endif; ?>
</div>