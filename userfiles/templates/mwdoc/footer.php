</div>
 
<?php include (ACTIVE_TEMPLATE_DIR.'footer_stats_scripts.php') ?>
  <!-- Start #footer -->
  <div id="footer">
    <p>Copyright &copy; 2010 <a href="http://microweber.com">Microweber</a>, all rights reserved.</p>
  </div>
</div>
<!-- #page -->
<script  type="text/javascript" src="<?php print TEMPLATE_URL; ?>/cssjs/jquery.1.4.2.min.js"></script>
<script  type="text/javascript" src="<?php print TEMPLATE_URL; ?>/cssjs/jquery.hoverIntent.min.js"></script>
<script  type="text/javascript" src="<?php print TEMPLATE_URL; ?>/cssjs/jquery.tablesorter.min.js"></script>
<script  type="text/javascript" src="<?php print TEMPLATE_URL; ?>/cssjs/jquery.lightbox-0.5.min.js"></script>
<script  type="text/javascript" src="<?php print TEMPLATE_URL; ?>/cssjs/jquery.uniform.min.js"></script>
<script  type="text/javascript" src="<?php print TEMPLATE_URL; ?>/cssjs/jquery.visualize.js"></script>
<script  type="text/javascript" src="<?php print TEMPLATE_URL; ?>/cssjs/carbon.js"></script>
<script  type="text/javascript" src="<?php print TEMPLATE_URL; ?>/cssjs/carbon.nav.js"></script>
<script  type="text/javascript" src="<?php print TEMPLATE_URL; ?>/cssjs/carbon.message.js"></script>
<script  type="text/javascript" src="<?php print TEMPLATE_URL; ?>/cssjs/carbon.portlet.js"></script>
<!--[if IE]><script  type="text/javascript" src="<?php print TEMPLATE_URL; ?>/cssjs/excanvas.min.js"></script><![endif]-->
<script type="text/javascript" charset="utf-8">
$(function () 
{
	megadrop.init ();
	carbon.portlet.init ();
	carbon.message.init ();
	
	carbon.init ();
});
</script>
</body>
</html>