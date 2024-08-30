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

            var el = $('.i_agree_with_terms');

            $('.i_agree_with_terms').change(function () {
                if (el.is(':checked')) {
                    $('.terms-conditions-text-error').hide();
                    $('.js-finish-your-order').removeAttr('disabled');
                } else {
                    $('.terms-conditions-text-error').fadeIn();
                    $('.js-finish-your-order').attr('disabled', 'disabled');
                }
            });
            $('.js-finish-your-order').click(function (e) {
                if (el.is(':checked')) {

                } else {
                    e.preventDefault();
                    $('.terms-conditions-text-error').fadeIn();
                }
            });
        });
    </script>

    <div class="form-group my-4">
        <div class="custom-control custom-checkbox my-2" id="i_agree_with_terms_row" style="line-height: normal;" >
            <input type="checkbox" class="form-check-input i_agree_with_terms" name="terms" id="customCheck1">
            <label class="custom-control-label" for="customCheck1"><?php _e('I agree with the Terms and Conditions'); ?>
                <a href="<?php print $terms_url ?>" target="_blank" class="mw-checkout-terms-and-conditions-link-check"><?php _e('Terms and Conditions'); ?></a>
            </label>
            <div>
            <small class="terms-conditions-text-error text-danger" style="display:none"><?php _e('You must agree Terms and Conditions'); ?></small>
            </div>
        </div>
    </div>
<?php endif; ?>
