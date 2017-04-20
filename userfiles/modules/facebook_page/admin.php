<?php only_admin_access(); ?>

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
?>

<script type="text/javascript">
    $(document).ready(function () {
        mw.lib.require('bootstrap3ns');
    });
</script>

<div class="module-live-edit-settings">
    <div class="bootstrap3ns">

        <div class="row">
            <div class="col-xs-12">

                <div class="form-group">
                    <label class="control-label" for="fb-page"><?php _e('Facebook page URL'); ?></label>
                    <input name="fb-page" data-refresh="facebook_page" class="form-control mw_option_field" type="text" value="<?php print $fbPage; ?>" id="fb-page"/>
                </div>

                <div class="form-group">
                    <label class="control-label" for="width"><?php _e('Box width (px)'); ?>
                        <small>min: 180px - max: 500px</small>
                    </label>
                    <input name="width" data-refresh="facebook_page" class="form-control mw_option_field" type="text" value="<?php print $width; ?>" id="width"/>
                </div>

                <div class="form-group">
                    <label class="control-label" for="height"><?php _e('Box height (px)'); ?>
                        <small>min: 70px</small>
                    </label>
                    <input name="height" data-refresh="facebook_page" class="form-control mw_option_field" type="text" value="<?php print $height; ?>" id="height"/>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="friends" data-refresh="facebook_page" class="mw_option_field" value="true" id="friends" <?php if ($friends == 'true') {
                                    echo 'checked';
                                } ?> /> <?php _e('Show friends faces'); ?>
                            </label>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>