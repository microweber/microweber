<?php must_have_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('#<?php print $params['id']?>', function () {
            mw.notification.success("<?php _ejs('Comments engine changes are saved.'); ?>");
            mw.reload_module_parent('comments');
        });
    });
</script>
<?php $engine = get_option('engine', 'comments'); ?>

<div class="form-group">
    <label class="control-label"><?php _e("Comments engine"); ?></label>

    <select name="engine" option-group="comments" class="mw_option_field selectpicker" data-width="100%" data-also-reload="#<?php print $params['id'] ?>">
        <option value="default" <?php if (!$engine or $engine == 'default'): ?>  selected="selected"  <?php endif; ?> ><?php _e('Default'); ?></option>
        <option value="facebook" <?php if ($engine == 'facebook'): ?>  selected="selected"  <?php endif; ?> >Facebook</option>
        <option value="disqus" <?php if ($engine == 'disqus'): ?>  selected="selected"  <?php endif; ?> >Disqus</option>
    </select>
</div>

<?php if ($engine == 'disqus'): ?>
    <div class="form-group">
        <label class="control-label">Disqus shortname</label>
        <?php $disqus_shortname = get_option('disqus_shortname', 'comments'); ?>
        <input type="text" name="disqus_shortname" value="<?php print $disqus_shortname ?>" class="mw_option_field form-control" option-group="comments"/>
    </div>
<?php elseif ($engine == 'facebook'): ?>
    <div class="form-group">
        <label class="control-label">Facebook APP ID</label>
        <?php $facebook_app_id = get_option('facebook_app_id', 'comments'); ?>
        <input type="text" name="facebook_app_id" value="<?php print $facebook_app_id ?>" class="mw_option_field form-control" option-group="comments" />
    </div>

    <div class="form-group">
        <label class="control-label"><?php _e('Number of comments'); ?></label>
        <?php $facebook_number_of_comments = get_option('facebook_number_of_comments', 'comments'); ?>
        <input type="number" name="facebook_number_of_comments" value="<?php print $facebook_number_of_comments ?>" class="mw_option_field form-control" option-group="comments"/>
    </div>

    <div class="form-group">
        <label class="control-label"><?php _e('Color scheme'); ?></label>
        <?php $facebook_color_scheme = get_option('facebook_color_scheme', 'comments'); ?>

        <select name="facebook_color_scheme" option-group="comments" class="mw_option_field selectpicker" data-width="100%">
            <option value="default" <?php if (!$facebook_color_scheme or $facebook_color_scheme == 'default'): ?>  selected="selected"  <?php endif; ?> ><?php _e('Default'); ?></option>
            <option value="light" <?php if ($facebook_color_scheme == 'light"  '): ?>  selected="selected"  <?php endif; ?> ><?php _e('Light'); ?></option>
            <option value="dark" <?php if ($facebook_color_scheme == 'dark"'): ?>  selected="selected"  <?php endif; ?> ><?php _e('Dark'); ?></option>
        </select>
    </div>
<?php endif; ?>
