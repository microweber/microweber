<?php only_admin_access(); ?>
<?php

$mod_id = false;


if (isset($params['parent-module-id'])) {
    $mod_id = $params['parent-module-id'];
}
if (!$mod_id) {
    return;
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
        });
    });
</script>
<?php

$selected = get_option('selected_text_name', $mod_id);


$dynamic_texts = get_dynamic_text();


//($params);
//return;
?>


<h5>  <?php _e("Which text to display?"); ?></h5>

<select name="selected_text_name" option-group="<?php print $mod_id ?>" class="mw-ui-field mw_option_field">
    <option value="" <?php if (!$selected or $selected == 'default'): ?>  selected="selected"  <?php endif; ?> ><?php _e('Default'); ?></option>
    <?php if (is_array($dynamic_texts)) : ?>
        <?php foreach ($dynamic_texts as $dynamic_text) : ?>
            <option value="<?php echo $dynamic_text['name']; ?>" <?php if ($selected == $dynamic_text['name']): ?>  selected="selected"  <?php endif; ?> ><?php echo $dynamic_text['name']; ?></option>
        <?php endforeach; ?>
    <?php endif; ?>

</select>

<hr>