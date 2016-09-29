<?php only_admin_access(); ?>
<script type="text/javascript">
    mw.require('options.js');
</script>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('#mw-twitter-feed-settings-holder', function () {
            if (mw.notification != undefined) {
                mw.notification.success('Twitter feed settings saved');
            }
			mw.reload_module_parent('twitter_feed'); //reload the module in live edit

        });
        mw.tabs({
            tabs: '.tab',
            nav: '.mw-ui-btn-nav a'
        });
    });
</script>

<div class="mw-ui-box-content" id="mw-twitter-feed-settings-holder">
  <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs"><a href="javascript:;" class="mw-ui-btn active"> Feed settings </a> <a
            href="javascript:;" class="mw-ui-btn"> Skin/Template </a> <a href="javascript:;" class="mw-ui-btn"> Access token </a> </div>
  <div class="tab mw-ui-box mw-ui-box-content" style="display: block">
    <div class="mw-ui-field-holder">
      <label class="mw-ui-label">Search string <a href="https://dev.twitter.com/rest/public/search" target="_blank">[?]</a></label>
      <input type="text" class="mw_option_field mw-ui-field w100" name="search_string" value="<?php print get_option('search_string', $params['id']); ?>">
    </div>
    <div class="mw-ui-field-holder">
      <label class="mw-ui-label">Number of items</label>
      <input type="number" class="mw_option_field mw-ui-field"  name="number_of_items" value="<?php print get_option('number_of_items', $params['id']); ?>">
    </div>
  </div>
  <div class="tab mw-ui-box mw-ui-box-content" style="display: none">
    <module type="admin/modules/templates"/>
  </div>
  <div class="tab mw-ui-box mw-ui-box-content" style="display: none">
    <div class="mw-ui-field-holder">
      <label class="mw-ui-label">Consumer Key</label>
      <input type="text" class="mw_option_field mw-ui-field w100" name="consumer_key" option-group="twitter_feed" value="<?php print get_option('consumer_key', 'twitter_feed'); ?>">
    </div>
    <div class="mw-ui-field-holder">
      <label class="mw-ui-label">Consumer Secret</label>
      <input type="text" class="mw_option_field mw-ui-field w100" name="consumer_secret" option-group="twitter_feed" value="<?php print get_option('consumer_secret', 'twitter_feed'); ?>">
    </div>
    <div class="mw-ui-field-holder">
      <label class="mw-ui-label">Access Token</label>
      <input type="text" class="mw_option_field mw-ui-field w100" name="access_token" option-group="twitter_feed" value="<?php print get_option('access_token', 'twitter_feed'); ?>">
    </div>
    <div class="mw-ui-field-holder">
      <label class="mw-ui-label">Access Token Secret</label>
      <input type="text" class="mw_option_field mw-ui-field w100" name="access_token_secret"  option-group="twitter_feed" value="<?php print get_option('access_token_secret', 'twitter_feed'); ?>">
    </div>
    <p>Get your Twitter access keys <a href="https://apps.twitter.com/app" target="_blank" class="mw-ui-link">from here</a></p>
  </div>
</div>
