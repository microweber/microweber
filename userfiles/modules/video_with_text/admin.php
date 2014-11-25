<?php only_admin_access(); ?>
<?php $video =  get_option('video', $params['id']); ?>

<label>Video url</label>
<input class="mw_option_field mw-ui-field"  name="video"  value="<?php print $video; ?>" />
