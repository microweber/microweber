<?php only_admin_access(); ?>

<?php

$file =  get_option('file', $params['id']);

?>

<label class="mw-ui-label">Include file</label>
<input type="text" value="<?php print $file; ?>" name="file" class="mw_option_field mw-ui-field w100" id="file" />

<script>
$(window).bind('load', function(){

});

</script>



