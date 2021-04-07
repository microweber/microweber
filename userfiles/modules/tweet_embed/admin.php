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

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php _e($module_info['name']); ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <?php
        $option_group = $params['id'];

        if (isset($params['option-group'])) {
            $option_group = $params['option-group'];
        }

        $twitter_url = get_option('twitter_url', $option_group);
        ?>

        <div class="module-live-edit-settings module-tweet-embed-settings">
            <div class="form-group">
                <label class="control-label"><?php _e('Tweet Post URL'); ?></label>
                <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field form-control" name="twitter_url" value="<?php print $twitter_url; ?>" placeholder="Enter your Tweet URL"/>
            </div>
        </div>
    </div>
</div>
