<?

//$rand = rand();

if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>

<? if(!empty($data['custom_field_name'])) : ?>
<?php $rand = uniqid(); ?>

<div class="control-group">
 <label class="custom-field-title"><? print $data["custom_field_name"]; ?></label>
 <div class="input-prepend input-append relative inline-block mw-custom-field-upload" id="upload_<?php print($rand); ?>">
    <span class="add-on">
        <i class="icon-file"></i>
    </span>
    <input type="text" class="no-post" autocomplete="off"  />
    <input type="hidden" autocomplete="off" />
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
var uploader = mw.files.uploader({
    multiple:false,
    filetypes:'<? if(isarr($data['options']) and isset($data['options']['file_types'])): ?><?php print implode(",",$data['options']['file_types']); ?> <? endif ?>'
});

var local_id = '<?php print($rand); ?>';


$(document).ready(function(){

    $(uploader).bind('progress', function(frame, file){
        mw.$("#upload_progress_"+local_id+" .bar").width(file.percent + '%')
        mw.$("#upload_progress_"+local_id).show();   mw.log(file)
    });
    $(uploader).bind('FileUploaded', function(frame, file){

        mw.$("#upload_<?php print($rand); ?> input[type='text']").val(file.name);
        mw.$("#upload_<?php print($rand); ?> input[type='hidden']").val(file.src);
        mw.$("#upload_progress_"+local_id).hide();
        mw.$("#upload_progress_"+local_id+" .bar").width(0);
        mw.$("#upload_err"+local_id).hide();
    });


    $(uploader).bind('error', function(frame, file){

        mw.$("#upload_progress_"+local_id).hide();
        mw.$("#upload_err"+local_id).show().html("<strong>"+file.name+"</strong> - Invalid filetype!");
        mw.$("#upload_progress_"+local_id+" .bar").width(0);
    });
    $(uploader).bind('responseError', function(frame, json){

        mw.$("#upload_progress_"+local_id).hide();
        mw.$("#upload_err"+local_id).show().html("<strong>Error "+json.error.code+"</strong> - " + json.error.message);
        mw.$("#upload_progress_"+local_id+" .bar").width(0);
    });

    mwd.getElementById('upload_<?php print($rand); ?>').appendChild(uploader);
});


</script>
<? endif; ?>
