<?

//$rand = rand();

if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>

<? if(!empty($data['custom_field_name'])) : ?>
<?php $rand = uniqid(); ?>

<div class="control-group">
 <label class="label"><? print $data["custom_field_name"]; ?></label>
 <div class="input-prepend input-append relative mw-custom-field-upload" id="upload_<?php print($rand); ?>">
    <span class="add-on">
        <i class="icon-file"></i>
    </span>
    <input type="text" class="no-post" autocomplete="off"  />
    <input type="hidden" autocomplete="off" />
    <button type="button" class="btn">Browse</button>
 </div>
</div>
<div class="progress" id="upload_progress_<?php print($rand); ?>">
    <div class="bar notransition" style="width: 0%;"></div>
</div>






<script>
    mw.require('tools.js');
    mw.require('files.js');
</script>

<script>
var uploader = mw.files.uploader({
    multiple:false,
    filters:['<?php print implode("','",$data['options']['file_types']); ?>]
});

var local_id = '<?php print($rand); ?>';


$(document).ready(function(){

    $(uploader).bind('progress', function(frame, file){
        mw.$("#upload_progress_"+local_id+" .bar").width(file.percent + '%')
        mw.$("#upload_progress_"+local_id).show();
    });
    $(uploader).bind('FileUploaded', function(frame, file){
        mw.$("#upload_<?php print($rand); ?> input[type='text']").val(file.name);
        mw.$("#upload_<?php print($rand); ?> input[type='hidden']").val(file.src);
        mw.$("#upload_progress_"+local_id).hide();
        mw.$("#upload_progress_"+local_id+" .bar").width(0)
    });

    mwd.getElementById('upload_<?php print($rand); ?>').appendChild(uploader);
});


</script>
<? endif; ?>
