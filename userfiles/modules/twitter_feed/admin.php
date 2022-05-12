<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php $twitter_feed_theme = get_option('twitter_feed_theme', $params['id']); ?>
<?php $twitter_feed_width = get_option('twitter_feed_width', $params['id']); ?>
<?php $twitter_feed_align = get_option('twitter_feed_align', $params['id']); ?>
<?php $twitter_feed_conversation = get_option('twitter_feed_conversation', $params['id']); ?>
<?php $twitter_feed_cards = get_option('twitter_feed_cards', $params['id']); ?>



<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <script type="text/javascript">mw.require('options.js');</script>

        <script type="text/javascript">
            $(window).ready(function () {
                mw.options.form('.module-twitter-feed-settings', function () {
                    if (mw.notification != undefined) {
                        mw.notification.success('Twitter feed settings saved');
                    }
                    mw.reload_module_parent('twitter_feed'); //reload the module in live edit
                });
            });
        </script>

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content pt-3">
            <div class="tab-pane fade show active" id="settings">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-twitter-feed-settings">
                    <div class="form-group">
                        <label class="control-label"><?php _e('Search string'); ?> <a href="https://dev.twitter.com/rest/public/search" target="_blank">[?]</a></label>
                        <input type="text" class="mw_option_field form-control" placeholder="Example: technology" name="search_string" value="<?php print get_option('search_string', $params['id']); ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e('Number of items'); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e('Type your prefer number of displayed posts'); ?></small>

                        <input type="number" class="mw_option_field form-control" name="number_of_items" value="<?php print get_option('number_of_items', $params['id']); ?>">
                    </div>

                    <hr class="thin"/>

                    <button class="btn btn-link btn-sm px-0" onclick="$('#toggle-advanced-settings-twitter').toggle();"><?php _e("Advanced settings") ?></button>
                    <div class="pt-3" style="display: none;" id="toggle-advanced-settings-twitter">
                        <h5 class="font-weight-bold mb-3"><?php _e('Access Token Settings'); ?></h5>

                        <div class="form-group">
                            <label class="control-label"><?php _e('Consumer Key'); ?></label>
                            <input type="text" class="mw_option_field form-control" name="consumer_key" option-group="twitter_feed" value="<?php print get_option('consumer_key', 'twitter_feed'); ?>">
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php _e('Consumer Secret'); ?></label>
                            <input type="text" class="mw_option_field form-control" name="consumer_secret" option-group="twitter_feed" value="<?php print get_option('consumer_secret', 'twitter_feed'); ?>">
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php _e('Access Token'); ?></label>
                            <input type="text" class="mw_option_field form-control" name="access_token" option-group="twitter_feed" value="<?php print get_option('access_token', 'twitter_feed'); ?>">
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php _e('Access Token Secret'); ?></label>
                            <input type="text" class="mw_option_field form-control" name="access_token_secret" option-group="twitter_feed" value="<?php print get_option('access_token_secret', 'twitter_feed'); ?>">
                        </div>

                        <p><?php _e('Get your Twitter access keys'); ?> <a href="https://apps.twitter.com/app" target="_blank" class="mw-ui-link"><?php _e('from here'); ?></a></p>
                    </div>
                </div>
                <!-- Settings Content - End -->
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
