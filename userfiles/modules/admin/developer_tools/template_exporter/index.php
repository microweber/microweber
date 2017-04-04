<?php only_admin_access(); ?>
<script type="text/javascript">
    mw.require("<?php print $config['url_to_module']; ?>template_exporter.js");
    mw.require("files.js");
</script>
<style>
#mw_upsdfsdloader.disabled iframe {
	top: -9999px;
}
.back-up-nav-btns .mw-ui-btn {
	width: 170px;
	text-align: left;
}
</style>

<div id="mw-admin-content">
  <div class="mw_edit_page_default" id="mw_edit_page_left">
    <div class="mw-admin-sidebar">
      <h2><span class="ico imanage-module"></span>&nbsp;
          <?php _e('Template backup'); ?> </h2>
    </div>
    <div class="mw-admin-side-nav">
      <div>
        <div class="back-up-nav-btns">
          <div class="vSpace"></div>
          <a href="javascript:mw.template_exporter.create()"
                       class="mw-ui-btn mw-ui-btn-green"><span class="mw-icon-plus"></span><span> <?php _e('Export template'); ?> </span></a>
          <div class="vSpace"></div>
        </div>
        <div id="mw_backup_log" type="admin/developer_tools/template_exporter/log"></div>
      </div>
    </div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px;">
    <module type="admin/developer_tools/template_exporter/manage"/>
  </div>
</div>
