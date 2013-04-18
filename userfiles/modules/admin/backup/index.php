<? if(!is_admin()){error("must be admin");}; ?>
<script  type="text/javascript">
    mw.require("<? print $config['url_to_module']; ?>backup.js");
	mw.require("files.js");
</script>
<style>
#mw_upsdfsdloader.disabled iframe {
	top:-9999px;
}
</style>

<div id="mw_backups_settings">
  <div class="mw_edit_page_left" id="mw_edit_page_left" style="width: 195px;">
    <h2 style="padding:30px 0 0 25px;"><span class="ico imanage-module"></span>&nbsp;Backups</h2>
    <div class="mw-admin-side-nav"  >
      <div id="">
        <ul>
          <li><a href="<? print $config['url']; ?>?backup_action=manage">Manage</a></li>
          <li><a href="<? print $config['url']; ?>?backup_action=settings">Settings</a></li>
        </ul>
      </div>
      <div>
        <div class="vSpace">&nbsp;</div>
        <a href="javascript:mw.admin_backup.create('.mw_edit_page_right')" class="mw-ui-btn"><span class="ico iplus"></span><span>New Database Backup</span></a> <a href="javascript:mw.admin_backup.create_full('.mw_edit_page_right')" class="mw-ui-btn"><span class="ico iplus"></span><span>New FULL Backup</span></a> 
        <script type="text/javascript">
		var uploader = mw.files.uploader({
			filetypes:"zip,sql",
			multiple:false
		});

		_mw_log_reload_int = false;
		$(document).ready(function(){
			
			
			if(_mw_log_reload_int == false){
						_mw_log_reload_int = true;
					 mw.reload_module_interval("#mw_backup_log", 3000);	
					}
			
			
		mw.$("#mw_uploader").append(uploader);
				$(uploader).bind("FileUploaded", function(obj, data){
					mw.admin_backup.move_uploaded_file_to_backup(data.src);
					//mw.tools.enable(mwd.getElementById('mw_uploader'));	
					mw.$("#mw_uploader_loading").hide();
					mw.$("#mw_uploader").show();
					
				});
	 
			    $(uploader).bind('progress', function(up, file) {
					
					
					
					
					mw.$("#mw_uploader").hide();
					mw.$("#mw_uploader_loading").show();
                // mw.notification.warning("Still uploading...", 5000);
					 mw.tools.disable(mwd.getElementById('mw_uploader_loading'), "Uploading...");	
            	});
				
				
			
			
		});


		</script>
        <div class="vSpace">&nbsp;</div>
        <div id="mw_uploader" class="mw-ui-btn"><span class="ico iplus"></span><span>Upload backup</span></div>
        <div id="mw_uploader_loading" class="mw-ui-btn" style="display:none;">Uploading files</div>
        
        <div class="vSpace">&nbsp;</div>
        
                <module id="mw_backup_log" type="admin/backup/log"/>
        
        
      </div>
    </div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px;">
    <? if(isset($_GET['backup_action'])): ?>
    <module type="admin/backup/<? print $_GET['backup_action'] ?>" />
    <? else :?>
    <module type="admin/backup/manage" />
    <? endif ;?>
  </div>
</div>
