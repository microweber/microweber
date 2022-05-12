
<?php $twitter_feed_theme = get_option('twitter_feed_theme', $params['id']); ?>
<?php $twitter_feed_width = get_option('twitter_feed_width', $params['id']); ?>
<?php $twitter_feed_align = get_option('twitter_feed_align', $params['id']); ?>
<?php $twitter_feed_conversation = get_option('twitter_feed_conversation', $params['id']); ?>
<?php $twitter_feed_cards = get_option('twitter_feed_cards', $params['id']); ?>


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
