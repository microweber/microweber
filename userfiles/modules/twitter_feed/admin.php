<?php only_admin_access(); ?>

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


<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-twitter-feed-settings">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e('Search string'); ?> <a href="https://dev.twitter.com/rest/public/search" target="_blank">[?]</a></label>
                    <input type="text" class="mw_option_field mw-ui-field mw-full-width" placeholder="Example: microweber" name="search_string" value="<?php print get_option('search_string', $params['id']); ?>">
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e('Number of items'); ?></label>
                    <input type="number" class="mw_option_field mw-ui-field mw-full-width" name="number_of_items" value="<?php print get_option('number_of_items', $params['id']); ?>">
                </div>

                <hr/>

                <h5>Access Token Settings</h5>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e('Consumer Key'); ?></label>
                    <input type="text" class="mw_option_field mw-ui-field mw-full-width" name="consumer_key" option-group="twitter_feed" value="<?php print get_option('consumer_key', 'twitter_feed'); ?>">
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e('Consumer Secret'); ?></label>
                    <input type="text" class="mw_option_field mw-ui-field mw-full-width" name="consumer_secret" option-group="twitter_feed" value="<?php print get_option('consumer_secret', 'twitter_feed'); ?>">
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e('Access Token'); ?></label>
                    <input type="text" class="mw_option_field mw-ui-field mw-full-width" name="access_token" option-group="twitter_feed" value="<?php print get_option('access_token', 'twitter_feed'); ?>">
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e('Access Token Secret'); ?></label>
                    <input type="text" class="mw_option_field mw-ui-field mw-full-width" name="access_token_secret" option-group="twitter_feed" value="<?php print get_option('access_token_secret', 'twitter_feed'); ?>">
                </div>

                <p><?php _e('Get your Twitter access keys'); ?> <a href="https://apps.twitter.com/app" target="_blank" class="mw-ui-link"><?php _e('from here'); ?></a></p>
            </div>
            <!-- Settings Content - End -->
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>