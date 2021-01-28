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
                var el = $('#i_agree_with_terms');
                if (el.is(':checked')) {
                    $('#complete_order_button').removeAttr('disabled');
                } else {
                    $('#complete_order_button').attr('disabled', 'disabled');

                }
            });
        });
    </script>

    <div id="i_agree_with_terms_row">
        <label>
            <input type="checkbox" name="terms" id="i_agree_with_terms" value="1" autocomplete="off"/>
            <?php _e('I agree with the'); ?>
            <a href="<?php print $terms_url ?>" target="_blank" class="mw-checkout-terms-and-conditions-link-check"><?php _e('Terms and Conditions'); ?></a>
        </label>
    </div>
<?php endif; ?>
