<?php

 

if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>
<?php $up = 'up'.uniqid(); ?>
<?php if(!empty($data['custom_field_name'])) : ?>
<?php $rand = uniqid(); ?>
<?php

    $is_required = (isset($data['options']) == true and isset($data['options']["required"]) == true);

?>

<div class="control-group form-group">
  <label class="mw-ui-label"><?php print $data["custom_field_name"]; ?></label>
  <div class="relative inline-block mw-custom-field-upload" id="upload_<?php print($rand); ?>">
    <div class="mw-ui-row-nodrop">
      <div class="mw-ui-col" style="width: auto">

      <span class="mw-ui-btn" id="upload_button_<?php print($rand); ?>">
        <span class="mw-icon-upload"></span><?php _e("Browse"); ?>
        </span>
        <input type="hidden" class="mw-ui-invisible-field" id="file_name<?php print $data["custom_field_name"]; ?>" autocomplete="off"  />
        
         <input type="hidden" <?php if($is_required){ ?> required <?php } ?> class="mw-ui-invisible-field" id="uploaded_file_src<?php print($rand); ?>" name="<?php print $data["custom_field_name"]; ?>" autocomplete="off"  />
        
        

        <span class="ellipsis" id="val_<?php print $rand; ?>" style="font-size: small; opacity: 0.66; max-width: 200px; margin-left: 12px;"></span>

      </div>

    </div>
  </div>
</div>

<div class="alert alert-error" id="upload_err<?php print($rand); ?>"  style="display:none;"> </div>
<script>
    mw.require('tools.js');
    mw.require('files.js');
</script> 
<script>

    formHasUploader = true;






$(document).ready(function(){
	 
	 <?php print $up; ?> = mw.files.uploader({
    multiple:false,
	name:'<?php print $data["custom_field_name"]; ?>',
    autostart:true,
    filetypes:'<?php if(is_array($data['options']) and isset($data['options']['file_types'])): ?><?php print implode(",",$data['options']['file_types']); ?> <?php endif ?>'
});

var local_id = '<?php print($rand); ?>';


    $(<?php print $up; ?>).bind('FilesAdded', function(frame, file){

       mwd.getElementById('file_name<?php print $data["custom_field_name"]; ?>').value = file[0].name;

    });

    $(<?php print $up; ?>).bind('progress', function(frame, file){
        mw.$("#upload_progress_"+local_id+" .bar").width(file.percent + '%')
        mw.$("#upload_progress_"+local_id).show();  
		
	    mw.log(file)
    });

    $(<?php print $up; ?>).bind('FileUploaded', function(frame, file){
		mw.$("#uploaded_file_src<?php print($rand); ?>").val(file.src);
        mw.$("#upload_<?php print($rand); ?> input[type='text']").val(file.src);
        mw.$("#upload_progress_"+local_id).hide();
        mw.$("#upload_progress_"+local_id+" .bar").width(0);
        mw.$("#upload_err"+local_id).hide();
        mw.$("#val_<?php print $rand; ?>").html(file.src.split('/').pop());
    });


    $(<?php print $up; ?>).bind('error', function(frame, file){

        mw.$("#upload_progress_"+local_id).hide();
        mw.$("#upload_err"+local_id).show().html("<strong>" + file.name + "</strong> - Invalid filetype!");
        mw.$("#upload_progress_"+local_id+" .bar").width(0);
        mw.$("#val_<?php print $rand; ?>").empty();

    });


    $(<?php print $up; ?>).bind('responseError', function(frame, json){

        mw.$("#upload_progress_"+local_id).hide();
        mw.$("#upload_err"+local_id).show().html("<strong>Error "+json.error.code+"</strong> - " + json.error.message);
        mw.$("#upload_progress_"+local_id+" .bar").width(0);

        mw.$("#val_<?php print $rand; ?>").empty();
    });

    mwd.getElementById('upload_button_<?php print($rand); ?>').appendChild(<?php print $up; ?>);

<?php if(isset($data) and !empty($data) and isset($data['rel']) and ($data['rel'] == 'module' or $data['rel'] == 'modules') and isset($data['rel_id']) ) : ?>
     <?php print $up; ?>.contentWindow.onload = function(){
          mw.postMsg(<?php print $up; ?>.contentWindow, {
    		rel:"<?php print($data['rel']); ?>",
			custom_field_id:"<?php print($data['id']); ?>",
    		rel_id:"<?php print($data['rel_id']); ?>"
        });
     }
<?php endif; ?>

	


});


</script>
<?php endif; ?>
