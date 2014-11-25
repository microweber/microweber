<?php only_admin_access(); ?>

<?php

$html =  get_option('html', $params['id']);

?>
<input type="hidden" value="<?php $html; ?>" name="html" class="mw_option_field" id="html" />

<script>
$(window).bind('load', function(){
var up = mw.uploader({element:"#insertimage", filetypes:"images", multiple:false});
$(up).bind("FileUploaded", function(a,b){
    mw.$('#html').val(mw.$('#html').val() + '<div class="free"><img src="'+b.src+'"></div>');
    mw.$('#html').trigger('change');
});

mw.$("#clear").click(function(){
  mw.$('#html').val('').trigger('change');
});

});

</script>

<span class="mw-ui-btn" id="insertimage">Add image</span>
<span class="mw-ui-btn" id="clear">Clear All</span>


