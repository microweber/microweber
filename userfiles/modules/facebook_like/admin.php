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

<div class="card-body px-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <?php if (!isset($params['live_edit'])): ?>
        <div class="card-header">
            <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
        </div>
    <?php endif; ?>

    <div class=" ">
        <?php $layout = get_option('layout', $params['id']); ?>
        <?php $url_to_like = get_option('url', $params['id']); ?>
        <?php $url_to_like = get_option('url', $params['id']); ?>
        <?php $color = get_option('color', $params['id']); ?>
        <?php $show_faces = get_option('show_faces', $params['id']); ?>


        <div class="module-live-edit-settings module-facebook-like-settings">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php _e("Layout"); ?></label>
                        <select name="layout" class="mw_option_field form-select" data-width="100%" data-size="5">
                            <option value="standard" <?php if ($layout == false or $layout == 'standard'): ?> selected="selected" <?php endif ?>><?php _e("Standard"); ?></option>
                            <option value="box_count" <?php if ($layout == 'box_count'): ?> selected="selected" <?php endif ?>><?php _e("Box count"); ?></option>
                            <option value="button_count" <?php if ($layout == 'button_count'): ?> selected="selected" <?php endif ?>><?php _e("Button count"); ?></option>
                            <option value="button" <?php if ($layout == 'button'): ?> selected="selected" <?php endif ?>><?php _e("Button"); ?></option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php _e("Color scheme"); ?></label>
                        <select name="color" class="mw_option_field form-select" data-width="100%" data-size="5">
                            <option value="light" <?php if ($color == false or $color == 'standard'): ?> selected="selected" <?php endif ?>><?php _e("Light"); ?></option>
                            <option value="dark" <?php if ($color == 'dark'): ?> selected="selected" <?php endif ?>><?php _e("Dark"); ?></option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php _e("Show faces"); ?></label>
                        <select name="show_faces" class="mw_option_field form-select" data-width="100%" data-size="5">
                            <option value="y" <?php if ($show_faces == false or $show_faces == 'y'): ?> selected="selected" <?php endif ?>><?php _e("Yes"); ?></option>
                            <option value="n" <?php if ($show_faces == 'n'): ?> selected="selected" <?php endif ?>><?php _e("No"); ?></option>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label class="form-label"><?php _e("Custom URL"); ?></label>
                    <input name="url" class="mw_option_field form-control" type="text" placeholder="<?php _e("If you fill this field the current link will be liked"); ?>" value="<?php print $url_to_like; ?>"/>
                </div>
            </div>
        </div>

    </div>
</div>
