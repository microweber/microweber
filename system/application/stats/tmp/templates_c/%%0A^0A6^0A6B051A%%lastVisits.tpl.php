<?php /* Smarty version 2.6.26, created on 2011-02-09 23:50:06
         compiled from C:%5Cxampp%5Cxampp%5Chtdocs%5Ccms%5Csystem%5Capplication%5Cstats%5Cplugins%5CLive/templates/lastVisits.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'C:\\xampp\\xampp\\htdocs\\cms\\system\\application\\stats\\plugins\\Live/templates/lastVisits.tpl', 6, false),array('modifier', 'escape', 'C:\\xampp\\xampp\\htdocs\\cms\\system\\application\\stats\\plugins\\Live/templates/lastVisits.tpl', 15, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['visitors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['visitor']):
?>
	<div id="<?php echo $this->_tpl_vars['visitor']['idVisit']; ?>
" class="visit<?php if ($this->_tpl_vars['visitor']['idVisit'] % 2): ?> alt<?php endif; ?>">
		<div style="display:none" class="idvisit"><?php echo $this->_tpl_vars['visitor']['idVisit']; ?>
</div>
			<div class="datetime">
				<?php echo $this->_tpl_vars['visitor']['serverDatePretty']; ?>
 - <?php echo $this->_tpl_vars['visitor']['serverTimePretty']; ?>
 (<?php echo $this->_tpl_vars['visitor']['visitLengthPretty']; ?>
)
				&nbsp;<img src="<?php echo $this->_tpl_vars['visitor']['countryFlag']; ?>
" title="<?php echo $this->_tpl_vars['visitor']['country']; ?>
, <?php echo ((is_array($_tmp='Provider_ColumnProvider')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 <?php echo $this->_tpl_vars['visitor']['provider']; ?>
" />
				&nbsp;<img src="<?php echo $this->_tpl_vars['visitor']['browserIcon']; ?>
" title="<?php echo $this->_tpl_vars['visitor']['browser']; ?>
, <?php echo ((is_array($_tmp='UserSettings_Plugins')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <?php echo $this->_tpl_vars['visitor']['plugins']; ?>
" />
				&nbsp;<img src="<?php echo $this->_tpl_vars['visitor']['operatingSystemIcon']; ?>
" title="<?php echo $this->_tpl_vars['visitor']['operatingSystem']; ?>
, <?php echo $this->_tpl_vars['visitor']['resolution']; ?>
" />
				&nbsp;<?php if ($this->_tpl_vars['visitor']['isVisitorGoalConverted']): ?><img src="<?php echo $this->_tpl_vars['visitor']['goalIcon']; ?>
" title="<?php echo ((is_array($_tmp='Goals_GoalConversion')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 (<?php echo $this->_tpl_vars['visitor']['goalType']; ?>
)" /><?php endif; ?>
				<?php if ($this->_tpl_vars['visitor']['isVisitorReturning']): ?>&nbsp;<img src="plugins/Live/templates/images/returningVisitor.gif" title="Returning Visitor" /><?php endif; ?>
				<?php if ($this->_tpl_vars['visitor']['ip']): ?>IP: <?php echo $this->_tpl_vars['visitor']['ip']; ?>
<?php endif; ?>
			</div>
			<!--<div class="settings"></div>-->
			<div class="referer">
				<?php if ($this->_tpl_vars['visitor']['refererType'] != 'directEntry'): ?>from <a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['visitor']['refererUrl'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" target="_blank"><?php if (! empty ( $this->_tpl_vars['visitor']['searchEngineIcon'] )): ?><img src="<?php echo $this->_tpl_vars['visitor']['searchEngineIcon']; ?>
" /> <?php endif; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['visitor']['refererName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a>
					<?php if (! empty ( $this->_tpl_vars['visitor']['keywords'] )): ?>"<?php echo ((is_array($_tmp=$this->_tpl_vars['visitor']['keywords'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"<?php endif; ?>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['visitor']['refererType'] == 'directEntry'): ?><?php echo ((is_array($_tmp='Referers_DirectEntry')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
<?php endif; ?>
			</div>
		<div id="<?php echo $this->_tpl_vars['visitor']['idVisit']; ?>
_actions" class="settings">
			<span class="pagesTitle"><?php echo ((is_array($_tmp='Actions_SubmenuPages')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</span>&nbsp;
			<?php  $col = 0;	 ?>
			<?php $_from = $this->_tpl_vars['visitor']['actionDetails']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['action']):
?>
			  <?php 
			  	$col++;
		  		if ($col>=9)
		  		{
				  $col=0;
		  		}
				 ?>
				<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['pageUrl'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" target="_blank"><img align="middle" src="plugins/Live/templates/images/file<?php  echo $col;  ?>.png" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['pageUrl'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /></a>
			<?php endforeach; endif; unset($_from); ?>
		</div>
	</div>
<?php endforeach; endif; unset($_from); ?>