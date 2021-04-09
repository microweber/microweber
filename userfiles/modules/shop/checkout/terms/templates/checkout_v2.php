<?php

/*

type: layout

name: Default

description: Default terms template

*/

?>

<?php if ($terms): ?>
    <script>
        $(document).ready(function () {
            $('.js-finish-your-order').attr('disabled', 'disabled');
            $('.i_agree_with_terms').change(function () {
                var el = $('.i_agree_with_terms');
                if (el.is(':checked')) {
                    $('.js-finish-your-order').removeAttr('disabled');
                } else {
                    $('.js-finish-your-order').attr('disabled', 'disabled');
                }
            });
        });
    </script>

    <div class="form-group my-4">
        <div class="custom-control custom-checkbox" id="i_agree_with_terms_row" style="line-height: normal;" >
            <input type="checkbox" class="custom-control-input i_agree_with_terms" name="terms" id="customCheck1">
            <label class="custom-control-label" for="customCheck1"><?php _e('I agree with the Terms and Conditiots'); ?>
                <a href="<?php print $terms_url ?>" target="_blank" class="mw-checkout-terms-and-conditions-link-check"><?php _e('Terms and Conditions'); ?></a>
            </label>
        </div>
    </div>
<?php endif; ?>
