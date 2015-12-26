<script type="text/javascript">
mw.require("<?php print mw_includes_url(); ?>css/admin2.css");
mw.require("<?php print mw_includes_url(); ?>api/panel.js");
</script>

<div class="mw-ui-row" style="height:100%;">
  <div class="mw-ui-col mw-admin-main-panel-main-left-col-wrap">
    <?php include(__DIR__.DS.'sidebar.php'); ?>
  </div>
  <div class="mw-ui-col mw-admin-main-panel-main-right-col-wrap">
    <?php include(__DIR__.DS.'main.php'); ?>
  </div>
</div>
