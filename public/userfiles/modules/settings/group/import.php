<?php if(!is_admin()){error("must be admin");}; ?>
<script  type="text/javascript">
 	mw.require("files.js");
</script>

Import
<div class="back-up-nav-btns">

  <script type="text/javascript">
    		var uploader = mw.files.uploader({
    			filetypes:"zip,sql",
    			multiple:false,
                element: "#mw_uploader"
    		});

		_mw_log_reload_int = false;
		$(document).ready(function(){




				$(uploader).on("FileUploaded", function(obj, data){
					mw.admin_backup.move_uploaded_file_to_backup(data.src);
					//mw.tools.enable(document.getElementById('mw_uploader'));
					mw.$("#mw_uploader_loading").hide();
					mw.$("#mw_uploader").show();
                    mw.$("#upload_backup_info").html("");

				});

			    $(uploader).on('progress', function(up, file) {




					mw.$("#mw_uploader").hide();
					mw.$("#mw_uploader_loading").show();
                // mw.notification.warning("Still uploading...", 5000);

					 mw.tools.disable(document.getElementById('mw_uploader_loading'), '<?php _e("Uploading"); ?>...<span id="upload_backup_info"></span>');
                     mw.$("#upload_backup_info").html(file.percent + "%");
            	});

                $(uploader).on('error', function(up, file) {
                   mw.notification.error("<?php _ejs("The file must be"); ?> xml, sql, csv or zip.");

            	});




		});


		</script>
  <span id="mw_uploader" class="  btn btn-primary"><span class="mw-icon-upload"></span> <span>
  <?php _e("Upload backup"); ?>
  <span id="upload_backup_info"></span></span></span> </div>
<div id="mw_uploader_loading" class="  btn btn-primary" style="display:none;">
  <?php _e("Uploading files"); ?>
</div>
