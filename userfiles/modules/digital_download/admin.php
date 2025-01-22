<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card-body mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>

        <?php if (!isset($params['live_edit'])): ?>
            <h3 class="main-pages-title">
                <?php echo $module_info['name']; ?>
            </h3>
        <?php endif; ?>
    </div>

    <?php
    $button_text = get_option('button_text', $params['id']);
    $button_alignment = get_option('button_alignment', $params['id']);
    $download_url = get_option('download_url', $params['id']);
    ?>
    <div>

        <div class="form-group button_text">
            <label class="form-label font-weight-bold mb-3"><?php _e("Button text"); ?></label>
            <input type="text" name="button_text" class="mw_option_field form-control" value="<?php print $button_text; ?>" data-refresh=""/>
        </div>

        <div class="form-group button_alignment">
            <label class="form-label font-weight-bold mb-3"><?php _e("Button alignment"); ?></label>
            <select name="button_alignment" class="mw_option_field form-control">
                <option value="left" <?php if ($button_alignment == 'left'): ?>selected<?php endif; ?>><?php _e("Left"); ?></option>
                <option value="center" <?php if ($button_alignment == 'center'): ?>selected<?php endif; ?>><?php _e("Center"); ?></option>
                <option value="right" <?php if ($button_alignment == 'right'): ?>selected<?php endif; ?>><?php _e("Right"); ?></option>
            </select>
        </div>

        <div class="form-group download_url">
            <label class="form-label font-weight-bold mb-3"><?php _e("Download URL"); ?></label>
            <input type="text" name="download_url" class="mw_option_field form-control" value="<?php print $download_url; ?>" data-refresh=""/>
        </div>



    </div>

</div>
