<?php


$terms_label = get_option('terms_label', 'users');
$terms_url = get_option('terms_url', 'users');

$terms_url = get_option('terms_url', 'users');
if (!$terms_url) {
    $terms_url = site_url() . 'terms';
}


if (!$terms_label) {
    $terms_label = _e('I agree with the Terms and Conditions', true);
}

?>
<div style="margin-top: 10px;margin-bottom: 10px;">
    <div class="i_agree_with_terms_row_popup">

        <div class="mw-flex-row">
            <div class="mw-flex-col-xs-8">
                <div class="box">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox my-2">
                            <label class="mw-ui-check">
                                <input type="checkbox" name="terms" required="true" class="i_agree_with_terms_input" value="1" autocomplete="off" />
                                <span></span><span><?php print $terms_label; ?></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mw-flex-col-xs-4 text-end text-right">
                <div class="box"><a class="mw-ui-btn mw-ui-btn-small mw-ui-btn-primary"
                                    href="<?php print $terms_url; ?>" target="_blank"><?php _e("Read more"); ?></a>
                </div>
            </div>
        </div>


    </div>
</div>
