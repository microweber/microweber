<?php
$option_group = 'file_append';
if(!isset($params['option_group'])) {
	_e('First you need to set option group.');
	return;
}
$option_group = $params['option_group'];
?>
<style>
.mw-append-file {
	padding: 5px;
	border: 1px dotted #e2e2e2;
	margin-top: 8px;
	width: 500px;
}

.mw-append-file-delete {
	color: red;
	float: right;
	margin-top: -15px;
	font-weight: bold;
	font-size: 13px;
	cursor: pointer;
}
</style>

<script type="text/javascript">

function getAppendFiles() {
	 var append_files = mw.$('#append_files').val();
	if (append_files == '') {
		var append_files_array = [];
	} else {
		var append_files_array = append_files.split(',');
	}

	return append_files_array;
}

$(document).ready(function(){

	$('body').on('click', '.mw-append-file-delete', function() {

		var append_files_array = getAppendFiles();

		for( var i = 0; i < append_files_array.length; i++){
		  if (append_files_array[i] === $(this).attr('file-url')) {
			  append_files_array.splice(i, 1);
		  }
		}

		mw.$('#append_files').val(append_files_array.join(',')).trigger('change');

		$(this).parent().remove();
	});

	var uploader = mw.uploader({
	   filetypes:"images,videos",
	   multiple:false,
	   element:"#mw_uploader"
	 });

 	$(uploader).bind("FileUploaded", function(event, data){

 	  	 var append_file = '<div class="mw-append-file"><div>'+data.src+'</div><div class="mw-append-file-delete" file-url="'+data.src+'"><i class="mw-icon-close-round"></i></div></div>';

         mw.$("#mw_uploader_loading").hide();
         mw.$("#mw_uploader").show();
         mw.$("#upload_info").html('');
         mw.$("#upload_files").append(append_file);

         var append_files_array = getAppendFiles();

         append_files_array.push(data.src);

         mw.$('#append_files').val(append_files_array.join(',')).trigger('change');

	});

   $(uploader).bind('progress', function(up, file) {
       mw.$("#mw_uploader").hide();
       mw.$("#mw_uploader_loading").show();
       mw.$("#upload_info").html(file.percent + "%");
    });

   $(uploader).bind('error', function(up, file) {
       mw.notification.error("The file is not uploaded.");
   });

});
</script>

<?php
$appendFiles = explode(",", get_option('append_files', $option_group));
?>
<input name="append_files" value="<?php print get_option('append_files', $option_group) ?>" class="mw-ui-field mw_option_field w100" id="append_files" option-group="<?php echo $option_group; ?>" data-option-group="<?php echo $option_group; ?>" type="hidden"/>


<span id="mw_uploader" class="mw-ui-btn">
<span class="ico iupload"></span>
<span>Upload file<span id="upload_info"></span>
</span>
</span>

<div id="upload_files">
<?php
foreach($appendFiles as $file) {
	if (empty($file)) {
		continue;
	}
	?>
	<div class="mw-append-file"><div><?php echo $file; ?></div><div class="mw-append-file-delete" file-url="<?php echo $file; ?>"><i class="mw-icon-close-round"></i></div></div>
	<?php
}
?>
</div>
