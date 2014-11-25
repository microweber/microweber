<?php only_admin_access(); ?>

<style>

</style>

<?php

    $before =  get_option('before', $params['id']);
    $after =  get_option('after', $params['id']);



?>
<div class="module-live-edit-settings">



<div class="mw-ui-box mw-ui-box-content">
<label class="mw-ui-label">Upload 2 images</label>
<div class="mw-ui-field-holder">
  <span class="mw-ui-btn" id="before"><span class="mw-icon-upload"></span>Before</span>
      vs.
  <span class="mw-ui-btn" id="after"><span class="mw-icon-upload"></span>After</span>
</div>
</div>



<input type="hidden" class="mw_option_field" name="before" id="beforeval" value="<?php print $before; ?>" />
<input type="hidden" class="mw_option_field" name="after" id="afterval" value="<?php print $after; ?>" />

</div>

<script>

$(document).ready(function(){
    var before = mw.uploader({
          filetypes:"images,videos",
          multiple:false,
          element:"#before"
    });
    $(before).bind('FileUploaded', function(a,b){
        mw.$("#beforeval").val(b.src).trigger('change');
    });
    var after = mw.uploader({
          filetypes:"images,videos",
          multiple:false,
          element:"#after"
    });
    $(after).bind('FileUploaded', function(a,b){
        mw.$("#afterval").val(b.src).trigger('change');
    });
});
</script>