<?php /* Smarty version 2.6.26, created on 2011-07-03 13:41:49
         compiled from /home/microweber/public_html/system/application/stats/plugins/Feedback/templates/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', '/home/microweber/public_html/system/application/stats/plugins/Feedback/templates/index.tpl', 29, false),)), $this); ?>
<?php echo '
<script type="text/javascript">
$(function() {
	$(\'#feedback-contact\').click(function() {
		$(\'#feedback-faq\').hide();
		$(\'#feedback-form\').show();
		return false;
	});

	$(\'#feedback-home\').click(function() {
		$(\'#feedback-form\').hide();
		$(\'#feedback-faq\').show();
		return false;
	});

	$(\'#feedback-form-submit\').click(function() {
		var feedback = $(\'#feedback-form form\');
		$(\'#feedback-form\').hide();
		$.post(feedback.attr(\'action\'), feedback.serialize(), function (data) {
			$(\'#feedback-sent\').show().html(data);
		});
		return false;
	});
});
</script>
'; ?>


  <div id="feedback-faq">
    <p><strong><?php echo ((is_array($_tmp='Feedback_DoYouHaveBugReportOrFeatureRequest')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</strong></p>
    <p> &bull; <?php echo ((is_array($_tmp='Feedback_ViewAnswersToFAQ')) ? $this->_run_mod_handler('translate', true, $_tmp, "<a target='_blank' href='?module=Proxy&action=redirect&url=http://piwik.org/faq/'>", "</a>") : smarty_modifier_translate($_tmp, "<a target='_blank' href='?module=Proxy&action=redirect&url=http://piwik.org/faq/'>", "</a>")); ?>
.</p>
    <ul>
      <li>» <?php echo ((is_array($_tmp='Feedback_WhyAreMyVisitsNoTracked')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</li>
      <li>» <?php echo ((is_array($_tmp='Feedback_HowToExclude')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</li>
      <li>» <?php echo ((is_array($_tmp='Feedback_WhyWrongCountry')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</li>
      <li>» <?php echo ((is_array($_tmp='Feedback_HowToAnonymizeIP')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</li>
    </ul>
    <p> &bull; <?php echo ((is_array($_tmp='Feedback_VisitTheForums')) ? $this->_run_mod_handler('translate', true, $_tmp, "<a target='_blank' href='?module=Proxy&action=redirect&url=http://forum.piwik.org/'>", "</a>") : smarty_modifier_translate($_tmp, "<a target='_blank' href='?module=Proxy&action=redirect&url=http://forum.piwik.org/'>", "</a>")); ?>
.</p>
    <p> &bull; <?php echo ((is_array($_tmp='Feedback_LearnWaysToParticipate')) ? $this->_run_mod_handler('translate', true, $_tmp, "<a target='_blank' href='?module=Proxy&action=redirect&url=http://piwik.org/contribute/'>", "</a>") : smarty_modifier_translate($_tmp, "<a target='_blank' href='?module=Proxy&action=redirect&url=http://piwik.org/contribute/'>", "</a>")); ?>
.</p>
    <br />
    <p><strong><?php echo ((is_array($_tmp='Feedback_SpecialRequest')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</strong></p>
    <p> &bull;  <a target='_blank' href="#" id="feedback-contact"><?php echo ((is_array($_tmp='Feedback_ContactThePiwikTeam')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></p>
  </div>
  <div id="feedback-form" style="display:none;">
    <form method="post" action="index.php?module=Feedback&action=sendFeedback">
     <label><?php echo ((is_array($_tmp='Feedback_IWantTo')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label>
        <select name="category">
          <option value="share"><?php echo ((is_array($_tmp='Feedback_CategoryShareStory')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
          <option value="sponsor"><?php echo ((is_array($_tmp='Feedback_CategorySponsor')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
          <option value="hire"><?php echo ((is_array($_tmp='Feedback_CategoryHire')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
          <option value="security"><?php echo ((is_array($_tmp='Feedback_CategorySecurity')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
        </select>
     <br />
		<label><?php echo ((is_array($_tmp='Feedback_MyEmailAddress')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label>
        <input type="text" name="email" size="59" />
        <input type="hidden" name="nonce" value="<?php echo $this->_tpl_vars['nonce']; ?>
" /><br />
      	<label><?php echo ((is_array($_tmp='Feedback_MyMessage')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
<br /><i><?php echo ((is_array($_tmp='Feedback_DetailsPlease')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</i></label>
        <textarea name="body" cols="57" rows="10">Please write your message in English</textarea><br />
      	<label><a href="#" id="feedback-home"><img src="plugins/Feedback/images/go-previous.png" border="0" title="<?php echo ((is_array($_tmp='General_Previous')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" alt="[<?php echo ((is_array($_tmp='General_Previous')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
]" /></a></label>
      <input id="feedback-form-submit" type="submit" class='submit' value="<?php echo ((is_array($_tmp='Feedback_SendFeedback')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
    </form>
  </div>
  <div id="feedback-sent" style="display:none;">
  </div>