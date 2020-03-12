<script type="text/javascript" src="<?php print(mw()->template->get_apijs_combined_url()); ?>"></script>


<script src="<?php   print( mw_includes_url());  ?>js/sortable.js" type="text/javascript"></script>


<script type="text/javascript">
  //document.body.className+=' loading';

  //mw.require("<?php print( mw_includes_url());  ?>js/jquery.js");

  mw.settings.liveEdit = true;

  typeof jQuery === 'undefined' ? mw.require("<?php print mw_includes_url(); ?>js/jquery-1.9.1.js") : '' ;
  mw.require("liveadmin.js");
  mw.lib.require("jqueryui");
mw.require("events.js");
  mw.require("url.js");
 // mw.require("tools.js");
  mw.require("wysiwyg.js");
  mw.require("css_parser.js");
  
  mw.require("forms.js");
  mw.require("files.js");
  mw.require("content.js", true);
  mw.require("session.js");
 


</script>
<link href="<?php   print( mw_includes_url());  ?>css/components.css" rel="stylesheet" type="text/css" />
<link href="<?php   print( mw_includes_url());  ?>css/wysiwyg.css" rel="stylesheet" type="text/css" />
 
<?php /* <script src="http://c9.io/ooyes/mw/workspace/sortable.js" type="text/javascript"></script>  */ ?>
  <?php
$here = dirname(dirname(dirname(__FILE__)));
 
include($here.DS.'wysiwyg.php');
include($here.DS.'wysiwyg_tiny.php');

?>
