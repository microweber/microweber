<?php
$terms_label = get_option('terms_label', 'users');
$terms_label_cleared = str_replace('&nbsp;', '', $terms_label);
$terms_label_cleared = strip_tags($terms_label_cleared);
$terms_label_cleared = mb_trim($terms_label_cleared);

if ($terms_label_cleared == '') {
    $terms_label = 'I agree with <a href="' . site_url() . 'terms" target="_blank">terms and conditions</a>';
}
?>
<div class="row" style="margin-top: 20px;margin-bottom: 20px;">
    <div class="col-xs-12" class="i_agree_with_terms_row_popup">
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <h5><?php _e('You must agree to terms and conditions to continue'); ?></h5>
                <label class="mw-ui-check">
                    <input type="checkbox" name="terms" class="i_agree_with_terms_input" value="1" autocomplete="off"/> &nbsp;
                    <span><?php print $terms_label; ?></span>
                </label>
            </div>
        </div>


    </div>
</div>