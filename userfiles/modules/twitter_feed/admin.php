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

        <div class="tab-content py-3">
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

                    <div class="form-group">
                        <label class="control-label"><?php _e('Width'); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e('Choose width for the twitter box from 250 to 550 (px)'); ?></small>

                        <input type="number" min="250" max="550" class="mw_option_field form-control" name="twitter_feed_width" value="<?php print get_option('twitter_feed_width', $params['id']); ?>">
                    </div>


                    <div class="form-group">
                        <label class="control-label"><?php _e('Theme'); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e('Choose you prefer theme for the twitter card'); ?></small>

                        <select name="twitter_feed_theme" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true">
                            <option value="light" <?php if ('light' == $twitter_feed_theme): ?>   selected="selected"  <?php endif; ?> ><?php _e("Light"); ?></option>
                            <option value="dark" <?php if ('dark' == $twitter_feed_theme): ?>   selected="selected"  <?php endif; ?> ><?php _e("Dark"); ?></option>
                        </select>

                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e('Conversation'); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e('When set to Off, only the cited Tweet will be displayed even if it is in reply to another Tweet.'); ?></small>

                        <select name="twitter_feed_conversation" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true">
                            <option value="yes" <?php if ('yes' == $twitter_feed_conversation): ?>   selected="selected"  <?php endif; ?> ><?php _e("On"); ?></option>
                            <option value="none" <?php if ('none' == $twitter_feed_conversation): ?>   selected="selected"  <?php endif; ?> ><?php _e("Off"); ?></option>
                        </select>

                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e('Cards'); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e('When set to hidden, links in a Tweet are not expanded to photo, video, or link previews.'); ?></small>

                        <select name="twitter_feed_cards" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true">
                            <option value="yes" <?php if ('yes' == $twitter_feed_cards): ?>   selected="selected"  <?php endif; ?> ><?php _e("Show"); ?></option>
                            <option value="hidden" <?php if ('hidden' == $twitter_feed_cards): ?>   selected="selected"  <?php endif; ?> ><?php _e("Hidden"); ?></option>
                        </select>

                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e('Align'); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e('Float the Tweet left, right, or center relative to its container. Typically set to allow text or other content to wrap around the Tweet.'); ?></small>

                        <select name="twitter_feed_align" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true">
                            <option value="left" <?php if ('left' == $twitter_feed_align): ?>   selected="selected"  <?php endif; ?> ><?php _e("Left"); ?></option>
                            <option value="center" <?php if ('center' == $twitter_feed_align): ?>   selected="selected"  <?php endif; ?> ><?php _e("Center"); ?></option>
                            <option value="right" <?php if ('right' == $twitter_feed_align): ?>   selected="selected"  <?php endif; ?> ><?php _e("Right"); ?></option>
                        </select>

                    </div>

<!--                    <p>--><?php //_e('Get your Twitter access keys'); ?><!-- <a href="https://apps.twitter.com/app" target="_blank" class="mw-ui-link">--><?php //_e('from here'); ?><!--</a></p>-->
                </div>
                <!-- Settings Content - End -->
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
