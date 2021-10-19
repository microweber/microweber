<?php

if(user_id() == 0){
    return;
}
?>
<script type="text/javascript">

  mw.settings.liveEdit = true;

  mw.require("<?php print mw()->template->get_liveeditjs_url()  ?>");




</script>

<?php print app()->template->admin->getLiveEditTemplateHeadHtml(); ?>

<style>
.mw-sorthandle, .mw_master_handle {
	display:none !important;
}
</style>
<?php
$here = mw_includes_path();
  include($here.'toolbar'.DS.'wysiwyg.php');
include($here.'toolbar'.DS.'wysiwyg_tiny.php');



