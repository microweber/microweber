<?php only_admin_access(); ?>
<script  type="text/javascript">
    mw.require("<?php print $config['url_to_module']; ?>import.js");
</script>
<script type="text/javascript">
		var uploader = mw.files.uploader({
			filetypes:"all",
			multiple:false
		});

 
		$(document).ready(function(){
			
			
			 mw.reload_module_interval('admin/import/process', 1000);
		
		mw.$("#mw_uploader").append(uploader);
				$(uploader).bind("FileUploaded", function(obj, data){
					mw.$("#mw_uploader_loading").hide();
					mw.$("#mw_uploader").show();
                    mw.$("#upload_backup_info").html("");
				   
					mw.admin_import.move_uploaded_file_to_import(data.src);
				});

			    $(uploader).bind('progress', function(up, file) {

					mw.$("#mw_uploader").hide();
					mw.$("#mw_uploader_loading").show();
					 mw.tools.disable(mwd.getElementById('mw_uploader_loading'), 'Uploading...<span id="upload_backup_info"></span>');
                     mw.$("#upload_backup_info").html(file.percent + "%");
            	});

                $(uploader).bind('error', function(up, file) {
                   mw.notification.error("The file was not uploaded!");

            	});

		});


</script>

<div> <span id="mw_uploader" class="mw-ui-btn"><span class="ico iupload"></span><span>Upload file<span id="upload_backup_info"></span></span></span> </div>
<div class="vSpace"></div>
<module type="admin/import/process" />
<module type="admin/import/manage" />