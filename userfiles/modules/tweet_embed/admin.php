<?php only_admin_access(); ?>

<?php
$option_group = $params['id'];

if (isset($params['option-group'])) {
    $option_group = $params['option-group'];
}

$twitter_url = get_option('twitter_url', $option_group);
?>

<div class="module-live-edit-settings module-tweet-embed-settings">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php print _e('Tweet Post URL'); ?></label>
        <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-full-width" name="twitter_url" value="<?php print $twitter_url; ?>" placeholder="Enter your Tweet URL"/>
    </div>
</div>