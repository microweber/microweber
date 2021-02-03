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
            $('#i_agree_with_terms_row').click(function () {
                var el = $('.i_agree_with_terms');
                if (el.is(':checked')) {
                    $('#complete_order_button').removeAttr('disabled');
                } else {
                    $('#complete_order_button').attr('disabled', 'disabled');

                }
            });
        });
    </script>

    <div class="form-group my-4">
        <div class="custom-control custom-checkbox" id="i_agree_with_terms_row" style="line-height: normal;" >
            <input type="checkbox" class="custom-control-input i_agree_with_terms" name="terms" id="customCheck1">
            <label class="custom-control-label" for="customCheck1"><?php _lang('I agree with the Terms and Conditiots', "templates/big"); ?>
                <a href="<?php print $terms_url ?>" target="_blank" class="mw-checkout-terms-and-conditions-link-check"><?php _lang('Terms and Conditions', "templates/big"); ?></a>
            </label>
        </div>
    </div>
<?php endif; ?>
