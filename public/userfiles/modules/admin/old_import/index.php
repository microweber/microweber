<?php must_have_access(); ?>
<script  type="text/javascript">
    mw.require("<?php print $config['url_to_module']; ?>import.js");
</script>

<script>
    mw.require("files.js");
</script>
<script type="text/javascript">
		var uploader = mw.files.uploader({
			filetypes:"all",
			multiple:false,
            element: mw.$("#mw_uploader")
		});


		$(document).ready(function(){
			//  mw.admin_import.start_batch_process();


			// mw.reload_module_interval('admin/import/process', 1500);


				$(uploader).on("FileUploaded", function(obj, data){
					mw.$("#mw_uploader_loading").hide();
					mw.$("#mw_uploader").show();
                    mw.$("#upload_backup_info").html("");

					mw.admin_import.move_uploaded_file_to_import(data.src);
				});

			    $(uploader).on('progress', function(up, file) {

					mw.$("#mw_uploader").hide();
					mw.$("#mw_uploader_loading").show();
					 mw.tools.disable(document.getElementById('mw_uploader_loading'), '<?php _e('Uploading...'); ?><span id="upload_backup_info"></span>');
                     mw.$("#upload_backup_info").html(file.percent + "%");
            	});

                $(uploader).on('error', function(up, file) {
                   mw.notification.error("The file was not uploaded!");

            	});



				ModalContent = mw.$('#mw_import_to_page_holder').html();
				mw.$('#mw_import_to_page_holder').remove();

		});



mw.confirm_import_file = function($filename){



	mw.alert(ModalContent);
	$(".mw_import_file").val($filename);

	mw.$('#mw_alert .mw-cancel').hide();
}




mw.ok_import_file = function(){

  var file = $('#mw_import_file').val()
  var page_id = $('#mw_import_to_page_selector').val()
  mw.admin_import.restore_to_page(file,page_id);
  	// mw.reload_module_interval('admin/import/process', 1500);
   $('.mw_modal').remove()



}
</script>





<div class="mw-ui-row admin-section-bar">
    <div class="mw-ui-col">
        <h2><?php _e("Import Content"); ?></h2>
    </div>
</div>
<div class="admin-side-content">
    <div class="mw-module-admin-wrap">
<div id="mw_import_to_page_holder">
  <?php $all_pages = get_pages(); ?>
  <?php if(!empty($all_pages)): ?>
  <h5>
    <?php _e("Select a page to import the content to"); ?>
    :</h5>
  <select name="import_to_page" id="mw_import_to_page_selector" class="mw-ui-field">
    <?php foreach($all_pages as $page): ?>
    <option value="<?php print $page['id']  ?>"><?php print $page['title']; ?></option>
    <?php endforeach; ?>
  </select>
  <?php endif; ?>
  <input type="hidden" name="filename" id="mw_import_file" class="mw_import_file" />
  <button onclick="mw.ok_import_file()" class="  btn btn-primary">
  <?php _e("Start import"); ?>
  </button>
</div>




 <span id="mw_uploader" class="  btn btn-primary"><span class="mw-icon-upload"></span>&nbsp;<span>

  <span id="mw_uploader_loading"></span>



  <?php _e("Upload file"); ?>
  <span id="upload_backup_info"></span></span></span> </div>

  <div id="import-progress-log-holder" style="display:none">
  <?php _e('Progress:'); ?> <span id="import-progress-log-holder-values"></span>
<meter value="" optimum="100" high="90" low="40" max="100" min="0" id="import-progress-log-meter"><?php _e('Import progress'); ?></meter>

<span data-tip="Cancel" class="mw-icon-close show-on-hover tip" onclick="mw.admin_import.cancel_batch_process();"></span>


  </div>

<module type="admin/import/manage" />
<!-- <br />
<br />
<hr />
<module type="admin/import/export" /> -->
</div>
