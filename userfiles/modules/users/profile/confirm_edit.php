<div class="row" style="margin-top: 20px;margin-bottom: 20px;">
    <div class="col-xs-12">
        <div class="form-group">
            <h5><?php _e('Confirm edit of profile'); ?></h5>
            <label class="mw-ui-check" style="margin-left: 0;">
                <input type="checkbox" name="token" value="<?php print csrf_token() ?>" autocomplete="off"/>
                <span></span>&nbsp;
                <span><?php _e('Confirm'); ?></span>
            </label>
        </div>
    </div>
</div>