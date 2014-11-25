<?php if(!is_admin()){error("must be admin");}; ?>
<script  type="text/javascript">
 	mw.require("files.js");
</script>

Import
<div class="back-up-nav-btns">
  
  <script type="text/javascript">
    		var uploader = mw.files.uploader({
    			filetypes:"zip,sql",
    			multiple:false
    		});

		_mw_log_reload_int = false;
		$(document).ready(function(){

			

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
                   mw.notification.error("<?php _e("The file must be"); ?> xml, sql, csv or zip.");

            	});


			

		});


		</script>
  <span id="mw_uploader" class="mw-ui-btn"><span class="mw-icon-upload"></span><span>
  <?php _e("Upload backup"); ?>
  <span id="upload_backup_info"></span></span></span> </div>
<div id="mw_uploader_loading" class="mw-ui-btn" style="display:none;">
  <?php _e("Uploading files"); ?>
</div>
