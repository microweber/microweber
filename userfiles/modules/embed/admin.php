<?php only_admin_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <?php
        $rand_id = md5(serialize($params));
        $hide_in_live_edit = get_option('hide_in_live_edit', $params['id']);
        if ($hide_in_live_edit != '' OR $hide_in_live_edit != false) {
            $hide_in_live_edit == true;
        }
        ?>

        <div class="module-live-edit-settings module-embed-settings">
            <div id="mw_email_source_code_editor<?php print $rand_id ?>">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Insert Embed Code"); ?></label>
                    <textarea name="source_code" class="mw_option_field mw-ui-field mw-full-width" style="height:86px;" data-refresh="embed"><?php print get_option('source_code', $params['id']) ?></textarea>
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-check">
                        <input type="checkbox" name="hide_in_live_edit" class="mw_option_field" value="true" data-refresh="embed" <?php if ($hide_in_live_edit): ?>checked<?php endif; ?> /><span></span><span><?php _e("Hide in Live Edit"); ?></span>
                    </label>
                </div>
            </div>
        </div>

    </div>
</div>
