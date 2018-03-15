<?php only_admin_access(); ?>
<script type="text/javascript">
    mw.require("<?php print $config['url_to_module']; ?>backup.js");
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

<div class="mw-ui-row admin-section-bar">
    <div class="mw-ui-col">
        <h2><?php _e("Backups"); ?></h2>
    </div>
</div>

<div id="mw-admin-content" class="admin-side-content">
  <div class="mw_edit_page_default" id="mw_edit_page_left">



        <div class="mw-ui-btn-nav pull-left">

          <a href="javascript:mw.admin_backup.create('.mw_edit_page_right')" class="mw-ui-btn mw-ui-btn-warn">
            <span class="mw-icon-file"></span>
            <span>
              <?php _e("Database Backup"); ?>
            </span>
          </a>
          <a href="javascript:mw.admin_backup.create_full('.mw_edit_page_right')" class="mw-ui-btn mw-ui-btn-notification"><span class="mw-icon-refresh"></span><span>
          <?php _e("Create Full Backup"); ?>
          </span></a>

          <script type="text/javascript">
                        var uploader = mw.files.uploader({
                            filetypes: "zip,sql",
                            multiple: false
                        });

                        _mw_log_reload_int = false;
                        $(document).ready(function () {

                            mw.$("#mw_uploader").append(uploader);
                            $(uploader).bind("FileUploaded", function (obj, data) {
                                mw.admin_backup.move_uploaded_file_to_backup(data.src);
                                //mw.tools.enable(mwd.getElementById('mw_uploader'));
                                mw.$("#mw_uploader_loading").hide();
                                mw.$("#mw_uploader").show();
                                mw.$("#upload_backup_info").html("");
                            });

                            $(uploader).bind('progress', function (up, file) {
                                mw.$("#mw_uploader").hide();
                                mw.$("#mw_uploader_loading").show();
                                mw.tools.disable(mwd.getElementById('mw_uploader_loading'), '<?php _e("Uploading"); ?>...<span id="upload_backup_info"></span>');
                                mw.$("#upload_backup_info").html(file.percent + "%");
                            });
                            $(uploader).bind('error', function (up, file) {
                                mw.notification.error("<?php _e("The backup must be sql or zip"); ?>.");

                            });
                        });

                    </script>
          </div>
          <span id="mw_uploader" class="mw-ui-btn mw-ui-btn-info pull-right"><span class="mw-icon-upload"></span><span>
          <?php _e("Upload backup"); ?>
          <span id="upload_backup_info"></span></span></span>
        <div id="mw_uploader_loading" class="mw-ui-btn" style="display:none;">
          <?php _e("Uploading files"); ?>
        </div>
        <div class="vSpace">&nbsp;</div>
        <div id="mw_backup_log" type="admin/backup/log"></div>


  </div>
  <div class="mw_edit_page_right" style="padding: 20px 0;">
    <?php if (isset($_GET['backup_action'])): ?>
    <?php $action= htmlentities( mw()->format->strip_unsafe($_GET['backup_action'])); ?>
    <module type="admin/backup/<?php print $action ?>"/>
    <?php else : ?>
    <module type="admin/backup/manage"/>
    <?php endif;?>
  </div>
</div>
