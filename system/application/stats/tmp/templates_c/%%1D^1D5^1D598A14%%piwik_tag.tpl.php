<?php /* Smarty version 2.6.26, created on 2011-11-22 17:56:26
         compiled from CoreHome/templates/piwik_tag.tpl */ ?>
<?php if ($this->_tpl_vars['piwikUrl'] == 'http://demo.piwik.org/' || $this->_tpl_vars['debugTrackVisitsInsidePiwikUI']): ?>

<div class="clear"></div>
<?php echo '
<!-- Piwik -->
<script src="piwik.js" type="text/javascript"></script>
<script type="text/javascript">
try {
 var piwikTracker = Piwik.getTracker("piwik.php", 1);
 '; ?>

 <?php if ($this->_tpl_vars['piwikUrl'] == 'http://demo.piwik.org/'): ?>
 	piwikTracker.setCookieDomain('*.piwik.org');
 <?php endif; ?>
 <?php echo '
 //Set the domain the visitor landed on, in the Custom Variable
 if(!piwikTracker.getCustomVariable(1)) { 
   piwikTracker.setCustomVariable(1, "Domain landed", document.domain );
 }
 //Set the selected Piwik language in a custom var
 piwikTracker.setCustomVariable(2, "Demo language", piwik.languageName );
 piwikTracker.setDocumentTitle(document.domain + "/" + document.title);
 piwikTracker.trackPageView();
 piwikTracker.enableLinkTracking();
} catch(err) {}
</script>
<!-- End Piwik Code -->
'; ?>

<?php endif; ?>