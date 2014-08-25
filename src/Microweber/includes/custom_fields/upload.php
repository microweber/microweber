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
  <label class="custom-field-title"><?php print $data["custom_field_name"]; ?></label>
  <div class="relative inline-block mw-custom-field-upload" id="upload_<?php print($rand); ?>">
    <div class="mw-ui-row-nodrop">
      <div class="mw-ui-col">
        <input type="text" <?php if($is_required){ ?> required <?php } ?> class="form-control" id="file_name<?php print $data["custom_field_name"]; ?>" name="<?php print $data["custom_field_name"]; ?>" autocomplete="off"  />
                    

        
      </div>
      <div class="mw-ui-col">
        <button type="button" class="btn">
        <?php _e("Browse"); ?>
        </button>
      </div>
    </div>
  </div>
</div>
<div class="progress" id="upload_progress_<?php print($rand); ?>" style="display:none;">
  <div class="bar notransition" style="width: 0%;"></div>
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
        mw.$("#upload_progress_"+local_id).show();   mw.log(file)
    });

    $(<?php print $up; ?>).bind('FileUploaded', function(frame, file){

        mw.$("#upload_<?php print($rand); ?> input[type='text']").val(file.src);
        mw.$("#upload_progress_"+local_id).hide();
        mw.$("#upload_progress_"+local_id+" .bar").width(0);
        mw.$("#upload_err"+local_id).hide();

    });


    $(<?php print $up; ?>).bind('error', function(frame, file){

        mw.$("#upload_progress_"+local_id).hide();
        mw.$("#upload_err"+local_id).show().html("<strong>" + file.name + "</strong> - Invalid filetype!");
        mw.$("#upload_progress_"+local_id+" .bar").width(0);


    });


    $(<?php print $up; ?>).bind('responseError', function(frame, json){

        mw.$("#upload_progress_"+local_id).hide();
        mw.$("#upload_err"+local_id).show().html("<strong>Error "+json.error.code+"</strong> - " + json.error.message);
        mw.$("#upload_progress_"+local_id+" .bar").width(0);


    });

    mwd.getElementById('upload_<?php print($rand); ?>').appendChild(<?php print $up; ?>);

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
