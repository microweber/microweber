<?php /* Smarty version 2.6.26, created on 2011-07-03 12:30:32
         compiled from Goals/templates/add_edit_goal.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'Goals/templates/add_edit_goal.tpl', 2, false),array('modifier', 'escape', 'Goals/templates/add_edit_goal.tpl', 39, false),array('function', 'ajaxErrorDiv', 'Goals/templates/add_edit_goal.tpl', 19, false),array('function', 'ajaxLoadingDiv', 'Goals/templates/add_edit_goal.tpl', 20, false),array('function', 'loadJavascriptTranslations', 'Goals/templates/add_edit_goal.tpl', 35, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['onlyShowAddNewGoal'] )): ?>
    <h2><?php echo ((is_array($_tmp='Goals_AddNewGoal')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
    <p><?php echo ((is_array($_tmp='Goals_LearnMoreAboutGoalTrackingDocumentation')) ? $this->_run_mod_handler('translate', true, $_tmp, "<a href='?module=Proxy&action=redirect&url=http://piwik.org/docs/tracking-goals-web-analytics/' target='_blank'>", "</a>") : smarty_modifier_translate($_tmp, "<a href='?module=Proxy&action=redirect&url=http://piwik.org/docs/tracking-goals-web-analytics/' target='_blank'>", "</a>")); ?>

    </p>
<?php else: ?>
	
    <div class="clear"></div>
	<h2><?php echo ((is_array($_tmp='Goals_GoalsManagement')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
	<div class="entityList">
		<ul class='listCircle'>
			<li><a onclick='' name='linkAddNewGoal'><u><?php echo ((is_array($_tmp='Goals_CreateNewGOal')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</u></a></li>
			<li><a onclick='' name='linkEditGoals'><?php echo ((is_array($_tmp='Goals_ViewAndEditGoals')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></li>
			<li><?php echo ((is_array($_tmp='Goals_LearnMoreAboutGoalTrackingDocumentation')) ? $this->_run_mod_handler('translate', true, $_tmp, "<a href='?module=Proxy&action=redirect&url=http://piwik.org/docs/tracking-goals-web-analytics/' target='_blank'>", "</a>") : smarty_modifier_translate($_tmp, "<a href='?module=Proxy&action=redirect&url=http://piwik.org/docs/tracking-goals-web-analytics/' target='_blank'>", "</a>")); ?>
</li>
		</ul>
	</div>
	<br/>
<?php endif; ?>

<?php echo smarty_function_ajaxErrorDiv(array(), $this);?>

<?php echo smarty_function_ajaxLoadingDiv(array('id' => 'goalAjaxLoading'), $this);?>

	
<div class="entityContainer">
	<?php if (! isset ( $this->_tpl_vars['onlyShowAddNewGoal'] )): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Goals/templates/list_goal_edit.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Goals/templates/form_add_goal.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php if (! isset ( $this->_tpl_vars['onlyShowAddNewGoal'] )): ?>
		<div class='entityCancel' style='display:none'>
			<?php echo ((is_array($_tmp='General_OrCancel')) ? $this->_run_mod_handler('translate', true, $_tmp, "<a class='entityCancelLink'>", "</a>") : smarty_modifier_translate($_tmp, "<a class='entityCancelLink'>", "</a>")); ?>

		</div>
	<?php endif; ?>
	<a id='bottom'></a>
</div>
<br/><br/>
<?php echo smarty_function_loadJavascriptTranslations(array('plugins' => 'Goals'), $this);?>

<script type="text/javascript">

var mappingMatchTypeName = { 
	"url": "<?php echo ((is_array($_tmp=((is_array($_tmp='Goals_URL')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
", 
	"url": "<?php echo ((is_array($_tmp=((is_array($_tmp='Goals_PageTitle')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
", 
	"file": "<?php echo ((is_array($_tmp=((is_array($_tmp='Goals_Filename')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
", 
	"external_website": "<?php echo ((is_array($_tmp=((is_array($_tmp='Goals_ExternalWebsiteUrl')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" 
};
var mappingMatchTypeExamples = {
	"url": "<?php echo ((is_array($_tmp='General_ForExampleShort')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 <?php echo ((is_array($_tmp=((is_array($_tmp='Goals_Contains')) ? $this->_run_mod_handler('translate', true, $_tmp, "'checkout/confirmation'") : smarty_modifier_translate($_tmp, "'checkout/confirmation'")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 \
		<br /><?php echo ((is_array($_tmp=((is_array($_tmp='General_ForExampleShort')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=((is_array($_tmp='Goals_IsExactly')) ? $this->_run_mod_handler('translate', true, $_tmp, "'http://example.com/thank-you.html'") : smarty_modifier_translate($_tmp, "'http://example.com/thank-you.html'")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 \
		<br /><?php echo ((is_array($_tmp=((is_array($_tmp='General_ForExampleShort')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=((is_array($_tmp='Goals_MatchesExpression')) ? $this->_run_mod_handler('translate', true, $_tmp, "'(.*)\\\/demo\\\/(.*)'") : smarty_modifier_translate($_tmp, "'(.*)\\\/demo\\\/(.*)'")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
", 
	"title": "<?php echo ((is_array($_tmp='General_ForExampleShort')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 <?php echo ((is_array($_tmp=((is_array($_tmp='Goals_Contains')) ? $this->_run_mod_handler('translate', true, $_tmp, "'Order confirmation'") : smarty_modifier_translate($_tmp, "'Order confirmation'")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
",
	"file": "<?php echo ((is_array($_tmp=((is_array($_tmp='General_ForExampleShort')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=((is_array($_tmp='Goals_Contains')) ? $this->_run_mod_handler('translate', true, $_tmp, "'files/brochure.pdf'") : smarty_modifier_translate($_tmp, "'files/brochure.pdf'")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 \
		<br /><?php echo ((is_array($_tmp=((is_array($_tmp='General_ForExampleShort')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=((is_array($_tmp='Goals_IsExactly')) ? $this->_run_mod_handler('translate', true, $_tmp, "'http://example.com/files/brochure.pdf'") : smarty_modifier_translate($_tmp, "'http://example.com/files/brochure.pdf'")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 \
		<br /><?php echo ((is_array($_tmp=((is_array($_tmp='General_ForExampleShort')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=((is_array($_tmp='Goals_MatchesExpression')) ? $this->_run_mod_handler('translate', true, $_tmp, "'(.*)\\\.zip'") : smarty_modifier_translate($_tmp, "'(.*)\\\.zip'")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
", 
	"external_website": "<?php echo ((is_array($_tmp=((is_array($_tmp='General_ForExampleShort')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=((is_array($_tmp='Goals_Contains')) ? $this->_run_mod_handler('translate', true, $_tmp, "'amazon.com'") : smarty_modifier_translate($_tmp, "'amazon.com'")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 \
		<br /><?php echo ((is_array($_tmp=((is_array($_tmp='General_ForExampleShort')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=((is_array($_tmp='Goals_IsExactly')) ? $this->_run_mod_handler('translate', true, $_tmp, "'http://mypartner.com/landing.html'") : smarty_modifier_translate($_tmp, "'http://mypartner.com/landing.html'")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 \
		<br /><?php echo ((is_array($_tmp=((is_array($_tmp='General_ForExampleShort')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=((is_array($_tmp='Goals_MatchesExpression')) ? $this->_run_mod_handler('translate', true, $_tmp, "'http://www.amazon.com\\\/(.*)\\\/yourAffiliateId'") : smarty_modifier_translate($_tmp, "'http://www.amazon.com\\\/(.*)\\\/yourAffiliateId'")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" 
};
bindGoalForm();

<?php if (! isset ( $this->_tpl_vars['onlyShowAddNewGoal'] )): ?>
piwik.goals = <?php echo $this->_tpl_vars['goalsJSON']; ?>
;
bindListGoalEdit();
<?php else: ?>
initAndShowAddGoalForm();
<?php endif; ?>

</script>