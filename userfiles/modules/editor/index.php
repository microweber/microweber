<?php

if(user_id() == 0){
    return;
}
?>
<script type="text/javascript">
//mw.require('forms.js', true);
//mw.require('jquery-ui.js', true);

//mw.require("wysiwyg.js") ;


  mw.settings.liveEdit = true;

  mw.lib.require("jqueryui");

  mw.require("liveadmin.js");
  mw.require("events.js");
  mw.require("url.js");
  mw.require("wysiwyg.js");
  mw.require("css_parser.js");
  mw.require ("forms.js");
  mw.require("files.js");
  mw.require("content.js", true);
  mw.require("<?php print mw()->template->get_liveeditjs_url()  ?>");
  mw.require(mw.settings.includes_url + "css/liveedit.css",true);
  mw.require(mw.settings.includes_url + "css/components.css");
  mw.require(mw.settings.includes_url + "css/wysiwyg.css");



</script>
<style>
.mw-sorthandle, .mw_master_handle {
	display:none !important;
}
</style>
<?php
$here = mw_includes_path();
  include($here.'toolbar'.DS.'wysiwyg.php');
include($here.'toolbar'.DS.'wysiwyg_tiny.php');



