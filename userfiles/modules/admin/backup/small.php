<?php if(!is_admin()){error("must be admin");}; ?>
<script  type="text/javascript">
    mw.require("<?php print $config['url_to_module']; ?>backup.js");
	mw.require("files.js");
</script>
<style>
#mw_upsdfsdloader.disabled iframe {
	top:-9999px;
}
.back-up-nav-btns .mw-ui-btn {
	margin-right: 12px;
}
</style>

<h2>
  <?php _e("Backup"); ?>
</h2>
<div class="back-up-nav-btns"> <a href="javascript:mw.admin_backup.create('.mw_edit_page_right')" class="mw-ui-btn mw-ui-btn-green"><span class="ico iplus"></span><span>
  <?php _e("Create Database Backup"); ?>
  </span></a> <a href="javascript:mw.admin_backup.create_full('.mw_edit_page_right')" class="mw-ui-btn mw-ui-btn-blue"><span class="ico iplus"></span><span>
  <?php _e("Create Full Backup"); ?>
  </span></a>
  <script type="text/javascript">
		var uploader = mw.files.uploader({
			filetypes:"zip,sql",
			multiple:false
		});

		_mw_log_reload_int = false;
		$(document).ready(function(){


			if(_mw_log_reload_int == false){
						_mw_log_reload_int = true;
					     mw.reload_module_interval("#mw_backup_log", 6000);
					}


		mw.$("#mw_uploader").append(uploader);
				$(uploader).bind("FileUploaded", function(obj, data){
					mw.admin_backup.move_uploaded_file_to_backup(data.src);
					//mw.tools.enable(mwd.getElementById('mw_uploader'));
					mw.$("#mw_uploader_loading").hide();
					mw.$("#mw_uploader").show();
                    mw.$("#upload_backup_info").html("");

				});

			    $(uploader).bind('progress', function(up, file) {




					mw.$("#mw_uploader").hide();
					mw.$("#mw_uploader_loading").show();
                // mw.notification.warning("Still uploading...", 5000);

					 mw.tools.disable(mwd.getElementById('mw_uploader_loading'), '<?php _e("Uploading"); ?>...<span id="upload_backup_info"></span>');
                     mw.$("#upload_backup_info").html(file.percent + "%");
            	});

                $(uploader).bind('error', function(up, file) {
                   mw.notification.error("<?php _ejs('The backup must be sql or zip.'); ?>");

            	});




		});


		</script>
  <span id="mw_uploader" class="mw-ui-btn"><span class="ico iupload"></span>&nbsp;<span>
  <?php _e("Upload backup"); ?>
  <span id="upload_backup_info"></span></span></span> </div>
<div id="mw_uploader_loading" class="mw-ui-btn" style="display:none;">
  <?php _e("Uploading files"); ?>
</div>
<div class="vSpace">&nbsp;</div>
<module id="mw_backup_log" type="admin/backup/log"/>
<?php if(isset($_GET['backup_action'])): ?>
<module type="admin/backup/<?php print $_GET['backup_action'] ?>" />
<?php else :?>
<module type="admin/backup/manage" />
<?php endif ;?>

