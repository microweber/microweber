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
        if (get_option('fb-page', $params['id'])) {
            $fbPage = get_option('fb-page', $params['id']);
        } else {
            $fbPage = 'https://www.facebook.com/Microweber/';
        }

        if (get_option('width', $params['id'])) {
            $width = get_option('width', $params['id']);
        } else if (isset($params['width'])) {
            $width = $params['width'];
        } else {
            $width = '380';
        }

        if (get_option('height', $params['id'])) {
            $height = get_option('height', $params['id']);
        } else {
            $height = '300';
        }

        if (get_option('friends', $params['id'])) {
            if (get_option('friends', $params['id']) == 'true') {
                $friends = 'true';
            } else {
                $friends = 'false';
            }
        } else {
            $friends = 'false';
        }

        if (get_option('timeline', $params['id'])) {
            if (get_option('timeline', $params['id']) == 'true') {
                $timeline = '&tabs=timeline';
            } else {
                $timeline = '';
            }
        } else {
            $timeline = '';
        }

        ?>

        <div class="module-live-edit-settings module-facebook-page-settings">
            <div class="mw-ui-field-holder">
                <label class="mw-ui-label" for="fb-page"><?php _e('Facebook page URL'); ?></label>
                <input name="fb-page" data-refresh="facebook_page" class="mw_option_field mw-ui-field mw-full-width" type="text" value="<?php print $fbPage; ?>" id="fb-page" placeholder="Example: https://www.facebook.com/Microweber/"/>
            </div>

            <div class="mw-flex-row">
                <div class="mw-flex-col-xs-6">
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label" for="width"><?php _e('Box width (px)'); ?><br/></label>
                        <input name="width" data-refresh="facebook_page" class="mw_option_field mw-ui-field mw-full-width" type="text" value="<?php print $width; ?>" id="width"/>
                        <small>Min: 180px - Max: 500px</small>
                    </div>
                </div>

                <div class="mw-flex-col-xs-6">
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label" for="height"><?php _e('Box height (px)'); ?><br/></label>
                        <input name="height" data-refresh="facebook_page" class="mw_option_field mw-ui-field mw-full-width" type="text" value="<?php print $height; ?>" id="height"/>
                        <small>Min: 70px</small>
                    </div>
                </div>
            </div>

            <div class="mw-flex-row">
                <div class="mw-flex-col-xs-6">
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-check">
                            <input type="checkbox" name="friends" data-refresh="facebook_page" class="mw_option_field" value="true" id="friends" <?php if ($friends == 'true') {
                                echo 'checked';
                            } ?> />
                            <span></span><span><?php _e('Show friends faces'); ?></span>
                        </label>
                    </div>
                </div>

                <div class="mw-flex-col-xs-6">
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-check">
                            <input type="checkbox" name="timeline" data-refresh="facebook_page" class="mw_option_field" value="true" id="timeline" <?php if ($timeline == 'true') {
                                echo 'checked';
                            } ?> /><span></span><span><?php _e('Show timeline'); ?></span>
                        </label>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>