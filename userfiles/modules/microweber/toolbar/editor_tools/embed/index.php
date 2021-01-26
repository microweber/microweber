<script type="text/javascript" src="<?php print(mw()->template->get_apijs_combined_url()); ?>"></script>


<script src="<?php   print( mw_includes_url());  ?>js/sortable.js" type="text/javascript"></script>


<link href="<?php   print( mw_includes_url());  ?>css/components.css" rel="stylesheet" type="text/css" />
<link href="<?php   print( mw_includes_url());  ?>css/wysiwyg.css" rel="stylesheet" type="text/css" />

<?php /* <script src="http://c9.io/ooyes/mw/workspace/sortable.js" type="text/javascript"></script>  */ ?>
  <?php
$here = dirname(dirname(dirname(__FILE__)));

include($here.DS.'wysiwyg.php');
include($here.DS.'wysiwyg_tiny.php');

?>
