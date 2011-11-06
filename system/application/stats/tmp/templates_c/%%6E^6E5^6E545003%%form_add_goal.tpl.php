<?php /* Smarty version 2.6.26, created on 2011-07-03 12:30:32
         compiled from Goals/templates/form_add_goal.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'Goals/templates/form_add_goal.tpl', 11, false),array('modifier', 'money', 'Goals/templates/form_add_goal.tpl', 85, false),)), $this); ?>
<div class='entityAddContainer' style="display:none;">
<form>
<table class="dataTable entityTable">
	<thead>
		<tr class="first">
			<th colspan="2">Create a Goal</th>
		<tr>
	</thead>
	<tbody>
		<tr>
            <td class="first"><?php echo ((is_array($_tmp='Goals_GoalName')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 </th>
			<td><input type="text" name="name" value="" size="28" id="goal_name" class="inp" /></td>
		</tr>
		<tr>
			<td style='width:240px;' class="first"><?php echo ((is_array($_tmp='Goals_GoalIsTriggered')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

				<select name="trigger_type" class="inp">
					<option value="visitors"><?php echo ((is_array($_tmp='Goals_WhenVisitors')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
					<option value="manually"><?php echo ((is_array($_tmp='Goals_Manually')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
				</select>
			</td>
			<td>
				<input type="radio" id="match_attribute_url" value="url" name="match_attribute" />
                <label for="match_attribute_url"><?php echo ((is_array($_tmp='Goals_VisitUrl')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label>
				<br />
				<input type="radio" id="match_attribute_title" value="title" name="match_attribute" />
                <label for="match_attribute_title"><?php echo ((is_array($_tmp='Goals_VisitPageTitle')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label>
				<br />
				<input type="radio" id="match_attribute_file" value="file" name="match_attribute" />
				<label for="match_attribute_file"><?php echo ((is_array($_tmp='Goals_Download')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label>
				<br />
				<input type="radio" id="match_attribute_external_website" value="external_website" name="match_attribute" />
				<label for="match_attribute_external_website"><?php echo ((is_array($_tmp='Goals_ClickOutlink')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label>
			</td>
			</tr>
	</tbody>
	<tbody id="match_attribute_section">
		<tr>
			<td class="first"><?php echo ((is_array($_tmp='Goals_WhereThe')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 <span id="match_attribute_name"></span></td>
			<td>
				<select name="pattern_type" class="inp">
                    <option value="contains"><?php echo ((is_array($_tmp='Goals_Contains')) ? $this->_run_mod_handler('translate', true, $_tmp, "") : smarty_modifier_translate($_tmp, "")); ?>
</option>
                    <option value="exact"><?php echo ((is_array($_tmp='Goals_IsExactly')) ? $this->_run_mod_handler('translate', true, $_tmp, "") : smarty_modifier_translate($_tmp, "")); ?>
</option>
                    <option value="regex"><?php echo ((is_array($_tmp='Goals_MatchesExpression')) ? $this->_run_mod_handler('translate', true, $_tmp, "") : smarty_modifier_translate($_tmp, "")); ?>
</option>
				</select>
			
				<input type="text" name="pattern" value="" size="16" class="inp" />
				<br />
				<div id="examples_pattern" class="entityInlineHelp"></div>
				<br />
				<span style="float:right">
				<?php echo ((is_array($_tmp='Goals_Optional')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 <input type="checkbox" id="case_sensitive" />
                <label for="case_sensitive"><?php echo ((is_array($_tmp='Goals_CaseSensitive')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label>
				</span>
			</td>
		</tr>
	</tbody>
	<tbody id="manual_trigger_section" style="display:none">
		<tr><td colspan="2" class="first">
				<?php echo ((is_array($_tmp='Goals_WhereVisitedPageManuallyCallsJavascriptTrackerLearnMore')) ? $this->_run_mod_handler('translate', true, $_tmp, "<a target='_blank' href='?module=Proxy&action=redirect&url=http://piwik.org/docs/javascript-tracking/%23toc-manually-trigger-a-conversion-for-a-goal'>", "</a>") : smarty_modifier_translate($_tmp, "<a target='_blank' href='?module=Proxy&action=redirect&url=http://piwik.org/docs/javascript-tracking/%23toc-manually-trigger-a-conversion-for-a-goal'>", "</a>")); ?>

		</td></tr>
	</tbody>
	<tbody>
		<tr>
            <td class="first"> <?php echo ((is_array($_tmp='Goals_AllowMultipleConversionsPerVisit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 </td>
			<td>
				<input type="radio" id="allow_multiple_0" value="0" name="allow_multiple" />
                <label for="allow_multiple_0"><?php echo ((is_array($_tmp='Goals_DefaultGoalConvertedOncePerVisit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label>
            	<div class="entityInlineHelp">  
            		<?php echo ((is_array($_tmp='Goals_HelpOneConversionPerVisit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

            	</div>
                <br/>
                
                <input type="radio" id="allow_multiple_1" value="1" name="allow_multiple" />
                <label for="allow_multiple_1"><?php echo ((is_array($_tmp='Goals_AllowGoalConvertedMoreThanOncePerVisit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label>
				<br />
	            <div class="entityInlineHelp">  
					<?php echo ((is_array($_tmp='Goals_HelpMultipleConversionsPerVisit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

				</td>
		</tr>
		<tr>
	</tbody>
	<tbody>
		<tr>
            <td class="first">(optional) <?php echo ((is_array($_tmp='Goals_DefaultRevenue')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td><?php echo ((is_array($_tmp=' <input type="text" name="revenue" size="2" value="0" class="inp" /> ')) ? $this->_run_mod_handler('money', true, $_tmp, $this->_tpl_vars['idSite']) : smarty_modifier_money($_tmp, $this->_tpl_vars['idSite'])); ?>

            <div class="entityInlineHelp"> <?php echo ((is_array($_tmp='Goals_DefaultRevenueHelp')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 </div>
			</td>
		</tr>
		<tr>
	</tbody>
</table>
        <input type="hidden" name="methodGoalAPI" value="" />	
        <input type="hidden" name="goalIdUpdate" value="" />
        <input type="submit" value="" name="submit" id="goal_submit" class="submit" />
</form>
</div>