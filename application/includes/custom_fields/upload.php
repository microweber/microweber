<?php

//$rand = rand();

if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>

<?php if(!empty($data['custom_field_name'])) : ?>
<?php $rand = uniqid(); ?>

<div class="control-group">
 <label class="custom-field-title"><?php print $data["custom_field_name"]; ?></label>
 <div class="input-prepend input-append relative inline-block mw-custom-field-upload" id="upload_<?php print($rand); ?>">
    <span class="add-on">
        <i class="icon-file"></i>
    </span>
    <input type="text" class="no-post" id="file_name<?php print $data["custom_field_name"]; ?>" name="<?php print $data["custom_field_name"]; ?>" autocomplete="off"  />

    <button type="button" class="btn">Browse</button>
 </div>
</div>
<div class="progress" id="upload_progress_<?php print($rand); ?>" style="display:none;">
    <div class="bar notransition" style="width: 0%;"></div>
</div>

    <div class="alert alert-error" id="upload_err<?php print($rand); ?>"  style="display:none;">

    </div>




<script>
    mw.require('tools.js');
    mw.require('files.js');
</script>

<script>

    formHasUploader = true;

   <?php $up = 'up'.uniqid(); ?>

 <?php print $up; ?> = mw.files.uploader({
    multiple:false,
    autostart:false,
    filetypes:'<?php if(isarr($data['options']) and isset($data['options']['file_types'])): ?><?php print implode(",",$data['options']['file_types']); ?> <?php endif ?>'
});

var local_id = '<?php print($rand); ?>';


$(document).ready(function(){


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

<?php


if(isset($data) and !empty($data) and isset($data['rel']) and ($data['rel'] == 'module' or $data['rel'] == 'modules') and isset($data['rel_id']) ) : ?>
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
